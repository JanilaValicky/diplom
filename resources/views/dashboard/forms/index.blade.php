<x-app-layout>
    <x-slot name="header">
        @include('layouts.header')
    </x-slot>

    <div class="toolbar px-3 px-lg-6 py-3">
        <div class="position-relative container-fluid px-0">
          <div class="row align-items-center position-relative">
            <div class="col-md-8 mb-4 mb-md-0">
              <h3 class="mb-2">{{ __('general.forms_title') }}</h3>
            </div>
            <div class="col-md-4 text-md-end">
              <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle-split rounded-end"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  {{ __('forms.export') }}<i class="fa-solid fa-cloud-arrow-down align-middle fs-5 ms-2"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                  <button class="dropdown-item export-button" data-export-type="excel"><i class="fa-solid fa-file-excel align-middle opacity-75 me-2"></i>Excel</button>
                  <button class="dropdown-item export-button" data-export-type="csv"><i class="fa-solid fa-file-csv align-middle opacity-75 me-2"></i>CSV</button>
                </div>
              </div>
                <x-responsive-nav-link :href="route('forms.create')">
                    <button type="button" class="btn btn-success rounded-end">
                        {{ __('forms.create') }} <i class="fa-solid fa-plus"></i>
                    </button>
                </x-responsive-nav-link>
            </div>
          </div>
        </div>
    </div>

    <div class="content pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-12 mb-3 mb-lg-5">
                    <x-text-input id="form-search" placeholder="Search..."/>

                    <div class="card">
                        <div class="table-responsive">
                            <table id="forms-table" class="table mt-0 table-striped table-card table-nowrap" style="width: 100%;">
                                <thead class="text-uppercase small text-body-secondary">
                                    <tr>
                                        <th>{{ __('forms.id') }}</th>
                                        <th>{{ __('forms.name') }}</th>
                                        <th>{{ __('forms.slug') }}</th>
                                        <th>{{ __('forms.start_date') }}</th>
                                        <th>{{ __('forms.end_date') }}</th>
                                        <th>{{ __('forms.status') }}</th>
                                        <th>{{ __('forms.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.forms.partials.modal')
</x-app-layout>

@vite(['resources/js/forms/index.js'])
