<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Models\PaketTrip;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservasiController extends BaseController
{
    public function index(Request $request): View
    {
        $status = (string) $request->input('status', '');
        $paketTrip = null;

        if ($request->filled('paket')) {
            $paketSlug = (string) $request->input('paket');
            $paketTrip = PaketTrip::where('slug', $paketSlug)->firstOrFail();
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
            ->latest('reservasiId');

        $reservasis = $reservasisQuery->paginate(10);
        $reservasis->withQueryString();

        return view('admin.reservasi.index', compact('reservasis', 'status', 'paketTrip'));
    }

    public function exportPdf(Request $request)
    {
        [$status, $paketTrip, $reservasis] = $this->resolveExportData($request);

        $pdf = Pdf::loadView('admin.reservasi.export-pdf', [
            'reservasis' => $reservasis,
            'status' => $status,
            'paketTrip' => $paketTrip,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        $filename = 'reservasi-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        [$status, $paketTrip, $reservasis] = $this->resolveExportData($request);
        $filename = 'reservasi-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($reservasis) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'No',
                'Kode Reservasi',
                'Paket Trip',
                'Wisatawan',
                'Tanggal Berangkat',
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
                    $reservasi->user?->nama,
                    $reservasi->jadwal?->tanggal_berangkat ? Carbon::parse($reservasi->jadwal->tanggal_berangkat)->format('Y-m-d') : '-',
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

    private function resolveExportData(Request $request): array
    {
        $status = (string) $request->input('status', '');
        $paketTrip = null;

        if ($request->filled('paket')) {
            $paketSlug = (string) $request->input('paket');
            $paketTrip = PaketTrip::where('slug', $paketSlug)->firstOrFail();
        }

        $reservasis = Reservasi::with(['jadwal.paketTrip', 'user', 'pembayaran'])
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($paketTrip, function ($query) use ($paketTrip) {
                $query->whereHas('jadwal', function ($jadwalQuery) use ($paketTrip) {
                    $jadwalQuery->where('paketId', $paketTrip->paketId);
                });
            })
            ->latest('reservasiId')
            ->get();

        return [$status, $paketTrip, $reservasis];
    }
}