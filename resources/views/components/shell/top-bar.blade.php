@php
use App\Modules\Setting\Models\Setting;
$phone = Setting::where('key', 'contact_phone')->value('value');
$email = Setting::where('key', 'contact_email')->value('value');
@endphp

<div class="bg-section-dark relative z-dropdown hidden text-sm print:hidden sm:block">
    <div class="mx-auto flex min-h-11 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <ul class="flex min-w-0 items-center gap-x-5">
            @if ($phone)
                <li class="min-w-0">
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="inline-flex min-h-11 items-center gap-2 text-body/90 transition hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-secondary">
                        <x-icon name="phone" class="size-4 shrink-0 text-secondary-text" />
                        <span class="sr-only">{{ __('common.phone') }}:</span>
                        <bdi dir="ltr" class="truncate">{{ $phone }}</bdi>
                    </a>
                </li>
            @endif
            @if ($email)
                <li class="min-w-0">
                    <a href="mailto:{{ $email }}" class="inline-flex min-h-11 items-center gap-2 text-body/90 transition hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-secondary">
                        <x-icon name="envelope" class="size-4 shrink-0 text-secondary-text" />
                        <span class="sr-only">{{ __('shell_public.email_label') }}:</span>
                        <span class="truncate">{{ $email }}</span>
                    </a>
                </li>
            @endif
            <li class="hidden items-center gap-2 text-body/80 xl:flex">
                <x-icon name="clock" class="size-4 shrink-0 text-secondary-text" />
                <span class="sr-only">{{ __('shell_public.hours_label') }}:</span>
                <span>{{ __('shell_public.hours') }}</span>
            </li>
        </ul>

        <div class="flex shrink-0 items-center gap-1">
            <x-language-switcher />
            <x-theme-toggle />
        </div>
    </div>
</div>
