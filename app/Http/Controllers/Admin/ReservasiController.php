<?php

namespace App\Http\Controllers\Admin;

use App\Events\JadwalKuotaUpdated;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Jadwal;
use App\Models\PaketTrip;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservasiController extends BaseController
{
    public function index(Request $request): View
    {
        [$dateFrom, $dateTo] = $this->resolveDateRange($request);

        $status = (string) $request->input('status', '');
        $paketTrip = null;
        $jadwalTrip = null;

        $pakets = PaketTrip::orderBy('nama')->get();

        if ($request->filled('paket')) {
            $paketSlug = (string) $request->input('paket');
            $paketTrip = PaketTrip::where('slug', $paketSlug)->firstOrFail();
        }

        if ($request->filled('jadwal')) {
            $jadwalTrip = Jadwal::with('paketTrip')->whereKey((int) $request->input('jadwal'))->firstOrFail();

            if (! $paketTrip) {
                $paketTrip = $jadwalTrip->paketTrip;
            }
        }

        $jadwals = Jadwal::with('paketTrip')
            ->when($paketTrip, function ($query) use ($paketTrip) {
                $query->where('paketId', $paketTrip->paketId);
            })
            ->orderByDesc('tanggal_berangkat')
            ->get();

        if ($jadwalTrip && $paketTrip && (int) $jadwalTrip->paketId !== (int) $paketTrip->paketId) {
            $jadwalTrip = null;
        }

        $reservasisQuery = Reservasi::with(['jadwal.paketTrip', 'user', 'pembayaran'])
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($paketTrip, function ($query) use ($paketTrip) {
                $query->whereHas('jadwal', function ($jadwalQuery) use ($paketTrip) {
                    $jadwalQuery->where('paketId', $paketTrip->paketId);
                });
            })
            ->when($jadwalTrip, function ($query) use ($jadwalTrip) {
                $query->where('jadwalId', $jadwalTrip->jadwalId);
            })
            ->when($dateFrom || $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereHas('jadwal', function ($jadwalQuery) use ($dateFrom, $dateTo) {
                    $jadwalQuery
                        ->when($dateFrom, function ($query) use ($dateFrom) {
                            $query->whereDate('tanggal_berangkat', '>=', $dateFrom);
                        })
                        ->when($dateTo, function ($query) use ($dateTo) {
                            $query->whereDate('tanggal_berangkat', '<=', $dateTo);
                        });
                });
            })
            ->latest('reservasiId');

        $reservasis = $reservasisQuery->paginate(10);
        $reservasis->withQueryString();

        return view('admin.reservasi.index', compact('reservasis', 'status', 'paketTrip', 'jadwalTrip', 'pakets', 'jadwals', 'dateFrom', 'dateTo'));
    }

    public function exportPdf(Request $request)
    {
        [$status, $paketTrip, $jadwalTrip, $dateFrom, $dateTo, $reservasis] = $this->resolveExportData($request);

        $pdf = Pdf::loadView('admin.reservasi.export-pdf', [
            'reservasis' => $reservasis,
            'status' => $status,
            'paketTrip' => $paketTrip,
            'jadwalTrip' => $jadwalTrip,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        $filename = $this->buildExportFilename('pdf', $paketTrip, $jadwalTrip, $dateFrom, $dateTo);

        return $pdf->download($filename);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        [$status, $paketTrip, $jadwalTrip, $dateFrom, $dateTo, $reservasis] = $this->resolveExportData($request);
        $filename = $this->buildExportFilename('csv', $paketTrip, $jadwalTrip, $dateFrom, $dateTo);

        return response()->streamDownload(function () use ($reservasis) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'Kode Reservasi',
                'Paket Trip',
                'Jadwal',
                'Wisatawan',
                'Peserta',
                'Status Reservasi',
                'Status Pembayaran',
                'Total Harga',
            ]);

            foreach ($reservasis as $index => $reservasi) {
                fputcsv($handle, [
                    $index + 1,
                    $reservasi->kode_reservasi,
                    $reservasi->jadwal?->paketTrip?->nama,
                    $reservasi->jadwal?->jadwalId
                        ? 'Jadwal #' . $reservasi->jadwal->jadwalId . ' - ' . ($reservasi->jadwal->tanggal_berangkat ? Carbon::parse($reservasi->jadwal->tanggal_berangkat)->format('Y-m-d') : '-')
                        : '-',
                    $reservasi->user?->nama,
                    $reservasi->jml_peserta,
                    $reservasi->status,
                    $reservasi->pembayaran?->status ?? 'belum ada',
                    $reservasi->total_harga,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function show(Reservasi $reservasi): View
    {
        $reservasi->load(['jadwal.paketTrip', 'user', 'pembayaran', 'peserta']);

        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function cancelByPaketTrip(Request $request, PaketTrip $paketTrip): RedirectResponse
    {
        if (! $request->filled('jadwal')) {
            return redirect()
                ->route('admin.reservasi.index', ['paket' => $paketTrip->slug])
                ->withErrors([
                    'jadwal' => 'Silakan pilih jadwal terlebih dahulu sebelum melakukan cancel.',
                ]);
        }

        $jadwalTrip = Jadwal::with('paketTrip')
            ->whereKey((int) $request->input('jadwal'))
            ->where('paketId', $paketTrip->paketId)
            ->firstOrFail();

        $result = DB::transaction(function () use ($jadwalTrip) {
            $jadwals = Jadwal::whereKey($jadwalTrip->jadwalId)
                ->lockForUpdate()
                ->get();

            $reservasis = Reservasi::with('pembayaran')
                ->whereHas('jadwal', function ($query) use ($jadwalTrip) {
                    $query->whereKey($jadwalTrip->jadwalId);
                })
                ->lockForUpdate()
                ->get();

            $cancelledReservasiCount = 0;
            $cancelledPaymentCount = 0;

            foreach ($reservasis as $reservasi) {
                if ($reservasi->status !== 'cancelled') {
                    $reservasi->update([
                        'status' => 'cancelled',
                    ]);
                    $cancelledReservasiCount++;
                }

                $pembayaran = $reservasi->pembayaran;

                if ($pembayaran && ! in_array($pembayaran->status, ['settlement', 'capture'], true)) {
                    $pembayaran->update([
                        'status' => 'cancel',
                    ]);
                    $cancelledPaymentCount++;
                }
            }

            foreach ($jadwals as $jadwal) {
                $jadwal->update([
                    'kuota_terisi' => 0,
                    'status' => 'cancelled',
                ]);

                event(JadwalKuotaUpdated::fromJadwal($jadwal->fresh(['paketTrip', 'reservasis.pembayaran'])));
            }

            return [$cancelledReservasiCount, $cancelledPaymentCount, $jadwals->count()];
        });

        [$cancelledReservasiCount, $cancelledPaymentCount, $jadwalCount] = $result;

        return redirect()
            ->route('admin.reservasi.index', ['paket' => $paketTrip->slug, 'jadwal' => $jadwalTrip->jadwalId, 'status' => 'cancelled'])
            ->with('success', sprintf(
                'Berhasil cancel %d reservasi, %d pembayaran, dan %d jadwal untuk paket %s pada jadwal %s.',
                $cancelledReservasiCount,
                $cancelledPaymentCount,
                $jadwalCount,
                $paketTrip->nama,
                Carbon::parse($jadwalTrip->tanggal_berangkat)->translatedFormat('d M Y'),
            ));
    }

    private function resolveExportData(Request $request): array
    {
        [$dateFrom, $dateTo] = $this->resolveDateRange($request);

        $status = 'paid';
        $paketTrip = null;
        $jadwalTrip = null;

        if ($request->filled('paket')) {
            $paketSlug = (string) $request->input('paket');
            $paketTrip = PaketTrip::where('slug', $paketSlug)->firstOrFail();
        }

        if ($request->filled('jadwal')) {
            $jadwalTrip = Jadwal::with('paketTrip')->whereKey((int) $request->input('jadwal'))->firstOrFail();

            if (! $paketTrip) {
                $paketTrip = $jadwalTrip->paketTrip;
            }
        }

        $reservasis = Reservasi::with(['jadwal.paketTrip', 'user', 'pembayaran', 'peserta'])
            ->where('status', 'paid')
            ->when($paketTrip, function ($query) use ($paketTrip) {
                $query->whereHas('jadwal', function ($jadwalQuery) use ($paketTrip) {
                    $jadwalQuery->where('paketId', $paketTrip->paketId);
                });
            })
            ->when($jadwalTrip, function ($query) use ($jadwalTrip) {
                $query->where('jadwalId', $jadwalTrip->jadwalId);
            })
            ->when($dateFrom || $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereHas('jadwal', function ($jadwalQuery) use ($dateFrom, $dateTo) {
                    $jadwalQuery
                        ->when($dateFrom, function ($query) use ($dateFrom) {
                            $query->whereDate('tanggal_berangkat', '>=', $dateFrom);
                        })
                        ->when($dateTo, function ($query) use ($dateTo) {
                            $query->whereDate('tanggal_berangkat', '<=', $dateTo);
                        });
                });
            })
            ->latest('reservasiId')
            ->get();

        return [$status, $paketTrip, $jadwalTrip, $dateFrom, $dateTo, $reservasis];
    }

    private function resolveDateRange(Request $request): array
    {
        $dateToRules = ['nullable', 'date'];

        if ($request->filled('date_from')) {
            $dateToRules[] = 'after_or_equal:date_from';
        }

        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => $dateToRules,
        ]);

        return [
            $validated['date_from'] ?? null,
            $validated['date_to'] ?? null,
        ];
    }

    private function buildExportFilename(string $extension, ?PaketTrip $paketTrip, ?Jadwal $jadwalTrip, ?string $dateFrom = null, ?string $dateTo = null): string
    {
        $parts = ['reservasi-paid'];

        if ($paketTrip) {
            $parts[] = Str::slug($paketTrip->slug ?: $paketTrip->nama);
        }

        if ($jadwalTrip) {
            $parts[] = 'jadwal-' . Carbon::parse($jadwalTrip->tanggal_berangkat)->format('Ymd');
        }

        if ($dateFrom || $dateTo) {
            $parts[] = 'tanggal-' . ($dateFrom ? Carbon::parse($dateFrom)->format('Ymd') : 'awal') . '-sd-' . ($dateTo ? Carbon::parse($dateTo)->format('Ymd') : 'akhir');
        }

        $parts[] = now()->format('Ymd-His');

        return implode('-', $parts) . '.' . $extension;
    }
}
