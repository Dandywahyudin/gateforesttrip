<section class="space-y-6">
    <header class="space-y-2">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="text-sm text-gray-600">
            {{ __('Perbarui data profil sesuai kebutuhan akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="nama" :value="__('Nama')" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
                <p class="mt-2 text-xs text-gray-500">Gunakan email aktif untuk notifikasi dan pemulihan akun.</p>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="mt-2 text-sm text-gray-800">
                            {{ __('Alamat email Anda belum diverifikasi.') }}

                            <button form="send-verification" class="rounded-md text-sm text-gray-600 underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:text-gray-900">
                                {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600">
                                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="no_hp" :value="__('No. HP')" />
                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp', $user->no_hp)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
            </div>

            <div>
                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('Pilih jenis kelamin') }}</option>
                    <option value="laki-laki" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'laki-laki')>{{ __('Laki-laki') }}</option>
                    <option value="perempuan" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'perempuan')>{{ __('Perempuan') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
            </div>
        </div>

        <div>
            <x-input-label for="alamat" :value="__('Alamat')" />
            <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat', $user->alamat) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full" :value="old('tanggal_lahir', $user->tanggal_lahir ? \Illuminate\Support\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '')" />
                <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
            </div>

            <div>
                <x-input-label for="foto_profil" :value="__('Foto Profil')" />
                <input id="foto_profil" name="foto_profil" type="file" class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />

                @if ($user->foto_profil)
                    <div class="mt-4 flex items-center gap-4 rounded-lg border border-gray-200 p-3">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->foto_profil) }}" alt="Foto profil" class="h-16 w-16 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('Foto profil saat ini') }}</p>
                            <p class="text-xs text-gray-500">{{ $user->foto_profil }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
