@component('mail::message')
    # Email Verification Reminder
    {{ __('mail.reminder_header') }} {{ $user->name }},
    {{ __('mail.verify_content') }}
@component('mail::button', ['url' => $verificationUrl])
    {{ __('mail.verify_button') }}
@endcomponent
    {{ __('mail.delete_content') }}
@component('mail::button', ['url' => $deleteAccountUrl])
    {{ __('mail.delete_button') }}
@endcomponent
@endcomponent
