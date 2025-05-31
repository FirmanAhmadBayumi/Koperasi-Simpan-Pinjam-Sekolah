<section>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="mb-3">
            <x-input-label class="form-label" for="update_password_current_password" :value="__('Kata Sandi Sebelumnya')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="form-control" autocomplete="current-password" style="width: 600px;" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="text-danger mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label class="form-label" for="update_password_password" :value="__('Kata Sandi Baru')" />
            <x-text-input id="update_password_password" name="password" type="password" class="form-control"
                autocomplete="new-password" style="width: 600px;" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="text-danger mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label class="form-label" for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="form-control" autocomplete="new-password" style="width: 600px;" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="text-danger mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="btn btn-primary mt-2">{{ __('Simpan') }}</x-primary-button>
        </div>
    </form>
</section>