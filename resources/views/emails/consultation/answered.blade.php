<x-mail::message>
# {{ __('consultation.answered_mail_greeting') }}

{{ __('consultation.answered_mail_body') }}

<x-mail::panel>
**{{ __('consultation.topic') }}:** {{ $consultation->topic ?: __('consultation.mail_no_topic') }}

**{{ __('consultation.question') }}:**
{{ $consultation->question }}
</x-mail::panel>

**{{ __('consultation.answer') }}:**

{{ $consultation->answer }}

<x-mail::button :url="$signedUrl">
{{ __('consultation.mail_view_button') }}
</x-mail::button>

{{ __('consultation.answered_mail_footer') }}

{{ config('app.name') }}

<x-slot:subcopy>
{{ __('mail_ui.link_fallback') }} [{{ $signedUrl }}]({{ $signedUrl }})
</x-slot:subcopy>
</x-mail::message>
