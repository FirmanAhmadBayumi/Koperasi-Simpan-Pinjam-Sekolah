<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label class="form-label" for="nama" :value="__('Nama')" />
            <x-text-input id="nama" name="nama_display" type="text" class="form-control" value="{{ old('nama', $user->nama) }}"
                required autofocus autocomplete="nama" style="width: 600px;" disabled />
            <input type="hidden" name="nama" value="{{ old('nama', $user->nama) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div class="mb-3">
            <x-input-label class="form-label" for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" autofocus autocomplete="username" style="width: 600px;" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3">
            <x-input-label class="form-label" for="NIP" :value="__('NIP')" />
            <x-text-input id="NIP" name="nama_display" type="text" class="form-control" value="{{ old('NIP', $user->NIP) }}"
                required autofocus autocomplete="NIP" style="width: 600px;" disabled />
            <input type="hidden" name="NIP" value="{{ old('NIP', $user->NIP) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('NIP')" />
        </div>

        <div class="mb-3">
            <x-input-label class="form-label" for="jenis_kelamin" :value="__('Jenis Kelamin')" />
            <x-text-input id="jenis_kelamin" name="nama_display" type="text" class="form-control" value="{{ old('jenis_kelamin', $user->jenis_kelamin) }}"
                required autofocus autocomplete="jenis_kelamin" style="width: 600px;" disabled />
            <input type="hidden" name="jenis_kelamin" value="{{ old('jenis_kelamin', $user->jenis_kelamin) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
        </div>

        <div class="mb-3">
            <x-input-label class="form-label" for="alamat" :value="__('Alamat')" />
            <textarea id="alamat" name="alamat" class="form-control" autofocus autocomplete="alamat" style="width: 600px;" 
                aria-label="With textarea">{{ old('alamat', $user->alamat) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <div class="mb-3">
            <x-input-label class="form-label" for="no_tlp" :value="__('No. Telepon')" />
            <x-text-input id="no_tlp" name="no_tlp" type="text" class="form-control" :value="old('no_tlp', $user->no_tlp)"
                autofocus autocomplete="no_tlp" style="width: 600px;" />
            <x-input-error class="mt-2" :messages="$errors->get('no_tlp')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="btn btn-primary mt-2">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 mt-4">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>