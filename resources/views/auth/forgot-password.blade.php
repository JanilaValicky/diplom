<x-guest-layout>
    <h4 class="mb-1">{{ __('login.password_recover') }}</h4>
    <p class="mb-4 text-body-secondary">
        {{ __('login.pls_email') }}
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="z-1 position-relative needs-validation" novalidate="">
        @csrf

        <!-- Email Address -->
        <div class="form-floating mb-3">
            <x-text-input id="email" class="block mt-1 w-full {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" :value="old('email')" required autofocus placeholder="email"/>
            <x-input-label for="email" :value="__('general.email')" />
            @if ($errors->has('email'))
                <x-input-error :messages="$errors->get('email')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Enter the valid email</span>
            @endif
        </div>

        <x-primary-button class="w-100 btn-lg">
            {{ __('Email Password Reset Link') }}
        </x-primary-button>
        
        <hr class="mt-4">
        <p class="text-body-secondary text-center">
            {{ __('login.remember_password') }} <a href="{{ route('login') }}"
                class="ms-2">{{ __('login.login_title') }}</a>
        </p>
    </form>
</x-guest-layout>
