<x-app-layout>
    <x-slot name="header">
        @include('layouts.header')
    </x-slot>

    <div class="toolbar px-3 px-lg-6 py-3">
        <div class="position-relative container-fluid px-0">
            <div class="row align-items-center position-relative">
                <div class="col-md-8 mb-4 mb-md-0">
                    <h1>{{__('forms.create.form')}}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="d-flex">
            <input type="text" id="form-name" class="form-control form-control-lg mb-2"
                placeholder="{{ __('forms.create.name') }}">
            <div style="width: 10px;"></div>
            <button class="btn btn-success btn-lg mb-2" id="save-button" type="button">
                <i class="fas fa-save"></i> {{ __('forms.save') }}
            </button>
        </div>
    </div>

    <div class="container">
        <div class="row mb-3" id="drag-zone">
            <div class="col-sm-6">
                <div class="card card-body border-0 bg-body-tertiary" >
                    <div class="row mb-3">
                        <div class="col-sm">
                            <div class="card card-body border-0 bg-body-tertiary">
                                <button type="button"
                                        class="btn btn-outline-info mb-2 me-1">{{ __('forms.elements') }} <i class="fa-brands fa-elementor"></i></button>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="card card-body border-0 bg-body-tertiary">
                                <button type="button"
                                        class="btn btn-outline-info mb-2 me-1">{{ __('forms.settings') }} <i class="fa-solid fa-gear"></i></button>
                            </div>
                        </div>
                        {{-- <div class="col-sm">
                            <div class="card card-body border-0 bg-body-tertiary">
                                <button type="button" class="btn btn-outline-info mb-2 me-1">{{ __('forms.input') }} <i class="fa-solid fa-keyboard"></i></button>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div id="modules">
                                <p class="drag"><a class="btn btn-primary">{{ __('checkbox') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('file') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('header') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('number') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('paragraph') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('radio.buttons') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('select') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('text.field') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('text.area') }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6" >
                <div class="card card-body border-0 bg-body-tertiary" id="dropzone">
                    <div class="row mb-3">
                        <div class="col-sm">
                            <div class="card card-body border-0 bg-body-tertiary">
                                <button type="button"
                                        class="btn btn-outline-info mb-2 me-1">{{ __('forms.your.form') }} <i class="fa-solid fa-arrow-down"></i></button>
                            </div>
                        </div>
                    </div>
                    <span class="sidebar-text text-truncate fs-3 fw-bold">
                        {{ __('general.brend_title') }} 
                    </span>
                </div>
            </div>
        </div>

        <div class="text-md-end">
            <button type="button" class="btn btn-secondary btn-lg rounded-end mb-2 me-1">
                {{ __('forms.copy') }} <i class="fa-regular fa-copy"></i></i>
            </button>
            <button type="button" class="btn btn-success btn-lg rounded-end mb-2 me-1">
                {{ __('forms.save') }} <i class="fa-regular fa-floppy-disk"></i>
            </button>
        </div>
</x-app-layout>

@vite(['resources/js/forms/edit.js'])

