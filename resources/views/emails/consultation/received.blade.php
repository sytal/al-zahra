<x-mail::message>
# {{ __('consultation.mail_greeting') }}

{{ __('consultation.mail_body') }}

**{{ __('consultation.topic') }}:** {{ $consultation->topic ?: __('consultation.mail_no_topic') }}

<x-mail::button :url="$signedUrl">
{{ __('consultation.mail_view_button') }}
</x-mail::button>

{{ __('consultation.mail_footer') }}

{{ config('app.name') }}
</x-mail::message>
