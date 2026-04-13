@extends('layouts.app')

@section('title', 'Reservasi - ' . $paket->nama)

@section('content')
<div class="min-h-screen bg-background-light pt-24 pb-12">
    <div class="max-w-4xl mx-auto px-6 lg:px-16">
        <!-- Breadcrumb -->
        <div class="mb-8 text-sm text-forest-green/60">
            <a href="{{ route('paket-trip.index') }}" class="hover:text-primary transition">Katalog</a>
            <span class="mx-2">/</span>
            <a href="{{ route('paket-trip.show', $paket->paketId) }}" class="hover:text-primary transition">{{ $paket->nama }}</a>
            <span class="mx-2">/</span>
            <span class="text-forest-green">Reservasi</span>
        </div>

        <!-- Main Heading -->
        <h1 class="text-4xl font-display font-black text-forest-green uppercase mb-2">
            Reservasi {{ $paket->nama }}
        </h1>
        <p class="text-forest-green/70 mb-12">Silakan lengkapi data untuk menyelesaikan reservasi Anda</p>

        <!-- Form -->
        <form action="{{ route('reservasi.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Hidden Fields -->
            <input type="hidden" name="paketId" value="{{ $paket->paketId }}">

            <!-- Step 1: Pilih Jadwal -->
            <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200">
                <h2 class="text-2xl font-display font-black text-forest-green uppercase mb-6">Pilih Jadwal</h2>

                @if($jadwals->isEmpty())
                    <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-yellow-800">Tidak ada jadwal yang tersedia saat ini. Silakan kembali ke halaman detail untuk informasi lebih lanjut.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($jadwals as $jadwal)
                            @php
                                $kuota_tersisa = $jadwal->kuota_max - $jadwal->kuota_terisi;
                                $harga = $jadwal->harga_override ?? $paket->harga;
                            @endphp
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition"
                                   :class="{ 'border-primary bg-primary/5': selectedJadwal === '{{ $jadwal->jadwalId }}' }">
                                <input type="radio" name="jadwalId" value="{{ $jadwal->jadwalId }}" required
                                       class="w-4 h-4 text-primary mt-1"
                                       @change="selectedJadwal = '{{ $jadwal->jadwalId }}'">
                                <div class="ml-4 flex-1">
                                    <div class="font-black text-forest-green">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal_berangkat)->format('d M Y') }}
                                        @if($jadwal->tanggal_kembali)
                                            - {{ \Carbon\Carbon::parse($jadwal->tanggal_kembali)->format('d M Y') }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-forest-green/70">
                                        Kuota: {{ $kuota_tersisa }} dari {{ $jadwal->kuota_max }} tersedia
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-black text-lg text-primary">
                                        IDR {{ number_format($harga / 1000000, 1) }}M
                                    </div>
                                    <div class="text-xs text-forest-green/60">/orang</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('jadwalId')
                        <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- Step 2: Jumlah Peserta -->
            <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200">
                <h2 class="text-2xl font-display font-black text-forest-green uppercase mb-6">Jumlah Peserta</h2>

                <div class="flex items-center gap-4">
                    <label class="text-forest-green font-black">Peserta:</label>
                    <div class="flex items-center gap-3 border border-gray-300 rounded-lg p-3 w-32">
                        <button type="button" onclick="decreasePeserta()"
                                class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded transition">
                            −
                        </button>
                        <input type="number" id="jml_peserta" name="jml_peserta" value="{{ $jml_peserta }}" 
                               min="1" max="30" required
                               class="flex-1 text-center font-black text-forest-green bg-transparent border-0 focus:ring-0 outline-none">
                        <button type="button" onclick="increasePeserta()"
                                class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded transition">
                            +
                        </button>
                    </div>
                </div>

                @error('jml_peserta')
                    <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                @enderror
            </div>

            <!-- Step 3: Data Peserta -->
            <div id="pesertaContainer" class="space-y-6">
                <!-- Peserta akan di-generate oleh JavaScript -->
            </div>

            <!-- Step 4: Catatan -->
            <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200">
                <h2 class="text-lg font-display font-black text-forest-green uppercase mb-4">Catatan (Opsional)</h2>
                <textarea name="catatan" rows="4" placeholder="Tuliskan pertanyaan atau permintaan khusus Anda..."
                          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none">{{ old('catatan') }}</textarea>
            </div>

            <!-- Summary -->
            <div class="bg-primary/10 border-l-4 border-primary rounded-lg p-6">
                <p class="text-sm text-forest-green/60 mb-2">Estimasi Total Pembayaran:</p>
                <p class="text-4xl font-display font-black text-primary">
                    IDR <span id="totalPrice">0</span>M
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('paket-trip.show', $paket->paketId) }}"
                   class="flex-1 py-4 border-2 border-forest-green text-forest-green font-black uppercase tracking-widest rounded-lg hover:bg-forest-green hover:text-white transition-all text-center">
                    Kembali
                </a>
                <button type="submit"
                        class="flex-1 py-4 bg-primary hover:bg-orange-700 text-white font-black uppercase tracking-widest rounded-lg transition-all">
                    Lanjutkan ke Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Get user data from PHP
    const userData = {
        name: '{{ auth()->user()->name ?? "" }}',
        email: '{{ auth()->user()->email ?? "" }}'
    };

    const jmlPesertaInput = document.getElementById('jml_peserta');
    const pesertaContainer = document.getElementById('pesertaContainer');

    function renderPesertaForm() {
        const jml = parseInt(jmlPesertaInput.value) || 1;
        pesertaContainer.innerHTML = '';

        for (let i = 1; i <= jml; i++) {
            const isFirst = i === 1;
            const nama = isFirst ? userData.name : '';
            const email = isFirst ? userData.email : '';

            pesertaContainer.innerHTML += `
                <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-display font-black text-forest-green uppercase mb-6">Data Peserta ${i}</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-black text-forest-green mb-2">Nama Lengkap</label>
                            <input type="text" name="peserta[${i-1}][nama]" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   value="${nama}">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-black text-forest-green mb-2">Jenis Identitas</label>
                                <select name="peserta[${i-1}][jenis_identitas]" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="ktp">KTP</option>
                                    <option value="paspor">Paspor</option>
                                    <option value="sim">SIM</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-black text-forest-green mb-2">No. Identitas</label>
                                <input type="text" name="peserta[${i-1}][no_identitas]" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                       placeholder="Nomor KTP/Paspor/SIM">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-black text-forest-green mb-2">Jenis Kelamin</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="peserta[${i-1}][jenis_kelamin]" value="laki-laki" required
                                               class="w-4 h-4 text-primary">
                                        <span class="ml-2 text-sm text-forest-green">Laki-laki</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="peserta[${i-1}][jenis_kelamin]" value="perempuan" required
                                               class="w-4 h-4 text-primary">
                                        <span class="ml-2 text-sm text-forest-green">Perempuan</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-black text-forest-green mb-2">Tanggal Lahir</label>
                                <input type="date" name="peserta[${i-1}][tanggal_lahir]" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-forest-green mb-2">Email</label>
                            <input type="email" name="peserta[${i-1}][email]" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   value="${email}">
                        </div>

                        <div>
                            <label class="block text-sm font-black text-forest-green mb-2">No. HP</label>
                            <input type="tel" name="peserta[${i-1}][no_hp]" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
    }

    function updateTotal() {
        const jadwalId = document.querySelector('input[name="jadwalId"]:checked')?.value;
        const jml = parseInt(jmlPesertaInput.value) || 1;

        if (jadwalId) {
            const radio = document.querySelector(`input[value="${jadwalId}"]`);
            const priceText = radio.closest('label').querySelector('.text-primary').textContent;
            const price = parseFloat(priceText.match(/[\d.]+/)[0]);
            const total = (price * jml).toFixed(1);
            document.getElementById('totalPrice').textContent = total;
        }
    }

    function increasePeserta() {
        const current = parseInt(jmlPesertaInput.value) || 1;
        if (current < 30) {
            jmlPesertaInput.value = current + 1;
            renderPesertaForm();
            updateTotal();
        }
    }

    function decreasePeserta() {
        const current = parseInt(jmlPesertaInput.value) || 1;
        if (current > 1) {
            jmlPesertaInput.value = current - 1;
            renderPesertaForm();
            updateTotal();
        }
    }

    // Listen to changes
    jmlPesertaInput.addEventListener('change', () => {
        renderPesertaForm();
        updateTotal();
    });

    document.querySelectorAll('input[name="jadwalId"]').forEach(radio => {
        radio.addEventListener('change', updateTotal);
    });

    // Initial render
    renderPesertaForm();
    updateTotal();
</script>
@endsection
