<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 18px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #123524;
            margin: 0;
            padding: 0;
            background: #eef5ef;
        }

        .page {
            padding: 0;
        }

        .ticket {
            position: relative;
            background: #ffffff;
            border: 1px solid #d7e5d9;
            border-radius: 18px;
            overflow: hidden;
        }

        .ticket::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 14px;
            height: 100%;
            background: linear-gradient(180deg, #15803d 0%, #22c55e 100%);
        }

        .ticket-inner {
            padding: 20px 20px 18px 30px;
        }

        .hero {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .hero-left,
        .hero-right {
            display: table-cell;
            vertical-align: top;
        }

        .hero-right {
            text-align: right;
        }

        .brand {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.34em;
            text-transform: uppercase;
            color: #15803d;
        }

        h1 {
            margin: 7px 0 4px;
            font-size: 23px;
            line-height: 1.05;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .subtitle {
            color: #5f7264;
            font-size: 10px;
            line-height: 1.5;
            max-width: 420px;
        }

        .code-pill {
            display: inline-block;
            padding: 9px 12px;
            border-radius: 999px;
            background: #f3faf4;
            border: 1px solid #cfe0d3;
            color: #15803d;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .notice {
            margin-top: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: #f7fbf7;
            border: 1px dashed #cfe0d3;
            color: #4e6254;
            font-size: 10px;
            line-height: 1.5;
        }

        .main {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .col {
            display: table-cell;
            vertical-align: top;
        }

        .col.left {
            width: 56%;
            padding-right: 10px;
        }

        .col.right {
            width: 44%;
            padding-left: 10px;
        }

        .panel {
            border: 1px solid #dbe7dd;
            border-radius: 16px;
            padding: 15px;
            background: #ffffff;
            margin-bottom: 12px;
        }

        .panel-title {
            margin: 0 0 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #123524;
        }

        .panel-subtitle {
            margin: 0 0 12px;
            color: #5f7264;
            font-size: 10px;
            line-height: 1.5;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
        }

        .grid td {
            padding: 6px 0;
            vertical-align: top;
        }

        .label {
            width: 38%;
            color: #6b7f70;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }

        .value {
            font-weight: 700;
            color: #123524;
        }

        .amount {
            font-size: 15px;
            font-weight: 700;
            color: #15803d;
        }

        .divider {
            position: relative;
            margin: 12px 0;
            height: 18px;
        }

        .divider::before {
            content: "";
            position: absolute;
            top: 8px;
            left: 0;
            right: 0;
            border-top: 1px dashed #c7d8ca;
        }

        .divider span {
            position: relative;
            display: inline-block;
            padding: 0 10px;
            margin-left: 16px;
            background: #ffffff;
            color: #6b7f70;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.22em;
        }

        .participants {
            margin-top: 8px;
        }

        .participant {
            padding: 8px 0;
            border-top: 1px solid #edf3ee;
        }

        .participant:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .participant-name {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .muted {
            color: #5f7264;
        }

        .fare-box {
            border-radius: 14px;
            background: linear-gradient(180deg, #f4fbf5 0%, #edf8ef 100%);
            border: 1px solid #d7e5d9;
            padding: 12px;
            margin-top: 10px;
        }

        .fare-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .fare-row:last-child {
            margin-bottom: 0;
        }

        .fare-label,
        .fare-value {
            display: table-cell;
            vertical-align: top;
        }

        .fare-label {
            color: #6b7f70;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }

        .fare-value {
            text-align: right;
            font-weight: 700;
            color: #123524;
        }

        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
            color: #708277;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="ticket">
            <div class="ticket-inner">
                <div class="hero">
                    <div class="hero-left">
                        <div class="brand">GateForestTrip</div>
                        <h1>Tiket Resmi Perjalanan</h1>
                        <div class="subtitle">Dokumen perjalanan resmi untuk reservasi yang sudah berhasil dibayar. Simpan dan tunjukkan saat check-in atau keberangkatan.</div>
                    </div>
                    <div class="hero-right">
                        <div class="code-pill">{{ $reservasi->kode_reservasi }}</div>
                        <div class="notice">
                            Status: {{ ucfirst($reservasi->status) }}<br>
                            Pembayaran: {{ $pembayaran->status }}
                        </div>
                    </div>
                </div>

                <div class="main">
                    <div class="col left">
                        <div class="panel">
                            <h2 class="panel-title">Informasi Tiket</h2>
                            <p class="panel-subtitle">Data perjalanan dan peserta yang tercantum pada tiket resmi.</p>

                            <table class="grid">
                                <tr>
                                    <td class="label">Paket Trip</td>
                                    <td class="value">{{ $reservasi->jadwal?->paketTrip?->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Tanggal Berangkat</td>
                                    <td class="value">{{ $reservasi->jadwal?->tanggal_berangkat ? \Illuminate\Support\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->translatedFormat('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Jumlah Peserta</td>
                                    <td class="value">{{ $reservasi->jml_peserta }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Kode Reservasi</td>
                                    <td class="value">{{ $reservasi->kode_reservasi }}</td>
                                </tr>
                            </table>

                            <div class="divider"><span>Daftar Peserta</span></div>

                            <div class="participants">
                                @foreach($reservasi->peserta as $peserta)
                                    <div class="participant">
                                        <div class="participant-name">{{ $peserta->nama }}</div>
                                        <div class="muted">Email: {{ $peserta->email ?? '-' }}</div>
                                        <div class="muted">Jenis Kelamin: {{ $peserta->jenis_kelamin }}</div>
                                        <div class="muted">Tanggal Lahir: {{ $peserta->tanggal_lahir ? \Illuminate\Support\Carbon::parse($peserta->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</div>
                                        <div class="muted">No. HP: {{ $peserta->no_hp ?? '-' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col right">
                        <div class="panel">
                            <h2 class="panel-title">Bukti Transaksi</h2>
                            <p class="panel-subtitle">Rincian pembayaran yang telah diselesaikan.</p>

                            <table class="grid">
                                <tr>
                                    <td class="label">Order ID</td>
                                    <td class="value">{{ $pembayaran->orderId }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Metode Pembayaran</td>
                                    <td class="value">{{ $pembayaran->metode_pembayaran ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Status Pembayaran</td>
                                    <td class="value">{{ $pembayaran->status }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Waktu Pembayaran</td>
                                    <td class="value">{{ $pembayaran->paid_at ? \Illuminate\Support\Carbon::parse($pembayaran->paid_at)->translatedFormat('d M Y H:i') : '-' }}</td>
                                </tr>
                            </table>

                            <div class="fare-box">
                                <div class="fare-row">
                                    <div class="fare-label">Total Bayar</div>
                                    <div class="fare-value amount">Rp {{ number_format($pembayaran->jumlah ?? $reservasi->total_harga, 0, ',', '.') }}</div>
                                </div>
                                <div class="fare-row">
                                    <div class="fare-label">Tanggal Berangkat</div>
                                    <div class="fare-value">{{ $reservasi->jadwal?->tanggal_berangkat ? \Illuminate\Support\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->translatedFormat('d M Y') : '-' }}</div>
                                </div>
                                <div class="fare-row">
                                    <div class="fare-label">Status Reservasi</div>
                                    <div class="fare-value">{{ ucfirst($reservasi->status) }}</div>
                                </div>
                            </div>

                            <div class="divider"><span>Ringkasan</span></div>

                            <table class="grid">
                                <tr>
                                    <td class="label">Paket Trip</td>
                                    <td class="value">{{ $reservasi->jadwal?->paketTrip?->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Jumlah Peserta</td>
                                    <td class="value">{{ $reservasi->jml_peserta }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Kode Reservasi</td>
                                    <td class="value">{{ $reservasi->kode_reservasi }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    Simpan dokumen ini sebagai tiket resmi dan bukti transaksi GateForestTrip untuk reservasi {{ $reservasi->kode_reservasi }}.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
