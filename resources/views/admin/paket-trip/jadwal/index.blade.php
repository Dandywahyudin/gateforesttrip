@extends('layouts.admin')

@section('title', 'Jadwal Paket Trip')
@section('page-title', 'Jadwal Paket Trip')

@section('content')
    <div class="space-y-6">
        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">Jadwal untuk Paket Trip</p>
                    <h3 class="mt-2 text-3xl font-display font-black uppercase text-gray-900">{{ $paketTrip->nama }}</h3>
                    <p class="mt-2 text-sm text-gray-500">Kelola tanggal keberangkatan, kuota, dan harga override.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.paket-trip.index') }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Kembali</a>
                    <a href="{{ route('admin.paket-trip.jadwal.create', $paketTrip) }}" class="rounded-full bg-primary px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">Tambah Jadwal</a>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-1">Harga Paket</p>
                    <p class="text-lg font-black text-gray-900">Rp {{ number_format($paketTrip->harga, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-1">Jadwal Total</p>
                    <p class="text-lg font-black text-gray-900">{{ $jadwals->count() }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-1">Lokasi</p>
                    <p class="text-lg font-black text-gray-900">{{ $paketTrip->lokasi }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400 mb-1">Status Paket</p>
                    <p class="text-lg font-black text-gray-900">{{ $paketTrip->aktif ? 'Aktif' : 'Nonaktif' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="min-w-[1000px] w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Berangkat</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Kembali</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Kuota</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Harga Override</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($jadwals as $jadwal)
                            <tr data-kuota-realtime-channel="jadwal.{{ $jadwal->jadwalId }}" data-jadwal-refresh-url="{{ route('jadwal.realtime', $jadwal) }}" data-jadwal-id="{{ $jadwal->jadwalId }}" data-jadwal-status="{{ $jadwal->status }}" data-jadwal-sisa-kuota="{{ max(0, $jadwal->kuota_max - $jadwal->kuota_terisi) }}">
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_kembali)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600" data-jadwal-field="kuota">{{ $jadwal->kuota_terisi }} / {{ $jadwal->kuota_max }}</td>
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ $jadwal->harga_override ? 'Rp ' . number_format($jadwal->harga_override, 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-lg {{ $jadwal->status === 'open' ? 'bg-green-100 text-green-700' : ($jadwal->status === 'full' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-600') }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em]" data-jadwal-field="status">
                                        {{ $jadwal->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.paket-trip.jadwal.edit', [$paketTrip, $jadwal->jadwalId]) }}" class="rounded-full border border-gray-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Edit</a>
                                        <form method="POST" action="{{ route('admin.paket-trip.jadwal.destroy', [$paketTrip, $jadwal->jadwalId]) }}" onsubmit="return confirm('Hapus jadwal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-red-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600 transition hover:border-red-300 hover:bg-red-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada jadwal untuk paket trip ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection