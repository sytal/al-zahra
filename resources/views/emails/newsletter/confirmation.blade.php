<x-mail::message>
# {{ __('newsletter.mail_greeting') }}

{{ __('newsletter.mail_body') }}

<x-mail::button :url="$signedUrl">
{{ __('newsletter.mail_button') }}
</x-mail::button>

{{ __('newsletter.mail_footer') }}

{{ config('app.name') }}

<x-slot:subcopy>
{{ __('mail_ui.link_fallback') }} [{{ $signedUrl }}]({{ $signedUrl }})
</x-slot:subcopy>
</x-mail::message>
