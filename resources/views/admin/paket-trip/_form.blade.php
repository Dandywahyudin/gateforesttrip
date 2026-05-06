@php
    $selectedKategori = old('kategori', $paketTrip->kategori_value ?? '');
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            <p class="font-black uppercase tracking-[0.25em]">Ada data yang belum lengkap</p>
            <p class="mt-1">Mohon lengkapi field yang ditandai merah sebelum menyimpan.</p>
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="nama">Nama Paket</label>
                <input id="nama" name="nama" type="text" value="{{ old('nama', $paketTrip->nama ?? '') }}" class="w-full rounded-lg border {{ $errors->has('nama') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3" required>
                @error('nama')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="lokasi">Lokasi</label>
                <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi', $paketTrip->lokasi ?? '') }}" class="w-full rounded-lg border {{ $errors->has('lokasi') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3" required>
                @error('lokasi')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="w-full rounded-lg border {{ $errors->has('kategori') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3">
                    <option value="">Pilih kategori</option>
                    @foreach(\App\Enums\PaketTripKategori::options() as $value => $label)
                        <option value="{{ $value }}" @selected($selectedKategori === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-gray-500">Gunakan kategori baku agar data katalog tetap konsisten.</p>
                @error('kategori')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="durasi_hari">Durasi Hari</label>
                <input id="durasi_hari" name="durasi_hari" type="number" min="1" value="{{ old('durasi_hari', $paketTrip->durasi_hari ?? 1) }}" class="w-full rounded-lg border {{ $errors->has('durasi_hari') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3" required>
                @error('durasi_hari')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="harga">Harga</label>
                <input id="harga" name="harga" type="number" min="1" value="{{ old('harga', $paketTrip->harga ?? '') }}" class="w-full rounded-lg border {{ $errors->has('harga') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3" required>
                @error('harga')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="meeting_point">Meeting Point</label>
                <textarea id="meeting_point" name="meeting_point" rows="3" class="w-full rounded-lg border {{ $errors->has('meeting_point') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3">{{ old('meeting_point', $paketTrip->meeting_point ?? '') }}</textarea>
                @error('meeting_point')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="6" class="w-full rounded-lg border {{ $errors->has('deskripsi') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3" required>{{ old('deskripsi', $paketTrip->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="fasilitas">Fasilitas</label>
                <textarea id="fasilitas" name="fasilitas" rows="5" placeholder="Contoh:&#10;- Transport&#10;- Makan 3x&#10;- Tiket masuk" class="w-full rounded-lg border {{ $errors->has('fasilitas') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3" required>{{ old('fasilitas', $paketTrip->fasilitas ?? '') }}</textarea>
                <p class="mt-2 text-xs text-gray-500">Tulis satu item per baris agar tampilan publik lebih rapi. Koma lama tetap terbaca.</p>
                @error('fasilitas')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="include">Include</label>
                    <textarea id="include" name="include" rows="5" placeholder="Contoh:&#10;- Dokumentasi&#10;- Air mineral" class="w-full rounded-lg border {{ $errors->has('include') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3">{{ old('include', $paketTrip->include ?? '') }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">Satu poin per baris supaya urutan tetap jelas dan mudah dibaca.</p>
                    @error('include')
                        <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="exclude">Exclude</label>
                    <textarea id="exclude" name="exclude" rows="5" placeholder="Contoh:&#10;- Pengeluaran pribadi&#10;- Tip driver" class="w-full rounded-lg border {{ $errors->has('exclude') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }} px-4 py-3">{{ old('exclude', $paketTrip->exclude ?? '') }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">Gunakan baris baru untuk memisahkan tiap pengecualian.</p>
                    @error('exclude')
                        <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="foto">Foto Utama</label>
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4">
                        <div class="overflow-hidden rounded-lg border border-dashed border-gray-200 bg-gray-50">
                            @if(!empty($paketTrip->foto))
                                <img src="{{ $paketTrip->foto_url }}" alt="Foto utama saat ini" class="h-40 w-full object-cover">
                            @else
                                <div class="flex h-40 items-center justify-center px-4 text-center text-xs font-semibold uppercase tracking-[0.25em] text-gray-400">Belum ada foto utama</div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="foto">Ganti foto utama</label>
                                <input id="foto" name="foto" type="file" accept="image/*" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary">
                            </div>

                            @if(!empty($paketTrip->foto))
                                <div class="flex flex-wrap items-center gap-3">
                                    <input type="checkbox" name="hapus_foto" value="1" id="hapus_foto" class="sr-only image-delete-toggle" data-target="foto-delete-state">
                                    <button type="button" class="inline-flex items-center justify-center rounded-full border border-red-200 px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600 transition hover:border-red-300 hover:bg-red-50" onclick="toggleImageDelete('hapus_foto')">Hapus gambar</button>
                                    <span id="foto-delete-state" class="hidden rounded-full bg-red-50 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600">Akan dihapus saat disimpan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Unggah file gambar baru untuk mengganti gambar lama, atau hapus jika ingin slot ini dikosongkan.</p>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="foto2">Foto 2</label>
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4">
                        <div class="overflow-hidden rounded-lg border border-dashed border-gray-200 bg-gray-50">
                            @if(!empty($paketTrip->foto2))
                                <img src="{{ $paketTrip->foto2_url }}" alt="Foto 2 saat ini" class="h-40 w-full object-cover">
                            @else
                                <div class="flex h-40 items-center justify-center px-4 text-center text-xs font-semibold uppercase tracking-[0.25em] text-gray-400">Belum ada foto 2</div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="foto2">Ganti foto 2</label>
                                <input id="foto2" name="foto2" type="file" accept="image/*" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary">
                            </div>

                            @if(!empty($paketTrip->foto2))
                                <div class="flex flex-wrap items-center gap-3">
                                    <input type="checkbox" name="hapus_foto2" value="1" id="hapus_foto2" class="sr-only image-delete-toggle" data-target="foto2-delete-state">
                                    <button type="button" class="inline-flex items-center justify-center rounded-full border border-red-200 px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600 transition hover:border-red-300 hover:bg-red-50" onclick="toggleImageDelete('hapus_foto2')">Hapus gambar</button>
                                    <span id="foto2-delete-state" class="hidden rounded-full bg-red-50 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600">Akan dihapus saat disimpan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="foto3">Foto 3</label>
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4">
                        <div class="overflow-hidden rounded-lg border border-dashed border-gray-200 bg-gray-50">
                            @if(!empty($paketTrip->foto3))
                                <img src="{{ $paketTrip->foto3_url }}" alt="Foto 3 saat ini" class="h-40 w-full object-cover">
                            @else
                                <div class="flex h-40 items-center justify-center px-4 text-center text-xs font-semibold uppercase tracking-[0.25em] text-gray-400">Belum ada foto 3</div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="foto3">Ganti foto 3</label>
                                <input id="foto3" name="foto3" type="file" accept="image/*" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary">
                            </div>

                            @if(!empty($paketTrip->foto3))
                                <div class="flex flex-wrap items-center gap-3">
                                    <input type="checkbox" name="hapus_foto3" value="1" id="hapus_foto3" class="sr-only image-delete-toggle" data-target="foto3-delete-state">
                                    <button type="button" class="inline-flex items-center justify-center rounded-full border border-red-200 px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600 transition hover:border-red-300 hover:bg-red-50" onclick="toggleImageDelete('hapus_foto3')">Hapus gambar</button>
                                    <span id="foto3-delete-state" class="hidden rounded-full bg-red-50 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600">Akan dihapus saat disimpan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-black uppercase tracking-[0.25em] text-gray-500" for="foto4">Foto 4</label>
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4">
                        <div class="overflow-hidden rounded-lg border border-dashed border-gray-200 bg-gray-50">
                            @if(!empty($paketTrip->foto4))
                                <img src="{{ $paketTrip->foto4_url }}" alt="Foto 4 saat ini" class="h-40 w-full object-cover">
                            @else
                                <div class="flex h-40 items-center justify-center px-4 text-center text-xs font-semibold uppercase tracking-[0.25em] text-gray-400">Belum ada foto 4</div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="mb-2 block text-[10px] font-black uppercase tracking-[0.25em] text-gray-500" for="foto4">Ganti foto 4</label>
                                <input id="foto4" name="foto4" type="file" accept="image/*" class="w-full rounded-lg border-gray-200 px-4 py-3 focus:border-primary focus:ring-primary">
                            </div>

                            @if(!empty($paketTrip->foto4))
                                <div class="flex flex-wrap items-center gap-3">
                                    <input type="checkbox" name="hapus_foto4" value="1" id="hapus_foto4" class="sr-only image-delete-toggle" data-target="foto4-delete-state">
                                    <button type="button" class="inline-flex items-center justify-center rounded-full border border-red-200 px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600 transition hover:border-red-300 hover:bg-red-50" onclick="toggleImageDelete('hapus_foto4')">Hapus gambar</button>
                                    <span id="foto4-delete-state" class="hidden rounded-full bg-red-50 px-3 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-red-600">Akan dihapus saat disimpan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <label class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white px-4 py-4">
                <input type="checkbox" name="aktif" value="1" {{ old('aktif', $paketTrip->aktif ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary">
                <span>
                    <span class="block text-sm font-black uppercase tracking-[0.25em] text-gray-700">Aktif</span>
                    <span class="block text-sm text-gray-500">Tampilkan paket trip ini di katalog publik.</span>
                </span>
            </label>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-black uppercase tracking-[0.25em]">Periksa kembali data form</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.paket-trip.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-gray-200 px-5 py-3 text-sm font-black uppercase tracking-[0.25em] text-gray-700 transition hover:border-primary hover:text-primary">Batal</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-black uppercase tracking-[0.25em] text-white transition hover:bg-primary-dark">{{ $submitLabel }}</button>
    </div>
</form>

@push('scripts')
<script>
    function toggleImageDelete(inputId) {
        const input = document.getElementById(inputId);
        const stateId = input?.dataset?.target;
        const state = stateId ? document.getElementById(stateId) : null;

        if (!input) {
            return;
        }

        input.checked = !input.checked;

        if (state) {
            state.classList.toggle('hidden', !input.checked);
        }
    }
</script>
@endpush