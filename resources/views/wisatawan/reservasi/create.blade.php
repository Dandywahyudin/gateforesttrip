@extends('layouts.app')

@section('title', 'Pilih Jadwal - ' . $paket->nama)

@section('content')
<section class="w-full pt-32 pb-16 bg-[radial-gradient(circle_at_top_right,_rgba(21,128,61,0.12),_transparent_30%),linear-gradient(to_bottom,_#f8faf7,_#ffffff)]">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-[1.25fr_0.75fr] gap-8 items-start">
            <div>
                <form action="{{ route('reservasi.jadwal.store') }}" method="POST" class="rounded-[28px] border border-white/60 bg-white/90 p-6 md:p-8 shadow-[0_22px_70px_rgba(15,23,42,0.08)] backdrop-blur-md space-y-8">
                    @csrf
                    <input type="hidden" name="paket_id" value="{{ $paket->paketId }}">

                    @error('jadwal_id')
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ $message }}</div>
                    @enderror
                    @error('jml_peserta')
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ $message }}</div>
                    @enderror
                    @error('catatan')
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ $message }}</div>
                    @enderror

                    <div class="space-y-2">
                        <x-breadcrumbs :items="[
                            ['label' => 'Home', 'url' => url('/')],
                            ['label' => 'Trip', 'url' => route('paket-trip.index')],
                            ['label' => $paket->nama, 'url' => route('paket-trip.show', $paket)],
                            ['label' => 'Pilih Jadwal', 'url' => null],
                        ]" />
                        <h3 class="text-3xl font-display font-black uppercase text-forest-green">Pilih tanggal keberangkatan</h3>
                        <p class="max-w-2xl text-sm leading-relaxed text-forest-green/70">Pilih Salah Satu Tanggal Yang Tersedia.</p>
                    </div>

                    <div class="space-y-3">
                        @forelse($jadwals as $jadwal)
                            @php
                                $hargaJadwal = $jadwal->harga_override ?? $paket->harga;
                                $sisaKuota = (int) ($jadwal->sisa_kuota_tersedia ?? max(0, $jadwal->kuota_max - $jadwal->kuota_terisi));
                                $isSelected = (string) old('jadwal_id', $sessionData['jadwal_id'] ?? '') === (string) $jadwal->jadwalId;
                            @endphp
                            <label class="group flex cursor-pointer flex-col gap-4 rounded-[24px] border bg-white p-5 transition-all duration-300 {{ $isSelected ? 'border-primary ring-4 ring-primary/10 shadow-[0_18px_50px_rgba(21,128,61,0.14)]' : 'border-forest-green/10 shadow-sm hover:border-primary/30 hover:shadow-[0_18px_50px_rgba(15,23,42,0.08)]' }}"
                                data-kuota-realtime-channel="jadwal.{{ $jadwal->jadwalId }}"
                                data-jadwal-refresh-url="{{ route('jadwal.realtime', $jadwal) }}"
                                data-jadwal-id="{{ $jadwal->jadwalId }}"
                                data-jadwal-status="{{ $jadwal->status }}"
                                data-jadwal-sisa-kuota="{{ $sisaKuota }}">
                                <div class="flex items-start gap-4">
                                    <input type="radio" name="jadwal_id" value="{{ $jadwal->jadwalId }}" class="mt-1 h-5 w-5 border-forest-green/20 text-primary focus:ring-primary" {{ $isSelected ? 'checked' : '' }} {{ $sisaKuota <= 0 ? 'disabled' : '' }} data-jadwal-field="radio">

                                    <div class="min-w-0 flex-1 space-y-4">
                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                <p class="text-[10px] font-black uppercase tracking-[0.28em] text-forest-green/40 mb-2">Jadwal</p>
                                                <h4 class="text-xl font-display font-black uppercase text-forest-green leading-tight" data-jadwal-field="tanggal-berangkat">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}</h4>
                                                <p class="mt-1 text-sm text-forest-green/60">Sampai <span data-jadwal-field="tanggal-kembali">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_kembali)->translatedFormat('d M Y') }}</span></p>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex rounded-full bg-background-light px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-forest-green" data-jadwal-field="harga">
                                                    Rp {{ number_format($hargaJadwal, 0, ',', '.') }}
                                                </span>
                                                <span class="inline-flex rounded-full bg-background-light px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-forest-green" data-jadwal-field="sisa-kuota">
                                                    {{ $sisaKuota }} dari {{ $jadwal->kuota_max }} kursi
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-2xl border border-dashed border-forest-green/10 px-4 py-3 text-xs font-black uppercase tracking-[0.22em] {{ $sisaKuota > 0 ? 'text-forest-green/60' : 'text-red-600' }}" data-jadwal-field="kuota-badge">
                                            <span>{{ $sisaKuota > 0 ? 'Tersedia' : 'Penuh' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-forest-green/20 bg-white p-6 text-sm text-forest-green/60">
                                Tidak ada jadwal open untuk paket ini.
                            </div>
                        @endforelse
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Jumlah Peserta</label>
                            <div class="flex items-stretch overflow-hidden rounded-2xl border border-forest-green/10 bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20">
                                <button type="button" id="decrement-peserta" class="w-14 shrink-0 border-r border-forest-green/10 text-2xl font-black text-forest-green transition hover:bg-background-light hover:text-primary" aria-label="Kurangi jumlah peserta">−</button>
                                <input id="jml_peserta" type="number" name="jml_peserta" min="1" max="20" value="{{ old('jml_peserta', $sessionData['jml_peserta'] ?? 1) }}" class="w-full border-0 bg-transparent px-4 py-3 text-center font-black text-forest-green focus:outline-none focus:ring-0" required>
                                <button type="button" id="increment-peserta" class="w-14 shrink-0 border-l border-forest-green/10 text-2xl font-black text-forest-green transition hover:bg-background-light hover:text-primary" aria-label="Tambah jumlah peserta">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('paket-trip.show', $paket) }}" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-forest-green/10 px-5 py-4 text-sm font-black uppercase tracking-[0.25em] text-forest-green transition hover:border-primary/40">Kembali ke Detail</a>
                        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-forest-green px-5 py-4 text-sm font-black uppercase tracking-[0.25em] text-white transition hover:-translate-y-0.5 hover:bg-forest-green/90 hover:shadow-lg hover:shadow-forest-green/20">Lanjut ke Data Peserta</button>
                    </div>
                </form>
            </div>

            <aside class="lg:sticky lg:top-28">
                <div class="rounded-[28px] border border-white/60 bg-white/90 p-6 shadow-[0_18px_60px_rgba(15,23,42,0.08)] backdrop-blur-md space-y-6">
                    <div class="space-y-4">
                        <span class="inline-flex rounded-full bg-primary/10 px-4 py-2 text-[10px] font-black uppercase tracking-[0.3em] text-primary">Ringkasan</span>
                        <div>
                            <h1 class="text-3xl md:text-4xl font-display font-black uppercase text-forest-green leading-tight">{{ $paket->nama }}</h1>
                            <p class="mt-3 text-sm leading-relaxed text-forest-green/70">Ringkasan paket berada di sisi kanan agar form tetap dominan di kiri.</p>
                        </div>
                    </div>

                    <div class="rounded-3xl overflow-hidden border border-forest-green/10 bg-gradient-to-br from-forest-green to-primary text-white">
                        <div class="aspect-[4/3] w-full">
                            @if($paket->foto_url)
                                <img src="{{ $paket->foto_url }}" alt="{{ $paket->nama }}" class="h-full w-full object-cover opacity-85">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-forest-green via-forest-green/90 to-primary px-6 text-center">
                                    <div class="space-y-3">
                                        <p class="text-[10px] font-black uppercase tracking-[0.35em] text-white/70">Foto belum diunggah</p>
                                        <h3 class="text-2xl font-display font-black uppercase leading-tight">{{ $paket->nama }}</h3>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 space-y-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-white/70">Preview Paket</p>
                            <h2 class="text-2xl font-display font-black uppercase leading-tight">{{ $paket->nama }}</h2>
                            <p class="text-sm text-white/80">{{ $paket->lokasi }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-3xl border border-forest-green/10 bg-background-light p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-1">Harga mulai</p>
                            <p class="text-base font-black text-forest-green leading-tight">Rp {{ number_format($paket->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-3xl border border-forest-green/10 bg-background-light p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-1">Jadwal</p>
                            <p class="text-base font-black text-forest-green leading-tight">{{ $jadwals->count() }} tersedia</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-forest-green/10 bg-white p-4 shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-forest-green/40 mb-2">Panduan</p>
                        <p class="text-sm leading-relaxed text-forest-green/70">Pilih salah satu jadwal, isi jumlah peserta, lalu lanjut ke data peserta. Ringkasan ini akan membantu sebelum masuk ke langkah berikutnya.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('jml_peserta');
        const decrementButton = document.getElementById('decrement-peserta');
        const incrementButton = document.getElementById('increment-peserta');

        if (!input || !decrementButton || !incrementButton) {
            return;
        }

        const min = Number(input.getAttribute('min') || 1);
        const max = Number(input.getAttribute('max') || 20);

        function setValue(nextValue) {
            const boundedValue = Math.min(max, Math.max(min, nextValue));
            input.value = boundedValue;
        }

        decrementButton.addEventListener('click', () => {
            setValue(Number(input.value || min) - 1);
        });

        incrementButton.addEventListener('click', () => {
            setValue(Number(input.value || min) + 1);
        });

        input.addEventListener('blur', () => {
            setValue(Number(input.value || min));
        });
    })();
</script>
@endpush
