<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Akun Administrator') }}
        </h2>
        <p class="text-sm text-gray-600 mt-1">Daftarkan akun baru khusus untuk akses Admin.</p>
    </div>

    <!-- Error Custom -->
    @if($errors->any())
        <div class="mb-4 text-sm text-red-600 space-y-1">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.register.submit') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar Admin') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
