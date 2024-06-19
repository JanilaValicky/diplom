<x-guest-layout>
    <h4 class="text-center">{{ __('login.hello') }}</h4>
        <p class="mb-4 text-center text-body-secondary">
            {{ __('login.pls_register') }}
        </p>
    <form method="POST" action="{{ route('register') }}" class="z-1 position-relative needs-validation" novalidate="">
      <x-text-input id="x-token" class="d-none" value="{{csrf_token()}}"/>

        <!-- Name -->
        <div class="form-floating mb-3">
            <x-text-input id="name" class="block mt-1 w-full {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" :value="old('name')" required autofocus placeholder="name" />
            <x-input-label for="name" :value="__('name')" />
            @if ($errors->has('name'))
                <x-input-error :messages="$errors->get('name')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Enter the valid name</span>
            @endif
        </div>

        <!-- Email Address -->
        <div class="form-floating mb-3">
            <x-text-input id="email" class="block mt-1 w-full {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" :value="old('email')" required placeholder="email" />
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
                            required placeholder="new-password" />

            <x-input-label for="password" :value="__('general.password')" />

            @if ($errors->has('password'))
                <x-input-error :messages="$errors->get('password')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Enter the valid password</span>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="form-floating mb-3">
            <x-text-input id="password_confirmation" class="block mt-1 w-full {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                            type="password"
                            name="password_confirmation" required placeholder="new-password" />

            <x-input-label for="password_confirmation" :value="__('general.confirm_password')" />

            @if ($errors->has('password_confirmation'))
                <x-input-error :messages="$errors->get('password_confirmation')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Confirm password</span>
            @endif

        </div>

        <x-primary-button class="w-100 btn-lg" id="submit-button">
            {{ __('login.register_title') }}
        </x-primary-button>

        <hr class="mt-4">
        <p class="text-body-secondary text-center">
            {{ __('login.already_registered') }} <a href="{{ route('login') }}"
                class="ms-2">{{ __('login.login_title') }}</a>
        </p>
    </form>
</x-guest-layout>

@vite(['resources/js/register.js'])
