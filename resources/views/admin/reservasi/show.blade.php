@extends('layouts.admin')

@section('title', 'Detail Reservasi')
@section('page-title', 'Detail Reservasi')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">Kode Reservasi</p>
                    <h3 class="mt-2 text-3xl font-display font-black uppercase text-gray-900">{{ $reservasi->kode_reservasi }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $reservasi->jadwal?->paketTrip?->nama }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.reservasi.index') }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Kembali</a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <h4 class="text-2xl font-display font-black uppercase text-gray-900">Informasi Reservasi</h4>
                <dl class="mt-5 grid gap-4 sm:grid-cols-2 text-sm">
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <dt class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-2">Wisatawan</dt>
                        <dd class="font-black text-gray-900">{{ $reservasi->user?->nama }}</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <dt class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-2">Paket Trip</dt>
                        <dd class="font-black text-gray-900">{{ $reservasi->jadwal?->paketTrip?->nama }}</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <dt class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-2">Tanggal Berangkat</dt>
                        <dd class="font-black text-gray-900">{{ $reservasi->jadwal?->tanggal_berangkat ? \Illuminate\Support\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->translatedFormat('d M Y') : '-' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <dt class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-2">Total Peserta</dt>
                        <dd class="font-black text-gray-900">{{ $reservasi->jml_peserta }}</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <dt class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-2">Status Reservasi</dt>
                        <dd class="font-black text-gray-900 uppercase">{{ $reservasi->status }}</dd>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <dt class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-2">Total Harga</dt>
                        <dd class="font-black text-gray-900">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <h4 class="text-2xl font-display font-black uppercase text-gray-900">Pembayaran</h4>
                <div class="mt-5 space-y-3 text-sm">
                    <div class="rounded-2xl bg-gray-50 p-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <span class="text-gray-500">Order ID</span>
                        <span class="font-black text-gray-900 break-all">{{ $reservasi->pembayaran?->orderId ?? '-' }}</span>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <span class="text-gray-500">Status Midtrans</span>
                        <span class="font-black text-gray-900 uppercase">{{ $reservasi->pembayaran?->status ?? '-' }}</span>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-black text-gray-900 uppercase">{{ $reservasi->pembayaran?->metode_pembayaran ?? '-' }}</span>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <span class="text-gray-500">Paid At</span>
                        <span class="font-black text-gray-900">{{ $reservasi->pembayaran?->paid_at ? $reservasi->pembayaran->paid_at->format('d M Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <h4 class="text-2xl font-display font-black uppercase text-gray-900">Daftar Peserta</h4>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($reservasi->peserta as $peserta)
                    <article class="rounded-2xl bg-gray-50 p-4">
                        <h5 class="text-lg font-black text-gray-900">{{ $peserta->nama }}</h5>
                        <div class="mt-3 space-y-1 text-sm text-gray-600">
                            <p>Identitas: {{ $peserta->jenis_identitas }}</p>
                            <p>No: {{ $peserta->no_identitas }}</p>
                            <p>JK: {{ $peserta->jenis_kelamin }}</p>
                            <p>Lahir: {{ $peserta->tanggal_lahir ? \Illuminate\Support\Carbon::parse($peserta->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</p>
                            <p>HP: {{ $peserta->no_hp ?? '-' }}</p>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-200 p-4 text-sm text-gray-500">Belum ada data peserta.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection