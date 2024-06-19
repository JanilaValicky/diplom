<div class="mt-6">
    <div class="row align-items-center">
        <div class="col-md-auto col-12 mb-4 mb-md-0">
            <!-- Avatar -->
            <div class="position-relative">
                <div class="avatar size-140">
                    <img id="avatar" class="img-fluid rounded-pill" src="{{ $user->avatar_name }}" alt="...">
                </div>
            </div>
        </div>
        <div class="col-md col-12">
            <h6>{{ __('avatar.input_warning') }}</h6>
            <p class="text-body-secondary">{{ __('avatar.avatar_description') }}</p>
            <div class="d-flex align-items-center">
                <!-- Upload Button -->
                <div class="position-relative">
                    <input id="profile_picture" type="file" accept="image/*" class="d-none w-0 h-0 position-absolute">
                    <label for="profile_picture" data-tippy-placement="bottom" data-tippy-content="Update photo"
                           class="btn btn-primary me-3">
                        <i class="fas fa-image"></i> {{ __('avatar.upload_button') }}
                    </label>
                </div>

                <!-- Delete button -->
                <button type="button"
                        class="btn size-35 btn-outline-danger d-flex align-items-center justify-content-center p-0"
                        data-tippy-placement="bottom" data-tippy-content="Delete photo" id="delete-button">
                    <span class="material-symbols-rounded align-middle">{{ __('avatar.delete_button') }}</span>
                </button>
            </div>
        </div>
    </div>

    <x-input-error :messages="$errors->profilePicture->get('profile_picture')" class="mt-2"/>
</div>

@vite(['resources/js/avatar.js'])
