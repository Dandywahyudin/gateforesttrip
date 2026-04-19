@extends('layouts.app')

@section('title', 'Isi Peserta - ' . $paket->nama)

@section('content')
<section class="w-full pt-32 pb-16 bg-white">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-16">
        <div class="mb-8">
            <x-breadcrumbs :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => 'Trip', 'url' => route('paket-trip.index')],
                ['label' => $paket->nama, 'url' => route('paket-trip.show', $paket)],
                ['label' => 'Pilih Jadwal', 'url' => route('reservasi.jadwal', $paket)],
                ['label' => 'Isi Data', 'url' => null],
            ]" />
            <h1 class="mt-2 text-4xl md:text-5xl font-display font-black uppercase text-forest-green">Isi Data Peserta</h1>
            <p class="mt-3 text-forest-green/70 max-w-2xl">Lengkapi data sesuai jumlah peserta yang sudah dipilih. Data ini akan dipakai untuk reservasi dan tiket.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1.25fr_0.75fr] gap-8 items-start">
            <div>
                <form action="{{ route('reservasi.peserta.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" id="jml_peserta" name="jml_peserta" value="{{ old('jml_peserta', $flow['jml_peserta'] ?? 1) }}">

                    @if ($errors->any())
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="rounded-2xl border border-forest-green/10 bg-white p-5 shadow-sm space-y-4">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.25em] text-primary">Jumlah Peserta</p>
                                <h3 class="mt-2 text-2xl font-display font-black uppercase text-forest-green">Atur peserta langsung di halaman ini</h3>
                                <p class="mt-2 text-sm text-forest-green/60">Tambahkan atau kurangi peserta tanpa kembali ke halaman sebelumnya.</p>
                            </div>
                            <div class="flex items-stretch overflow-hidden rounded-2xl border border-forest-green/10 bg-white lg:w-[240px]">
                                <button type="button" id="decrement-peserta" class="w-14 shrink-0 border-r border-forest-green/10 text-2xl font-black text-forest-green transition hover:bg-background-light hover:text-primary" aria-label="Kurangi jumlah peserta">−</button>
                                <input id="jml_peserta_display" type="number" min="1" max="20" value="{{ old('jml_peserta', $flow['jml_peserta'] ?? 1) }}" class="w-full border-0 bg-transparent px-4 py-3 text-center font-black text-forest-green focus:outline-none focus:ring-0" inputmode="numeric">
                                <button type="button" id="increment-peserta" class="w-14 shrink-0 border-l border-forest-green/10 text-2xl font-black text-forest-green transition hover:bg-background-light hover:text-primary" aria-label="Tambah jumlah peserta">+</button>
                            </div>
                        </div>
                    </div>

                    <div id="participant-list" class="space-y-4">
                        @for ($index = 0; $index < (int) old('jml_peserta', $flow['jml_peserta'] ?? 1); $index++)
                            <div class="participant-card rounded-2xl border border-forest-green/10 bg-white p-6 shadow-sm space-y-4" data-index="{{ $index }}">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.25em] text-primary">Peserta {{ $index + 1 }}</p>
                                        <h3 class="text-2xl font-display font-black uppercase text-forest-green">Data Peserta</h3>
                                    </div>

                                    @if(auth()->check())
                                        <label class="inline-flex items-center gap-3 rounded-full border border-forest-green/10 bg-background-light px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-forest-green">
                                            <input type="checkbox" class="use-profile rounded border-forest-green/20 text-primary focus:ring-primary" {{ $index === 0 ? '' : 'disabled' }}>
                                            <span>Gunakan data profil</span>
                                        </label>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Nama Lengkap</label>
                                        <input type="text" name="peserta[{{ $index }}][nama]" value="{{ old('peserta.' . $index . '.nama') }}" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Email</label>
                                        <input type="email" name="peserta[{{ $index }}][email]" value="{{ old('peserta.' . $index . '.email') }}" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required autocomplete="email">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Jenis Kelamin</label>
                                        <select name="peserta[{{ $index }}][jenis_kelamin]" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required>
                                            <option value="laki-laki" @selected(old('peserta.' . $index . '.jenis_kelamin') === 'laki-laki')>Laki-laki</option>
                                            <option value="perempuan" @selected(old('peserta.' . $index . '.jenis_kelamin') === 'perempuan')>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">No. HP</label>
                                        <input type="text" name="peserta[{{ $index }}][no_hp]" value="{{ old('peserta.' . $index . '.no_hp') }}" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Tanggal Lahir</label>
                                        <input type="date" name="peserta[{{ $index }}][tanggal_lahir]" value="{{ old('peserta.' . $index . '.tanggal_lahir') }}" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="flex flex-col md:flex-row gap-3">
                        <a href="{{ route('reservasi.jadwal', $paket) }}" class="flex-1 rounded-xl border border-forest-green/10 py-3 px-4 text-center text-sm font-black uppercase tracking-[0.25em] text-forest-green hover:border-primary/40 transition">Kembali ke Jadwal</a>
                        <button type="submit" class="flex-1 rounded-xl bg-forest-green py-3 px-4 text-white font-black uppercase tracking-[0.25em] text-sm hover:bg-forest-green/90 transition">Lanjut ke Ringkasan</button>
                    </div>
                </form>
            </div>

            <aside class="lg:sticky lg:top-28 bg-background-light rounded-2xl p-6 border border-forest-green/10">
                <h2 class="text-2xl font-display font-black uppercase text-forest-green">Ringkasan Singkat</h2>
                <dl class="mt-6 space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-forest-green/60">Paket</dt>
                        <dd class="font-black text-forest-green text-right">{{ $paket->nama }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-forest-green/60">Jadwal</dt>
                        <dd class="font-black text-forest-green text-right">{{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-forest-green/60">Jumlah peserta</dt>
                        <dd class="font-black text-forest-green text-right">{{ $flow['jml_peserta'] }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-forest-green/60">Total</dt>
                        <dd class="font-black text-forest-green text-right">Rp {{ number_format($flow['total_harga'], 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </div>
</section>
@endsection

@php
    $profileParticipantData = auth()->check() ? [
        'nama' => auth()->user()->nama,
        'email' => auth()->user()->email,
        'no_hp' => auth()->user()->no_hp,
        'jenis_kelamin' => auth()->user()->jenis_kelamin,
        'tanggal_lahir' => auth()->user()->tanggal_lahir,
    ] : null;
@endphp

<template id="participant-template">
    <div class="participant-card rounded-2xl border border-forest-green/10 bg-white p-6 shadow-sm space-y-4" data-index="__INDEX__">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.25em] text-primary">Peserta __NUMBER__</p>
                <h3 class="text-2xl font-display font-black uppercase text-forest-green">Data Peserta</h3>
            </div>

            <label class="inline-flex items-center gap-3 rounded-full border border-forest-green/10 bg-background-light px-4 py-2 text-xs font-black uppercase tracking-[0.22em] text-forest-green">
                <input type="checkbox" class="use-profile rounded border-forest-green/20 text-primary focus:ring-primary" __PROFILE_DISABLED__>
                <span>Gunakan data profil</span>
            </label>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Nama Lengkap</label>
                <input type="text" name="peserta[__INDEX__][nama]" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required>
            </div>
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Email</label>
                <input type="email" name="peserta[__INDEX__][email]" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required autocomplete="email">
            </div>
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Jenis Kelamin</label>
                <select name="peserta[__INDEX__][jenis_kelamin]" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required>
                    <option value="laki-laki">Laki-laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">No. HP</label>
                <input type="text" name="peserta[__INDEX__][no_hp]" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary">
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-forest-green/50">Tanggal Lahir</label>
                <input type="date" name="peserta[__INDEX__][tanggal_lahir]" class="participant-field w-full rounded-xl border-forest-green/10 focus:border-primary focus:ring-primary" required>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    (function () {
        const countInput = document.getElementById('jml_peserta_display');
        const hiddenCountInput = document.getElementById('jml_peserta');
        const list = document.getElementById('participant-list');
        const template = document.getElementById('participant-template');
        const decrementButton = document.getElementById('decrement-peserta');
        const incrementButton = document.getElementById('increment-peserta');

        if (!countInput || !hiddenCountInput || !list || !template || !decrementButton || !incrementButton) {
            return;
        }

        const min = 1;
        const max = 20;
        const profileData = @json($profileParticipantData);

        function clamp(value) {
            return Math.min(max, Math.max(min, value));
        }

        function participantCount() {
            return list.querySelectorAll('.participant-card').length;
        }

        function updateCountDisplay(value) {
            const nextValue = clamp(value);
            countInput.value = nextValue;
            hiddenCountInput.value = nextValue;
        }

        function fillProfile(card) {
            if (!profileData) {
                return;
            }

            const fields = {
                nama: profileData.nama || '',
                email: profileData.email || '',
                no_hp: profileData.no_hp || '',
                jenis_kelamin: profileData.jenis_kelamin || '',
                tanggal_lahir: profileData.tanggal_lahir ? profileData.tanggal_lahir.slice(0, 10) : '',
            };

            for (const [fieldName, fieldValue] of Object.entries(fields)) {
                const field = card.querySelector(`[name$="[${fieldName}]"]`);
                if (field) {
                    field.value = fieldValue;
                }
            }
        }

        function clearProfile(card) {
            ['nama', 'email', 'no_hp', 'tanggal_lahir'].forEach((fieldName) => {
                const field = card.querySelector(`[name$="[${fieldName}]"]`);
                if (field) {
                    field.value = '';
                }
            });

            const genderField = card.querySelector('[name$="[jenis_kelamin]"]');
            if (genderField) {
                genderField.value = '';
            }
        }

        function bindProfileToggle(card) {
            const toggle = card.querySelector('.use-profile');
            if (!toggle) {
                return;
            }

            toggle.addEventListener('change', () => {
                if (toggle.checked) {
                    fillProfile(card);
                } else {
                    clearProfile(card);
                }
            });
        }

        function createParticipantCard(index) {
            const html = template.innerHTML
                .replaceAll('__INDEX__', index)
                .replaceAll('__NUMBER__', index + 1)
                .replaceAll('__PROFILE_DISABLED__', index === 0 ? '' : 'disabled');

            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const card = wrapper.firstElementChild;
            bindProfileToggle(card);
            return card;
        }

        function syncCards() {
            const current = participantCount();
            const desired = clamp(Number(countInput.value || hiddenCountInput.value || min));

            if (desired > current) {
                for (let index = current; index < desired; index++) {
                    list.appendChild(createParticipantCard(index));
                }
            } else if (desired < current) {
                for (let index = current; index > desired; index--) {
                    const card = list.querySelector(`.participant-card[data-index="${index - 1}"]`);
                    if (card) {
                        card.remove();
                    }
                }
            }

            updateCountDisplay(desired);
        }

        decrementButton.addEventListener('click', () => {
            updateCountDisplay(Number(countInput.value || min) - 1);
            syncCards();
        });

        incrementButton.addEventListener('click', () => {
            updateCountDisplay(Number(countInput.value || min) + 1);
            syncCards();
        });

        countInput.addEventListener('change', syncCards);
        countInput.addEventListener('blur', syncCards);

        list.querySelectorAll('.participant-card').forEach(bindProfileToggle);

        if (hiddenCountInput.form) {
            hiddenCountInput.form.addEventListener('submit', () => {
                updateCountDisplay(participantCount());
            });
        }
    })();
</script>
@endpush
