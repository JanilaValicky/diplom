<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-error-message="{{ __('forms.delete_error') }}" data-error-title="{{ __('forms.error') }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">{{ __('forms.delete_form') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{ __('forms.form') }} <span class="element-name"></span>{{ __('forms.will_be_removed') }}
          <div>{{ __('forms.are_you_sure') }}</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark align-middle opacity-75 me-2"></i>{{ __('forms.cancel') }}</button>
          <button type="button" class="btn btn-danger" id="confirmDelete"><i class="fa-solid fa-trash-can align-middle opacity-75 me-2"></i>{{ __('forms.delete') }}</button>
        </div>
      </div>
    </div>
</div>
