@extends('layouts.admin')

@section('title', 'Kelola Jadwal')
@section('page-title', 'Kelola Jadwal')

@section('content')
    @php
        $isNested = $isNested ?? (isset($paketTrip) && $paketTrip);
        $filters = $filters ?? [];
        $indexUrl = $isNested
            ? route('admin.paket-trip.jadwal.index', $paketTrip)
            : route('admin.jadwal.index');
        $createUrl = $isNested
            ? route('admin.paket-trip.jadwal.create', $paketTrip)
            : route('admin.jadwal.create', array_filter(['paket_id' => $filters['paket_id'] ?? null]));
    @endphp

    <div class="space-y-6">
        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-primary">{{ $isNested ? 'Jadwal Paket Trip' : 'Pusat Kelola Jadwal' }}</p>
                    <h3 class="mt-2 text-3xl font-display font-black uppercase text-gray-900">{{ $isNested ? $paketTrip->nama : 'Semua jadwal paket trip' }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $isNested ? 'Kelola tanggal keberangkatan, kuota, dan status untuk paket ini.' : 'Tambah, filter, edit, dan hapus jadwal dari satu halaman.' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.paket-trip.index') }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Daftar Paket</a>
                    <a href="{{ $createUrl }}" class="rounded-full bg-primary px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">Tambah Jadwal</a>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ $indexUrl }}" class="grid gap-4 lg:grid-cols-5">
                @unless($isNested)
                    <div>
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="paket_id">Paket</label>
                        <select id="paket_id" name="paket_id" class="w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:border-primary focus:ring-primary">
                            <option value="">Semua Paket</option>
                            @foreach($paketTrips as $optionPaket)
                                <option value="{{ $optionPaket->paketId }}" @selected((string) ($filters['paket_id'] ?? '') === (string) $optionPaket->paketId)>{{ $optionPaket->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endunless

                <div>
                    <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="status">Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:border-primary focus:ring-primary">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $statusOption)
                            <option value="{{ $statusOption }}" @selected(($filters['status'] ?? '') === $statusOption)>{{ ucfirst($statusOption) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="tanggal_mulai">Dari</label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:border-primary focus:ring-primary">
                </div>

                <div>
                    <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="tanggal_selesai">Sampai</label>
                    <input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:border-primary focus:ring-primary">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-primary px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-white transition hover:bg-primary-dark">Filter</button>
                    <a href="{{ $indexUrl }}" class="inline-flex flex-1 items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Reset</a>
                </div>
            </form>
        </div>

        <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="min-w-[1120px] w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">No.</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Paket</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Berangkat</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Kembali</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Cutoff</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Kuota</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($jadwals as $jadwal)
                            @php
                                $editUrl = $isNested
                                    ? route('admin.paket-trip.jadwal.edit', [$paketTrip, $jadwal])
                                    : route('admin.jadwal.edit', $jadwal);
                                $destroyUrl = $isNested
                                    ? route('admin.paket-trip.jadwal.destroy', [$paketTrip, $jadwal])
                                    : route('admin.jadwal.destroy', $jadwal);
                            @endphp
                            <tr data-kuota-realtime-channel="jadwal.{{ $jadwal->jadwalId }}" data-jadwal-refresh-url="{{ route('jadwal.realtime', $jadwal) }}" data-jadwal-id="{{ $jadwal->jadwalId }}" data-jadwal-status="{{ $jadwal->status }}" data-jadwal-sisa-kuota="{{ max(0, $jadwal->kuota_max - $jadwal->kuota_terisi) }}">
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ $jadwals->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-4">
                                    <div class="font-black uppercase text-gray-900">{{ $jadwal->paketTrip?->nama }}</div>
                                    <div class="mt-1 text-xs text-gray-500">{{ $jadwal->paketTrip?->lokasi }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4 text-sm font-black text-gray-900">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_kembali)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    {{ $jadwal->cutoff_booking ? \Illuminate\Support\Carbon::parse($jadwal->cutoff_booking)->translatedFormat('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600" data-jadwal-field="kuota">{{ $jadwal->kuota_terisi }} / {{ $jadwal->kuota_max }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-lg {{ $jadwal->status === 'open' ? 'bg-green-100 text-green-700' : ($jadwal->status === 'full' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-600') }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em]" data-jadwal-field="status">
                                        {{ $jadwal->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ $editUrl }}" class="rounded-full border border-gray-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Edit</a>
                                        <form method="POST" action="{{ $destroyUrl }}" onsubmit="return confirm('Hapus jadwal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-red-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600 transition hover:border-red-300 hover:bg-red-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada jadwal yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 overflow-x-auto">
                {{ $jadwals->links() }}
            </div>
        </div>
    </div>
@endsection
