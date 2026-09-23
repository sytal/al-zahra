<x-mail::message>
# {{ __('contact.mail_heading') }}

**{{ __('contact.name') }}:** {{ $contactMessage->name }}
**{{ __('contact.email') }}:** {{ $contactMessage->email }}
**{{ __('contact.subject') }}:** {{ $contactMessage->subject }}

{{ $contactMessage->message }}

{{ config('app.name') }}
</x-mail::message>
