<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Portal Login') }}
        </h2>
        <p class="text-sm text-gray-600 mt-1">Gunakan kredensial administrator Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Error Custom -->
    @if($errors->any())
        <div class="mb-4 text-sm text-red-600 space-y-1">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Session Error (from middleware redirect) -->
    @if(session('error'))
        <div class="mb-4 p-3 rounded text-sm" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">
            <strong>⚠️</strong> {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('dashboard') }}">
                {{ __('Kembali') }}
            </a>

            <div>
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 me-3" href="{{ route('admin.register') }}">
                    {{ __('Buat Akun') }}
                </a>
                
                <x-primary-button>
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
