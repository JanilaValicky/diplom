<x-guest-layout>
    <div class="text-center">
        <div class="size-80 mx-auto d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-circle-check fs-1" style="color: #00e078;"></i>
        </div>
        <div>
            <h2 class="mb-3">{{ __('verify_email.message_sent') }}</h2>
    <p class="mb-5 text-body-secondary">
        {{ __('verify_email.thanks_for_registration') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 text-body-secondary">
            {{ __('verify_email.new_verefication_link') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('verify_email.resend_verefication_email') }}
                </x-primary-button>
            </div>
        </form>

        <hr class="mt-4">
        <div class="d-flex align-items-center justify-end">   
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    <span>
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </span>
                                    <span>{{ __('general.log_out') }}</span>
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</x-guest-layout>
