@extends('layouts.admin')

@section('title', 'Kelola Reservasi')
@section('page-title', 'Kelola Reservasi')

@section('content')
    <div class="space-y-6">
        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary"> Reservasi</p>
                    <h3 class="mt-2 text-3xl font-display font-black uppercase text-gray-900">Reservasi masuk</h3>
                    <p class="mt-2 text-sm text-gray-500">Pantau reservasi, peserta, dan status pembayaran.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if($paketTrip)
                        <form method="POST" action="{{ route('admin.reservasi.cancel-by-paket', $paketTrip) }}" onsubmit="return confirm('Cancel semua reservasi untuk paket ini? Jadwal akan dinonaktifkan dan paket trip dijadikan nonaktif.');">
                            @csrf
                            <button type="submit" class="rounded-full border border-red-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-red-600 transition hover:border-red-300 hover:bg-red-50">Cancel Paket</button>
                        </form>
                        <a href="{{ route('admin.reservasi.index') }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Semua Reservasi</a>
                    @endif
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full bg-primary px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">Dashboard</a>
                </div>
            </div>

            @php
                $exportQuery = array_filter([
                    'paket' => $paketTrip?->slug,
                    'jadwal' => $jadwalTrip?->jadwalId,
                ]);
            @endphp

            <form method="get" action="{{ route('admin.reservasi.index') }}" class="mt-6 rounded-lg border border-gray-100 bg-gray-50 p-4">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="grid gap-4 lg:grid-cols-4">
                    <div>
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Paket Trip</label>
                        <select name="paket" class="w-full rounded-lg border-gray-200 bg-white text-sm focus:border-primary focus:ring-primary">
                            <option value="">Semua paket</option>
                            @foreach($pakets as $paket)
                                <option value="{{ $paket->slug }}" @selected($paketTrip?->slug === $paket->slug)>{{ $paket->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Jadwal</label>
                        <select name="jadwal" class="w-full rounded-lg border-gray-200 bg-white text-sm focus:border-primary focus:ring-primary">
                            <option value="">Semua jadwal</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->jadwalId }}" @selected($jadwalTrip?->jadwalId === $jadwal->jadwalId)>
                                    {{ $jadwal->paketTrip?->nama }} - {{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2 flex flex-wrap items-end gap-3">
                        <button type="submit" class="rounded-full bg-primary px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">Terapkan Filter</button>
                        @if($paketTrip || $jadwalTrip || $status !== '')
                            <a href="{{ route('admin.reservasi.index') }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Reset</a>
                        @endif
                        <a href="{{ route('admin.reservasi.export.pdf', $exportQuery) }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Export PDF Paid</a>
                        <a href="{{ route('admin.reservasi.export.csv', $exportQuery) }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Export Excel Paid</a>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-500">Export hanya mengambil reservasi yang sudah dibayar.</p>
            </form>

            @if($paketTrip || $jadwalTrip)
                <div class="mt-4 rounded-lg border border-primary/10 bg-primary/5 p-4 text-sm text-gray-700">
                    <span class="font-black uppercase tracking-[0.22em] text-primary">Filter aktif:</span>
                    <span class="ml-2">
                        @if($paketTrip)
                            Paket {{ $paketTrip->nama }}
                        @endif
                        @if($paketTrip && $jadwalTrip)
                            <span class="mx-1 text-gray-400">|</span>
                        @endif
                        @if($jadwalTrip)
                            Jadwal {{ \Illuminate\Support\Carbon::parse($jadwalTrip->tanggal_berangkat)->translatedFormat('d M Y') }}
                        @endif
                    </span>
                </div>
            @endif
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('admin.reservasi.index', array_filter(['paket' => $paketTrip?->slug, 'jadwal' => $jadwalTrip?->jadwalId])) }}" class="rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] {{ $status === '' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-200 text-gray-700 hover:border-primary hover:text-primary' }}">Semua</a>
                <a href="{{ route('admin.reservasi.index', array_filter(['paket' => $paketTrip?->slug, 'jadwal' => $jadwalTrip?->jadwalId, 'status' => 'unpaid'])) }}" class="rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] {{ $status === 'unpaid' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-200 text-gray-700 hover:border-primary hover:text-primary' }}">Unpaid</a>
                <a href="{{ route('admin.reservasi.index', array_filter(['paket' => $paketTrip?->slug, 'jadwal' => $jadwalTrip?->jadwalId, 'status' => 'paid'])) }}" class="rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] {{ $status === 'paid' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-200 text-gray-700 hover:border-primary hover:text-primary' }}">Paid</a>
                <a href="{{ route('admin.reservasi.index', array_filter(['paket' => $paketTrip?->slug, 'jadwal' => $jadwalTrip?->jadwalId, 'status' => 'cancelled'])) }}" class="rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] {{ $status === 'cancelled' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-200 text-gray-700 hover:border-primary hover:text-primary' }}">Cancelled</a>
            </div>
        </div>

        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="min-w-[1200px] w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">No</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Kode</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Paket</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Wisatawan</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Peserta</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Pembayaran</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($reservasis as $reservasi)
                            <tr>
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ $reservasi->kode_reservasi }}</td>
                                <td class="px-4 py-4">
                                    <div class="font-black uppercase text-gray-900">{{ $reservasi->jadwal?->paketTrip?->nama }}</div>
                                    <div class="mt-1 text-xs text-gray-500">{{ $reservasi->jadwal?->tanggal_berangkat ? \Illuminate\Support\Carbon::parse($reservasi->jadwal->tanggal_berangkat)->translatedFormat('d M Y') : '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $reservasi->user?->nama }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $reservasi->jml_peserta }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-lg {{ $reservasi->status === 'paid' ? 'bg-green-100 text-green-700' : ($reservasi->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em]">
                                        {{ $reservasi->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="rounded-lg {{ in_array($reservasi->pembayaran?->status, ['settlement', 'capture'], true) ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em]">
                                        {{ $reservasi->pembayaran?->status ?? 'belum ada' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('admin.reservasi.show', $reservasi) }}" class="rounded-full border border-gray-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada reservasi yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4 overflow-x-auto">
                    {{ $reservasis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection