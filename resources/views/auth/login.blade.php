<x-guest-layout :heading="'Welcome back'" :subheading="'Sign in to shop, track orders and manage your wishlist.'">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-6 text-sm text-center text-gray-500">
        {{ __('Not registered yet?') }}
        <a href="{{ route('register') }}" class="font-semibold text-red-600 hover:underline">{{ __('Create an account') }}</a>
    </p>

    <div class="mt-5 rounded-lg bg-gray-50 border border-gray-200 p-4 text-xs text-gray-600">
        <p class="font-bold uppercase tracking-wide text-gray-800 mb-2">Demo accounts</p>
        <p><span class="font-semibold">Admin:</span> admin@footlocker.test</p>
        <p><span class="font-semibold">Customer:</span> customer@footlocker.test</p>
        <p><span class="font-semibold">Password:</span> password</p>
    </div>
</x-guest-layout>