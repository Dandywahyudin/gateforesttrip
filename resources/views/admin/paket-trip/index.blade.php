@extends('layouts.admin')

@section('title', 'Paket Trip')
@section('page-title', 'Paket Trip')

@section('content')
    <div class="space-y-6">
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="mt-2 text-3xl font-display font-black uppercase text-gray-900">Daftar paket trip</h3>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-gray-200 px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Dashboard</a>
                        <a href="{{ route('admin.paket-trip.create') }}" class="rounded-full bg-primary px-4 py-2 text-xs font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">Tambah Paket</a>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-[1100px] w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">No.</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Nama</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Slug</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Harga</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Jadwal Open</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Reservasi</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($paketTrips as $paket)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $paketTrips->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-4">
                                        <div class="font-black uppercase text-gray-900">{{ $paket->nama }}</div>
                                        <div class="mt-1 text-xs text-gray-500">{{ $paket->lokasi }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $paket->slug }}</td>
                                    <td class="px-4 py-4 text-sm font-black text-gray-900">Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-sm font-black text-gray-900">{{ $paket->jadwal_open_count }}</td>
                                    <td class="px-4 py-4 text-sm font-black text-gray-900">{{ $paket->reservasi_count }}</td>
                                    <td class="px-4 py-4">
                                        <span class="rounded-lg {{ $paket->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em]">
                                            {{ $paket->aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('paket-trip.show', $paket) }}" class="rounded-full border border-gray-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Preview</a>
                                            <a href="{{ route('admin.reservasi.index', ['paket' => $paket->slug]) }}" class="rounded-full border border-gray-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Reservasi</a>
                                            <a href="{{ route('admin.paket-trip.edit', $paket) }}" class="rounded-full border border-gray-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Edit</a>
                                            <a href="{{ route('admin.paket-trip.jadwal.index', $paket) }}" class="rounded-full border border-gray-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-gray-700 transition hover:border-primary hover:text-primary">Jadwal</a>
                                            <form method="POST" action="{{ route('admin.paket-trip.destroy', $paket) }}" onsubmit="return confirm('Hapus paket trip ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-full border border-red-200 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600 transition hover:border-red-300 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada data paket trip.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 overflow-x-auto">
                    {{ $paketTrips->links() }}
                </div>
            </div>
    </div>
@endsection