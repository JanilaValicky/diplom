<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="z-1 position-relative needs-validation" novalidate="">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            @if ($errors->has('email'))
                <x-input-error :messages="$errors->get('email')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Enter the valid email</span>
            @endif
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" required autocomplete="new-password" />
            
            @if ($errors->has('password'))
                <x-input-error :messages="$errors->get('password')" class="invalid-feedback"/>
            @else
                <span class="invalid-feedback">Enter the valid password</span>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            @if ($errors->has('password_confirmation'))
                <x-input-error :messages="$errors->get('password_confirmation')" class="invalid-feedback" />
            @else
                <span class="invalid-feedback">Confirm password</span>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
