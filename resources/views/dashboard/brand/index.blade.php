<x-app-layout>
    <x-slot name="header">
        @include('layouts.header')
    </x-slot>

    <div class="toolbar px-3 px-lg-6 py-3">
        <div class="position-relative container-fluid px-0">
            <div class="row align-items-center position-relative">
                <div class="col-md-8 mb-4 mb-md-0">
                    <h3 class="mb-2">{{ __('brand.brand') }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if(!is_null($brand))
        <div class="p-6">
            <h1><span>{{ $brand }}</span></h1>
        </div>
    @else
        <div class="p-6">
            <div class="alert alert-success" id="brand-success" role="alert" style="display:none">{{ __('brand.success') }}</div>
            <div class="alert alert-danger" id="brand-error" role="alert" style="display:none">{{ __('brand.already.exists') }}</div>
            <div class="d-flex">
                <input type="text" id="brand-name" class="form-control form-control-lg mb-2"
                   placeholder="{{ is_null($brand) ? __('brand.name') : $brand }}" {{ is_null($brand) ? '' : 'disabled' }}>
                <div style="width: 10px;"></div>
                <button class="btn btn-success btn-lg mb-2" id="save-button" type="button" {{ is_null($brand) ? '' : 'disabled' }}>
                    <i class="fas fa-save"></i> {{ __('brand.save') }}
                </button>
            </div>
            <div id="name-exists" class="invalid-feedback" style="display:none">{{ __('brand.name.exists') }}</div>
            <div id="invalid-name" class="invalid-feedback" style="display:none">{{ __('brand.invalid.name') }}</div>
            <br/>
            <section class="position-relative overflow-hidden">
                <div class="container position-relative py-8">
                    <div class="row pt-4 pt-lg-6 align-items-start justify-content-center">
                        <div class="col-lg-6 col-md-9 mt-lg-6 text-center text-lg-start">
                            <h1 class="mb-4 display-3 me-lg-n4 position-relative z-index-1">{{ __('brand.create') }}</h1>
                            <p class="mb-4 mb-lg-6 lead opacity-75 pe-lg-6">{{ __('brand.build') }}</p>
                        </div>
                        <div class="col-lg-6 col-md-9 ms-lg-auto">
                            <div class="position-relative ps-lg-9 pb-lg-5">
                                <div class="card p-1 hover-lift">
                                    <div class="position-relative">
                                        <img src="{{ Vite::asset('resources/assets/images/brend.jpg') }}" class="img-fluid card-img"
                                            alt="test image" />
                                        <div
                                            class="position-absolute start-0 bottom-0 px-3 rounded py-1 mb-3 ms-3 bg-dark bg-opacity-25 text-white countdown">
                                            <span class="d-block small">{{ __('brand.click') }}</span>
                                            <div class="d-flex align-items-center" data-countdown="2023/03/01 11:59:59">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body px-4">
                                        <h5 class="mb-0 text-truncate">
                                            {{ __('brand.buy') }}
                                        </h5>
                                    </div>
                                    <a href="{{ route('payments.index', ['plan_name' => 'Pro']) }}" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif


</x-app-layout>

@vite(['resources/js/brand/index.js'])
