<x-mail::message>
# {{ __('contact.mail_heading') }}

<x-mail::panel>
**{{ __('contact.name') }}:** {{ $contactMessage->name }}<br>
**{{ __('contact.email') }}:** {{ $contactMessage->email }}<br>
**{{ __('contact.subject') }}:** {{ $contactMessage->subject }}
</x-mail::panel>

{{ $contactMessage->message }}

<x-mail::button :url="'mailto:'.$contactMessage->email">
{{ __('mail_ui.reply_button') }}
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
