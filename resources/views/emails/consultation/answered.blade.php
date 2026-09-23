<x-mail::message>
# {{ __('consultation.answered_mail_greeting') }}

{{ __('consultation.answered_mail_body') }}

**{{ __('consultation.topic') }}:** {{ $consultation->topic ?: __('consultation.mail_no_topic') }}

**{{ __('consultation.question') }}:**
{{ $consultation->question }}

**{{ __('consultation.answer') }}:**
{{ $consultation->answer }}

<x-mail::button :url="$signedUrl">
{{ __('consultation.mail_view_button') }}
</x-mail::button>

{{ __('consultation.answered_mail_footer') }}

{{ config('app.name') }}
</x-mail::message>
