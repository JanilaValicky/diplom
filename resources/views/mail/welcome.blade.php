<x-mail::message>
    {{ __('mail.welcome_message') }}
    <br/>
    {{ __('mail.welcome_end') }} {{ $userName }}
    <br/>
    {{ config('app.name') }}
</x-mail::message>
