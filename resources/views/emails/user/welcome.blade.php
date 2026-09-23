<x-mail::message>
# {{ __('user.welcome_mail_greeting', ['name' => $user->name]) }}

{{ __('user.welcome_mail_body') }}

<x-mail::button :url="route('home', app()->getLocale())">
{{ __('user.welcome_mail_button') }}
</x-mail::button>

{{ __('user.welcome_mail_footer') }}

{{ config('app.name') }}
</x-mail::message>
