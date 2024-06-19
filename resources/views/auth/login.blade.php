<x-guest-layout>
    <h4 class="text-center">{{ __('login.welcome_back') }}</h4>
    <p class="mb-4 text-body-secondary text-center">
        {{ __('login.pls_login') }}
    </p>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" class="z-1 position-relative needs-validation" action="{{ route('login') }}" novalidate="">
        <x-text-input id="x-token" class="d-none" value="{{csrf_token()}}"/>

        <!-- Email Address -->
        <div class="form-floating mb-3">
            <x-text-input id="email" class="block mt-1 w-full {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
            <x-input-label for="email" :value="__('general.email')" />
            @if ($errors->has('email'))
                <x-input-error :messages="$errors->get('email')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Enter the valid email</span>
            @endif
        </div>

        <!-- Password -->
        <div class="form-floating mb-3">
            <x-text-input id="password" class="block mt-1 w-full {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            type="password"
                            name="password"
                            required placeholder="Password" />

            <x-input-label for="password" :value="__('general.password')" />
            @if ($errors->has('password'))
                <x-input-error :messages="$errors->get('password')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Enter the valid password</span>
            @endif
        </div>

        <!-- Remember Me & Forgot password -->

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check">
                <label for="remember_me" class="form-check-label">
                    <input id="remember_me" type="checkbox" class="form-check-input me-1" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('login.remember_me') }}</span>
                </label>
            </div>

            <div>
                @if (Route::has('password.request'))
                    <a class="small text-body-secondary" href="{{ route('password.request') }}">
                        {{ __('login.forgot_password') }}
                    </a>
                @endif
            </div>
        </div>

        <x-primary-button class="w-100 btn-lg" id="submit-button">
            {{ __('login.login_title') }}
        </x-primary-button>
        <hr class="mt-4 mb-3">
        <p class="text-body-secondary text-center">
            {{ __('login.dont_have_account') }} <a href="{{ route('register') }}"
                class="ms-2 text-body">{{ __('login.register_title') }}</a>
        </p>
    </form>
</x-guest-layout>

@vite(['resources/js/login.js'])
