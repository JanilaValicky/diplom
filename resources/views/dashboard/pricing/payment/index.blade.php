<x-app-layout>
    <x-slot name="header">
        @include('layouts.header')
    </x-slot>
    @vite('resources/css/checkout.css')
    <div class="container py-9 py-lg-11">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card" id="payment-details">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('pricing.payment_details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <h6 class="mb-2">{{ __('pricing.plan') }}: {{ $plan->name }}</h6>
                            <h6 class="mb-2">{{ __('pricing.price') }}: {{ $plan->price }}$</h6>
                        </div>
                        <form id="payment-form" class="mt-3">
                            <div class="form-group">
                                <div id="payment-element" class="form-control"></div>
                            </div>
                            <button id="submit" class="btn btn-primary" data-plan-name="{{ $plan->name }}">
                              <div class="spinner hidden" id="spinner"></div>
                              <span id="button-text">{{ __('pricing.pay') }}</span>
                            </button>
                            <div id="payment-message" class="hidden"></div>
                          </form>
                    </div>
                </div>
                <div id="success-block" class="card" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('pricing.payment_status') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="size-80 mx-auto d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-circle-check fs-1" style="color: #00e078;"></i>
                        </div>
                        <h2 class="mb-3" id="success-message"></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const stripeKey = '{{ env('STRIPE_KEY') }}';
    </script>

    @vite('resources/js/stripe.js')
    @vite('resources/js/payment/index.js')
</x-app-layout>
