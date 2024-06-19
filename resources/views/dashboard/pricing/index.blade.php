<x-app-layout>
    <x-slot name="header">
        @include('layouts.header')
    </x-slot>
    <div class="pt-lg-5 align-items-center justify-content-center text-center">
        <div class="col-lg-10 col-xl-7 z-2 w-100">
            <div class="position-relative">
                <div>
                    <h1 class="mb-0 display-4">
                        {{ __('pricing.pricing_plans' )}}
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <!--Pricing container-->
    <div class="container py-9 py-lg-11">
        <!-- Pricing row -->
        <div class="no-gutters row">
            <!-- Pricing col -->
            @foreach($plans as $plan)
                <div class="col-lg-4">
                    <!-- Pricing Card -->
                    <div class="card py-5 border-0 card-body">
                        <div class="rounded-top mb-4 position-relative overflow-hidden text-center">
                            <h5 class="title mb-4">{{ $plan->name }}</h5>
                            <div>
                                <h2 class="display-4 mb-4 lh-1"><sub class="small">$</sub>{{ $plan->price }}</h2>
                                <h6 class="mb-0 small text-body-secondary">{{ __('pricing.monthly' )}}</h6>
                            </div>
                            <!--/.price-->
                        </div>
                        <br>
                        <!--/.price-card-header-->
                        @php
                            $features = $plan->features;
                            $featureCount = count($features);
                            $maxFeatureCount = 3;
                            $emptyLineCount = $maxFeatureCount - $featureCount;
                        @endphp
                        <ul class="text-center list-unstyled mb-4">
                            @foreach ($features as $feature)
                                <li class="mb-3">{{ $feature->name }}</li>
                            @endforeach
                            @for ($i = 0; $i < $emptyLineCount; $i++)
                                <li class="mb-3">&nbsp;</li>
                            @endfor
                        </ul>
                        <div class="text-center">
                            <a href="{{ route('payments.index', ['plan_name' => $plan->name]) }}" class="btn btn-outline-primary hover-lift btn-material">{{ __('pricing.get' )}} {{ $plan->name }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @include('dashboard.forms.partials.modal')
</x-app-layout>
