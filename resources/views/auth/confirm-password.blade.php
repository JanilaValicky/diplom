<x-guest-layout>
    <i class="fa-solid fa-user-lock avatar xl mx-auto" style="color: #00e078;"></i>

    <h4 class="mb-3 pt-4 text-center">{{ Auth::user()->name }}</h4>

    <div class="mb-4 text-center text-body-secondary">
        {{ __('confirm_password.pls_confirm_password') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="needs-validation" novalidate="">
        @csrf

        <div class="position-relative mb-3">
            <!-- Password -->
            <div>
                <x-text-input id="password" class="form-control-lg bg-transparent {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                type="password"
                                name="password"
                                required placeholder="{{ __('general.password')}}" />

                <x-primary-button class="p-0 d-flex align-items-center justify-content-center shadow-none border-0 position-absolute end-0 top-50 translate-middle-y me-1 h-75 width-40">
                    <i class="fa-solid fa-arrow-right" style="color: #ffffff;"></i>
                </x-primary-button>
                
                @if ($errors->has('password'))
                    <x-input-error :messages="$errors->get('password')" class="invalid-feedback"/>
                @else
                    <span class="invalid-feedback">Enter the valid password</span>
                @endif
            </div>
        </div>
    </form>
</x-guest-layout>
