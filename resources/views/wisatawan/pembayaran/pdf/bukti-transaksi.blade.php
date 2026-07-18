<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 portrait;
            margin: 24px 30px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #202622;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .document-header {
            border-bottom: 3px solid #000000;
            margin-bottom: 15px;
            padding-bottom: 12px;
        }

        .document-header td {
            vertical-align: top;
        }

        .company-cell {
            width: 58%;
        }

        .document-cell {
            width: 42%;
            text-align: right;
        }

        .company-table {
            width: auto;
        }

        .company-table td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 62px;
            padding-right: 11px;
        }

        .logo {
            width: 50px;
            max-height: 50px;
            object-fit: contain;
        }

        .company-name {
            margin: 0 0 2px;
            color: #18201b;
            font-size: 16px;
            font-weight: 700;
        }

        .company-legal {
            margin-bottom: 3px;
            color: #000000;
            font-size: 9px;
            font-weight: 700;
        }

        .company-contact {
            color: #59615c;
            font-size: 8px;
            line-height: 1.45;
        }

        .document-title {
            margin: 0 0 6px;
            color: #18201b;
            font-size: 20px;
            font-weight: 700;
        }

        .document-meta {
            margin-left: auto;
            width: 92%;
        }

        .document-meta td {
            padding: 1px 0 1px 8px;
        }

        .document-meta .meta-label {
            width: 42%;
            color: #69716c;
            text-align: left;
        }

        .document-meta .meta-value {
            color: #202622;
            font-weight: 700;
            text-align: right;
        }

        .section {
            margin-top: 13px;
        }

        .section-title {
            margin: 0;
            padding: 0 0 5px;
            border-bottom: 1px solid #000000;
            color: #202622;
            font-size: 11px;
            font-weight: 700;
        }

        .customer-payment {
            margin-top: 12px;
            border: 1px solid #bfc5c1;
        }

        .customer-payment > tbody > tr > td {
            width: 50%;
            padding: 10px 12px;
            vertical-align: top;
        }

        .customer-payment > tbody > tr > td:first-child {
            border-right: 1px solid #bfc5c1;
        }

        .block-label {
            margin-bottom: 6px;
            color: #000000;
            font-size: 9px;
            font-weight: 700;
        }

        .customer-name {
            margin-bottom: 2px;
            color: #000000;
            font-size: 11px;
            font-weight: 700;
        }

        .detail-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .detail-table .detail-label {
            width: 42%;
            color: #69716c;
        }

        .detail-table .detail-value {
            font-weight: 700;
        }

        .invoice-table {
            margin-top: 8px;
            border: 1px solid #aeb6b1;
        }

        .invoice-table th {
            padding: 7px 8px;
            border-right: 1px solid #aeb6b1;
            border-bottom: 1px solid #aeb6b1;
            background: #ecefed;
            color: #000000;
            font-size: 8px;
            font-weight: 700;
            text-align: left;
        }

        .invoice-table td {
            padding: 8px;
            border-right: 1px solid #cbd0cd;
            border-bottom: 1px solid #cbd0cd;
            vertical-align: top;
        }

        .invoice-table th:last-child,
        .invoice-table td:last-child {
            border-right: 0;
        }

        .invoice-table .description {
            width: 49%;
        }

        .invoice-table .quantity {
            width: 12%;
            text-align: center;
        }

        .invoice-table .money {
            width: 19.5%;
            text-align: right;
        }

        .item-name {
            font-weight: 700;
        }

        .item-note {
            margin-top: 2px;
            color: #69716c;
            font-size: 8px;
        }

        .invoice-table .total-label,
        .invoice-table .total-value {
            padding-top: 9px;
            padding-bottom: 9px;
            border-top: 2px solid #000000;
            border-bottom: 0;
            font-size: 11px;
            font-weight: 700;
        }

        .invoice-table .total-label {
            text-align: right;
        }

        .invoice-table .total-value {
            color: #000000;
            text-align: right;
        }

        .travel-table,
        .participant-table {
            margin-top: 8px;
            border: 1px solid #aeb6b1;
        }

        .travel-table th,
        .participant-table th {
            padding: 6px 7px;
            border-right: 1px solid #aeb6b1;
            border-bottom: 1px solid #aeb6b1;
            background: #ecefed;
            font-size: 8px;
            font-weight: 700;
            text-align: left;
        }

        .travel-table td,
        .participant-table td {
            padding: 6px 7px;
            border-right: 1px solid #cbd0cd;
            border-bottom: 1px solid #cbd0cd;
            vertical-align: top;
        }

        .travel-table th:last-child,
        .travel-table td:last-child,
        .participant-table th:last-child,
        .participant-table td:last-child {
            border-right: 0;
        }

        .participant-table tbody tr:last-child td,
        .travel-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .participant-table thead {
            display: table-header-group;
        }

        .participant-table tr {
            page-break-inside: avoid;
        }

        .participant-table .number {
            width: 5%;
            text-align: center;
        }

        .participant-table .participant-name-col {
            width: 23%;
        }

        .participant-table .contact-col {
            width: 31%;
        }

        .participant-table .gender-col {
            width: 15%;
        }

        .participant-table .birth-col {
            width: 16%;
        }

        .participant-table .phone-col {
            width: 15%;
        }

        .settlement {
            margin-top: 14px;
            page-break-inside: avoid;
        }

        .settlement td {
            vertical-align: bottom;
        }

        .stamp-cell {
            width: 28%;
            vertical-align: middle !important;
        }

        .stamp {
            display: inline-block;
            min-width: 112px;
            padding: 8px 10px 6px;
            border: 3px double #1f6a45;
            color: #1f6a45;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            text-align: center;
        }

        .stamp small {
            display: block;
            margin-top: 5px;
            font-size: 7px;
            font-weight: 400;
        }

        .verification-cell {
            width: 38%;
            padding: 0 18px;
            color: #59615c;
            font-size: 8px;
            line-height: 1.5;
        }

        .signature-cell {
            width: 34%;
            text-align: center;
        }

        .signature-space {
            height: 30px;
        }

        .signature-line {
            border-top: 1px solid #000000;
            padding-top: 4px;
            font-weight: 700;
        }

        .signature-role {
            color: #69716c;
            font-size: 8px;
        }

        .terms {
            margin-top: 14px;
            padding-top: 7px;
            border-top: 1px solid #aeb6b1;
            color: #69716c;
            font-size: 7px;
            line-height: 1.45;
        }

        .terms strong {
            color: #303632;
        }

        .footer {
            margin-top: 7px;
            color: #59615c;
            font-size: 7px;
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    @php
        $totalPembayaran = $pembayaran->jumlah ?? $reservasi->total_harga;
        $jumlahPeserta = (int) $reservasi->jml_peserta;
        $hargaSatuan = $jumlahPeserta > 0 ? ((float) $totalPembayaran / $jumlahPeserta) : (float) $totalPembayaran;
        $tanggalTerbit = $pembayaran->paid_at
            ? \Illuminate\Support\Carbon::parse($pembayaran->paid_at)
            : now();
        $logoPath = file_exists(public_path('images/logo/logo.png'))
            ? public_path('images/logo/logo.png')
            : public_path('images/logo/logo.webp');
    @endphp

    <div class="document-header">
        <table>
            <tr>
                <td class="company-cell">
                    <table class="company-table">
                        <tr>
                            <td class="logo-cell">
                                @if(file_exists($logoPath))
                                    <img class="logo" src="{{ $logoPath }}" alt="Logo GateForestTrip">
                                @endif
                            </td>
                            <td>
                                <div class="company-name">GateForestTrip</div>
                                <div class="company-legal">CV. Geopartner Solution Partnership</div>
                                <div class="company-contact">
                        
                                    gateforesttrip@gmail.com · instagram.com/gateforesttrip
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="document-cell">
                    <div class="document-title">Bukti Pembayaran</div>
                    <table class="document-meta">
                        <tr>
                            <td class="meta-label">Nomor dokumen</td>
                            <td class="meta-value">{{ $reservasi->kode_reservasi }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Nomor order</td>
                            <td class="meta-value">{{ $pembayaran->orderId }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Tanggal terbit</td>
                            <td class="meta-value">{{ $tanggalTerbit->translatedFormat('d M Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="customer-payment">
        <tr>
            <td>
                <div class="block-label">Ditagihkan kepada</div>
                <div class="customer-name">{{ $reservasi->user?->nama ?? '-' }}</div>
                <div>{{ $reservasi->user?->email ?? '-' }}</div>
                <div>Kode reservasi: <strong>{{ $reservasi->kode_reservasi }}</strong></div>
            </td>
            <td>
                <div class="block-label">Informasi pembayaran</div>
                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Metode</td>
                        <td class="detail-value">{{ $pembayaran->metode_pembayaran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Status</td>
                        <td class="detail-value">{{ $pembayaran->status }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Waktu bayar</td>
                        <td class="detail-value">{{ $pembayaran->paid_at ? \Illuminate\Support\Carbon::parse($pembayaran->paid_at)->translatedFormat('d M Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Referensi</td>
                        <td class="detail-value">{{ $pembayaran->orderId }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Rincian tagihan</div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th class="description">Deskripsi</th>
                    <th class="quantity">Jumlah</th>
                    <th class="money">Harga satuan</th>
                    <th class="money">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="description">
                        <div class="item-name">{{ $reservasi->jadwal?->paketTrip?->nama ?? '-' }}</div>
                        <div class="item-note">Reservasi perjalanan {{ $reservasi->kode_reservasi }}</div>
                    </td>
                    <td class="quantity">{{ $reservasi->jml_peserta }}</td>
                    <td class="money nowrap">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                    <td class="money nowrap">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="total-label">Total dibayar</td>
                    <td class="total-value nowrap">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Data perjalanan</div>
        <table class="travel-table">
            <thead>
                <tr>
                    <th>Paket perjalanan</th>
                    <th>Tanggal berangkat</th>
                    <th>Tanggal kembali</th>
                    <th>Jumlah peserta</th>
                    <th>Status reservasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $reservasi->jadwal?->paketTrip?->nama ?? '-' }}</strong></td>
                    <td>{{ $reservasi->jadwal?->tanggal_berangkat ? \Illuminate\Support\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->translatedFormat('d M Y') : '-' }}</td>
                    <td>{{ $reservasi->jadwal?->tanggal_kembali ? \Illuminate\Support\Carbon::parse($reservasi->jadwal->tanggal_kembali)->translatedFormat('d M Y') : '-' }}</td>
                    <td>{{ $reservasi->jml_peserta }} orang</td>
                    <td>{{ ucfirst($reservasi->status) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Daftar peserta</div>
        <table class="participant-table">
            <thead>
                <tr>
                    <th class="number">No.</th>
                    <th class="participant-name-col">Nama peserta</th>
                    <th class="contact-col">Email</th>
                    <th class="gender-col">Jenis kelamin</th>
                    <th class="birth-col">Tanggal lahir</th>
                    <th class="phone-col">No. HP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservasi->peserta as $peserta)
                    <tr>
                        <td class="number">{{ $loop->iteration }}</td>
                        <td><strong>{{ $peserta->nama }}</strong></td>
                        <td>{{ $peserta->email ?? '-' }}</td>
                        <td>{{ $peserta->jenis_kelamin }}</td>
                        <td>{{ $peserta->tanggal_lahir ? \Illuminate\Support\Carbon::parse($peserta->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</td>
                        <td>{{ $peserta->no_hp ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Data peserta belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <table class="settlement">
        <tr>
            <td class="stamp-cell">
                <div class="stamp">
                    LUNAS / PAID
                    <small>{{ $pembayaran->paid_at ? \Illuminate\Support\Carbon::parse($pembayaran->paid_at)->translatedFormat('d M Y') : $tanggalTerbit->translatedFormat('d M Y') }}</small>
                </div>
            </td>
            <td class="verification-cell">
                Dokumen ini diterbitkan secara elektronik dan merupakan bukti pembayaran yang sah untuk reservasi <strong>{{ $reservasi->kode_reservasi }}</strong>.
            </td>
            <td class="signature-cell">
                <div>Hormat kami,</div>
                <div class="signature-space"></div>
                <div class="signature-line">GateForestTrip</div>
                <div class="signature-role">Diterbitkan oleh sistem</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        GateForestTrip · gateforesttrip@gmail.com · Dokumen {{ $reservasi->kode_reservasi }}
    </div>
</body>
</html>
