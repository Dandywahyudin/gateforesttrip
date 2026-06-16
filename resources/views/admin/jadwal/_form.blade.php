@php
    $lockedPaketTrip = $lockedPaketTrip ?? false;
    $paketTrips = $paketTrips ?? collect();
    $selectedPaketId = old('paketId', $paketTrip?->paketId ?? $jadwal->paketId ?? '');
    $tanggalBerangkatValue = old('tanggal_berangkat', !empty($jadwal->tanggal_berangkat) ? \Illuminate\Support\Carbon::parse($jadwal->tanggal_berangkat)->format('Y-m-d') : '');
    $tanggalKembaliValue = old('tanggal_kembali', !empty($jadwal->tanggal_kembali) ? \Illuminate\Support\Carbon::parse($jadwal->tanggal_kembali)->format('Y-m-d') : '');
    $cutoffBookingValue = old('cutoff_booking', !empty($jadwal->cutoff_booking) ? \Illuminate\Support\Carbon::parse($jadwal->cutoff_booking)->format('Y-m-d\TH:i') : '');
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="paketId">Paket Trip</label>
        @if($lockedPaketTrip && $paketTrip)
            <input type="hidden" name="paketId" value="{{ $paketTrip->paketId }}">
            <input id="paketId" type="text" value="{{ $paketTrip->nama }}" class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-3 text-gray-700" disabled>
        @else
            <select id="paketId" name="paketId" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary" required>
                <option value="">Pilih Paket Trip</option>
                @foreach($paketTrips as $optionPaket)
                    <option value="{{ $optionPaket->paketId }}" @selected((string) $selectedPaketId === (string) $optionPaket->paketId)>{{ $optionPaket->nama }}</option>
                @endforeach
            </select>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="tanggal_berangkat">Tanggal Berangkat</label>
                <input id="tanggal_berangkat" name="tanggal_berangkat" type="date" value="{{ $tanggalBerangkatValue }}" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary" required>
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="tanggal_kembali">Tanggal Kembali</label>
                <input id="tanggal_kembali" name="tanggal_kembali" type="date" value="{{ $tanggalKembaliValue }}" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary" required>
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="kuota_max">Kuota Maksimal</label>
                <input id="kuota_max" name="kuota_max" type="number" min="1" value="{{ old('kuota_max', $jadwal->kuota_max ?? 1) }}" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary" required>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="status">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary" required>
                    <option value="open" @selected(old('status', $jadwal->status ?? 'open') === 'open')>Open</option>
                    <option value="full" @selected(old('status', $jadwal->status ?? '') === 'full')>Full</option>
                    <option value="cancelled" @selected(old('status', $jadwal->status ?? '') === 'cancelled')>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="cutoff_booking">Cutoff Booking</label>
                <input id="cutoff_booking" name="cutoff_booking" type="datetime-local" value="{{ $cutoffBookingValue }}" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary">
            </div>

            <div class="rounded-lg bg-gray-50 p-4 text-sm text-gray-600">
                <p class="font-black uppercase tracking-[0.25em] text-gray-400">Catatan</p>
                <p class="mt-2 leading-relaxed">Jika kuota terisi sudah sama atau melebihi kuota maksimal, status akan otomatis menjadi <span class="font-black text-gray-900">full</span>.</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        <a href="{{ $cancelUrl }}" class="inline-flex items-center justify-center rounded-2xl border border-gray-200 px-5 py-3 text-sm font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Batal</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">{{ $submitLabel }}</button>
    </div>
</form>
