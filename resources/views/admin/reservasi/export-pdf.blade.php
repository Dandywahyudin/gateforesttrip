<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }
        h1, h2, h3, p {
            margin: 0;
        }
        .header {
            margin-bottom: 18px;
        }
        .meta {
            margin-top: 6px;
            color: #6b7280;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px 6px;
            vertical-align: top;
            text-align: left;
        }
        th {
            background: #f9fafb;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 999px;
            background: #f3f4f6;
            font-size: 9px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Reservasi</h1>
        <p class="meta">Dibuat pada {{ $generatedAt->translatedFormat('d M Y H:i') }}</p>
        @if($paketTrip)
            <p class="meta">Filter paket: {{ $paketTrip->nama }}</p>
        @endif
        @if($jadwalTrip)
            <p class="meta">Filter jadwal: {{ \Illuminate\Support\Carbon::parse($jadwalTrip->tanggal_berangkat)->translatedFormat('d M Y') }}</p>
        @endif
        @if($status !== '')
            <p class="meta">Hanya reservasi dengan pembayaran lunas</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Paket</th>
                <th>Wisatawan</th>
                <th>Tanggal Berangkat</th>
                <th>Peserta</th>
                <th>Status Reservasi</th>
                <th>Status Pembayaran</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasis as $reservasi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reservasi->kode_reservasi }}</td>
                    <td>{{ $reservasi->jadwal?->paketTrip?->nama }}</td>
                    <td>{{ $reservasi->user?->nama }}</td>
                    <td>{{ $reservasi->jadwal?->tanggal_berangkat ? \Illuminate\Support\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->translatedFormat('d M Y') : '-' }}</td>
                        <td>
                            <div>{{ $reservasi->jml_peserta }} orang</div>
                            @php
                                $namaPeserta = $reservasi->peserta?->pluck('nama')->filter()->values();
                            @endphp
                            @if($namaPeserta->count() > 1)
                                <div style="margin-top: 4px; font-size: 9px; color: #6b7280; line-height: 1.4;">
                                    {{ $namaPeserta->implode(', ') }}
                                </div>
                            @endif
                        </td>
                    <td><span class="badge">{{ $reservasi->status }}</span></td>
                    <td><span class="badge">{{ $reservasi->pembayaran?->status ?? 'belum ada' }}</span></td>
                    <td>Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Tidak ada data reservasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>