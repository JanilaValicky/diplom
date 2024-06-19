<x-app-layout>
    <x-slot name="header">
            @include('layouts.header')
    </x-slot>

    <div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid position-relative">        
        {{ __("login.login_message") }}
    </div>
    <div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid position-relative">
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-12 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="pe-3 mb-0">
                            <span class="material-symbols-rounded me-2 align-middle text-primary">
                            workspace_premium
                            </span> {{ __('dashboard.stats') }}
                        </h5>
                    </div>
                    <div class="card-body py-0">
                    <div class="row mx-md-0">
                        <div class="col-md-7 col-xl-8 py-4">
                        <div class="card h-100 rounded-0 border-0 shadow-none">
                            
                        <div class="d-flex mb-4">
                            <div class="me-3 flex-grow-1 overflow-hidden">
                            <h3 class="mb-1 mt-3">{{ __('dashboard.find') }}</h3>
                            </div>
                        </div>

                        <!--Description text-->
                        <p class="mb-0">
                            {{ __('dashboard.gj') }}
                        </p>
                        </div>
                        </div>
                        <div class="col-md-5 col-xl-4 ps-md-6 border-start-md">

                        <div class="card border-0 shadow-none rounded-0 bg-transparent h-100">
                            <div class="row flex-column">
                            <div class="col-md-12 border-bottom py-md-5 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-shrink-0 size-50 bg-danger-subtle text-danger d-flex align-items-center justify-content-center rounded me-4">
                                    <span class="align-middle material-symbols-rounded fs-3">attach_money</span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="d-block mb-1 text-body-secondary">{{ __('dashboard.budget') }}</span>
                                    <h4 class="mb-0">9125</h4>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-12 border-bottom py-md-5 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-shrink-0 size-50 bg-primary-subtle text-primary d-flex align-items-center justify-content-center rounded me-4">
                                    <span class="align-middle material-symbols-rounded fs-3">schedule_send</span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="d-block mb-1 text-body-secondary">{{ __('dashboard.time') }}</span>
                                    <h4 class="mb-0">275</h4>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-12 py-3 py-md-5">
                                <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-shrink-0 size-50 bg-success-subtle text-success d-flex align-items-center justify-content-center rounded me-4">
                                    <span class="material-symbols-rounded fs-3">
                                    stacked_bar_chart
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="d-block mb-1 text-body-secondary">{{ __('dashboard.form') }}</span>
                                    <h4 class="mb-0">1</h4>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
