@php
use App\Modules\Setting\Models\Setting;

$settingText = function (string $key) {
    $v = Setting::where('key', $key)->value('value');
    if (is_array($v)) {
        $v = implode(', ', array_filter($v, 'is_scalar'));
    }
    $v = is_scalar($v) ? trim((string) $v) : '';

    return $v !== '' ? $v : null;
};
$contactEmail = $settingText('contact_email');
$contactPhone = $settingText('contact_phone');
$hoursRaw = Setting::where('key', 'contact_hours')->value('value');
$contactHoursArr = is_array($hoursRaw) && count(array_filter($hoursRaw)) ? $hoursRaw : null;
$contactHours = is_string($hoursRaw) && trim($hoursRaw) !== '' ? trim($hoursRaw) : null;
$contactAddress = $settingText('contact_address');
$socialLinks = collect(Setting::where('key', 'social_links')->value('value') ?? [])->filter()->all();
@endphp

@push('head')
    <x-seo
        :title="$seo['title']"
        :description="$seo['description']"
        :image="$seo['image']"
        :type="$seo['type']"
        :schema="$seo['schema']"
        :title-tag="false"
    />
@endpush

<div>
    <section class="relative isolate overflow-hidden border-b bg-hero-gradient">
        <span class="glow-teal -top-32 end-[-6rem] opacity-80" style="--glow-size: 26rem" aria-hidden="true"></span>
        <span class="bg-pattern-neural pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>
        <div class="container-page py-10 sm:py-14 lg:py-16">
            <p class="eyebrow mb-3 inline-flex items-center gap-2"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('forms_ui.contact_eyebrow') }}</p>
            <h1 class="heading-1 max-w-3xl break-words">{{ __('contact.page_title') }}</h1>
            <p class="lead mt-4 max-w-2xl">{{ __('contact.page_intro') }}</p>
        </div>
        <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
    </section>

    <x-section variant="light" tight>
        <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,26rem)] lg:gap-10 xl:gap-14">
            <div class="min-w-0 lg:order-1">
                @if ($submitted)
                    <div class="card-surface relative overflow-hidden p-6 text-center sm:p-10" role="status">
                        <span class="glow-gold -top-24 start-1/2 -translate-x-1/2 opacity-60 rtl:translate-x-1/2" style="--glow-size: 20rem" aria-hidden="true"></span>
                        <div class="star-burst relative mx-auto w-fit" x-data="starBurst" x-init="$nextTick(() => fire())" :class="{ 'is-bursting': bursting }">
                            <img src="{{ asset('images/illustrations/illus-success.svg') }}" alt="" width="400" height="300" loading="lazy" decoding="async" class="relative mx-auto h-auto w-full max-w-64">
                        </div>
                        <h2 class="heading-2 mt-4 break-words">{{ __('contact.success_title') }}</h2>
                        <p class="mx-auto mt-3 max-w-md text-body">{{ __('contact.success_message') }}</p>
                        <x-star-divider class="my-6" />
                        <div class="flex flex-col justify-center gap-3 xs:flex-row">
                            <x-button variant="primary" wire:click="$set('submitted', false)">{{ __('forms_ui.send_another') }}</x-button>
                            <x-button variant="outline" :href="route('home', app()->getLocale())" wire:navigate>{{ __('forms_ui.back_home') }}</x-button>
                        </div>
                    </div>
                @else
                    <div class="card-surface relative min-w-0 overflow-hidden p-5 shadow-lift sm:p-8" x-data>
                        <span class="star-mark pointer-events-none absolute -end-5 -top-5 text-[7rem] opacity-15" aria-hidden="true"></span>
                        <h2 class="heading-3 relative break-words">{{ __('forms_ui.contact_form_title') }}</h2>
                        <p class="mt-1 text-body">{{ __('forms_ui.contact_form_sub') }}</p>

                        <form x-on:submit.prevent="$wire.submit().then(() => $nextTick(() => $el.querySelector('[aria-invalid=&quot;true&quot;]')?.focus()))" class="mt-6 space-y-5" novalidate>
                            <div class="grid gap-5 sm:grid-cols-2">
                                <x-input name="name" icon="user" :label="__('contact.name')" wire:model="name" autocomplete="name" maxlength="150" required />
                                <x-input name="email" icon="envelope" type="email" :label="__('contact.email')" wire:model="email" autocomplete="email" maxlength="255" required />
                            </div>
                            <x-input name="subject" icon="tag" :label="__('contact.subject')" wire:model="subject" maxlength="255" required />
                            <x-textarea name="message" :label="__('contact.message')" wire:model="message" :rows="6" maxlength="5000" :counter="5000" required />

                            <p class="flex items-start gap-2 text-sm text-body">
                                <x-icon name="lock-closed" class="mt-0.5 size-5 shrink-0 text-brand-primary" aria-hidden="true" />
                                <span class="min-w-0">{{ __('forms_ui.privacy_note') }}</span>
                            </p>

                            <x-button type="submit" variant="primary" size="lg" block magnetic icon="paper-airplane" wire:loading.attr="disabled" wire:target="submit">
                                <span wire:loading.remove wire:target="submit">{{ __('contact.submit') }}</span>
                                <span wire:loading wire:target="submit">{{ __('forms_ui.sending') }}</span>
                            </x-button>
                        </form>
                    </div>
                @endif
            </div>

            <aside class="min-w-0 space-y-4 lg:order-2" aria-label="{{ __('contact.info_title') }}">
                <h2 class="heading-3 break-words">{{ __('contact.info_title') }}</h2>
                @if ($contactEmail)
                    <x-contact-info-card type="email" :label="__('contact.email')" :value="$contactEmail" :note="__('forms_ui.email_note')" />
                @endif
                @if ($contactPhone)
                    <x-contact-info-card type="phone" :label="__('common.phone')" :value="$contactPhone" />
                @endif
                @if ($contactHoursArr)
                    <x-opening-hours :hours="$contactHoursArr" :title="__('forms_ui.hours_label')" />
                @elseif ($contactHours)
                    <x-contact-info-card type="other" icon="clock" :label="__('forms_ui.hours_label')" :value="$contactHours" />
                @endif
                @if ($contactAddress)
                    <x-contact-info-card type="address" :label="__('forms_ui.address_label')" :value="$contactAddress" />
                @endif
                @if (count($socialLinks))
                    <div class="card-surface p-5">
                        <p class="mb-3 font-semibold text-strong">{{ __('forms_ui.follow_us') }}</p>
                        <x-social-links :links="$socialLinks" />
                    </div>
                @endif
                @unless ($contactEmail || $contactPhone || $contactHours || $contactHoursArr || $contactAddress || count($socialLinks))
                    <p class="card-surface p-5 text-body">{{ __('forms_ui.contact_form_only') }}</p>
                @endunless
            </aside>
        </div>
    </x-section>
</div>
