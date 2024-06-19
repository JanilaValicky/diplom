<x-app-layout>
    <x-slot name="header">
        @include('layouts.header')
    </x-slot>

    <div class="toolbar px-3 px-lg-6 py-3">
        <div class="position-relative container-fluid px-0">
            <div class="row align-items-center position-relative">
                <div class="col-md-8">
                    <h2 class="mb-2 text-lg">Profile</h2>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="p-6">
            <div class="p-4 sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('dashboard.profile.partials.upload-avatar-form', ['user' => $user])
                </div>
            </div>

            <div class="p-4 sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('dashboard.profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('dashboard.profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('dashboard.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
