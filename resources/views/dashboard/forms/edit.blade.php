<x-app-layout>
    <x-slot name="header">
        @include('layouts.header')
    </x-slot>

    <div class="toolbar px-3 px-lg-6 py-3">
        <div class="position-relative container-fluid px-0">
            <div class="row align-items-center position-relative">
                <div class="col-md-8 mb-4 mb-md-0">
                    <h1>{{ __('forms.edit.form') }}</h1>
                    <h2>{{ $form['name'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="text-md-end">
            <button type="button" class="btn btn-success btn-lg rounded-end mb-2 me-1">
                {{ __('forms.save') }} <i class="fa-regular fa-floppy-disk"></i>
            </button>
        </div>
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
                        <div class="col-sm">
                            <div class="card card-body border-0 bg-body-tertiary">
                                <button type="button" class="btn btn-outline-info mb-2 me-1">???</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="modules">
                                <p class="drag"><a class="btn btn-primary">{{ __('checkbox') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('file') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('header') }}</a></p>
                                <p class="drag"><a class="btn btn-primary">{{ __('hidden.input') }}</a></p>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite(['resources/js/forms/edit.js'])
