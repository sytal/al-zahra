<x-mail::message>
# {{ __('consultation.mail_greeting') }}

{{ __('consultation.mail_body') }}

<x-mail::panel>
**{{ __('consultation.topic') }}:** {{ $consultation->topic ?: __('consultation.mail_no_topic') }}
</x-mail::panel>

<x-mail::button :url="$signedUrl">
{{ __('consultation.mail_view_button') }}
</x-mail::button>

{{ __('consultation.mail_footer') }}

{{ config('app.name') }}

<x-slot:subcopy>
{{ __('mail_ui.link_fallback') }} [{{ $signedUrl }}]({{ $signedUrl }})
</x-slot:subcopy>
</x-mail::message>
