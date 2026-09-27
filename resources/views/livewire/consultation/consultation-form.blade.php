@php
$isPaid = $type === 'paid_booking';
$howSteps = [
    ['icon' => 'pencil-square', 'title' => __('forms_ui.consult_how_1_title'), 'text' => __('forms_ui.consult_how_1_text')],
    ['icon' => 'envelope', 'title' => __('forms_ui.consult_how_2_title'), 'text' => __('forms_ui.consult_how_2_text')],
    ['icon' => 'chat-bubble-left-right', 'title' => __('forms_ui.consult_how_3_title'), 'text' => __('forms_ui.consult_how_3_text')],
];
@endphp

<div>
    <section class="relative isolate overflow-hidden border-b bg-hero-gradient">
        <span class="glow-teal -top-32 start-[-6rem] opacity-80" style="--glow-size: 26rem" aria-hidden="true"></span>
        <span class="glow-gold -bottom-40 end-[-6rem] opacity-50" style="--glow-size: 24rem" aria-hidden="true"></span>
        <span class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></span>
        <div class="container-page py-10 sm:py-14 lg:py-16">
            <p class="eyebrow mb-3 inline-flex items-center gap-2"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('forms_ui.consult_eyebrow') }}</p>
            <h1 class="heading-1 max-w-3xl break-words">{{ __('consultation.page_title') }}</h1>
            <p class="lead mt-4 max-w-2xl">{{ __('consultation.page_intro') }}</p>
        </div>
        <span class="gold-thread absolute inset-x-0 bottom-0" aria-hidden="true"></span>
    </section>

    <x-section variant="light" tight>
        <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,24rem)] lg:gap-10 xl:gap-14">
            <div class="min-w-0">
                @if ($submitted)
                    <div class="card-surface relative overflow-hidden p-6 text-center sm:p-10" role="status">
                        <span class="glow-gold -top-24 start-1/2 -translate-x-1/2 opacity-60 rtl:translate-x-1/2" style="--glow-size: 20rem" aria-hidden="true"></span>
                        <div class="star-burst relative mx-auto w-fit" x-data="starBurst" x-init="$nextTick(() => fire())" :class="{ 'is-bursting': bursting }">
                            <img src="{{ asset('images/illustrations/illus-success.svg') }}" alt="" width="400" height="300" loading="lazy" decoding="async" class="relative mx-auto h-auto w-full max-w-64">
                        </div>
                        <h2 class="heading-2 mt-4 break-words">{{ __('consultation.success_title') }}</h2>
                        <p class="mx-auto mt-3 max-w-md text-body">{{ __('consultation.success_message') }}</p>
                        <x-star-divider class="my-6" />
                        <div class="flex flex-col justify-center gap-3 xs:flex-row">
                            <x-button variant="primary" wire:click="$set('submitted', false)">{{ __('forms_ui.send_another') }}</x-button>
                            <x-button variant="outline" :href="route('home', app()->getLocale())" wire:navigate>{{ __('forms_ui.back_home') }}</x-button>
                        </div>
                    </div>
                @else
                    <div class="card-surface relative min-w-0 overflow-hidden p-5 shadow-lift sm:p-8" x-data>
                        <span class="star-mark pointer-events-none absolute -end-5 -top-5 text-[7rem] opacity-15" aria-hidden="true"></span>

                        <ol class="relative mb-6 grid grid-cols-3 gap-2 text-center text-xs sm:text-sm" aria-label="{{ __('kit_forms.steps') }}">
                            <li class="min-w-0">
                                <span class="mx-auto mb-1.5 flex size-8 items-center justify-center rounded-full bg-brand text-on-brand"><x-icon name="check" class="size-4" aria-hidden="true" /></span>
                                <span class="block break-words font-semibold text-strong">{{ __('forms_ui.step_type') }}</span>
                            </li>
                            <li class="min-w-0">
                                <span class="mx-auto mb-1.5 flex size-8 items-center justify-center rounded-full border-2 font-semibold transition-colors duration-base"
                                      :class="($wire.question || '').trim().length ? 'border-brand bg-brand text-on-brand' : 'border-strong text-muted'">
                                    <x-icon name="check" class="size-4" x-show="($wire.question || '').trim().length" aria-hidden="true" />
                                    <span x-show="!($wire.question || '').trim().length">2</span>
                                </span>
                                <span class="block break-words font-semibold text-strong">{{ __('forms_ui.step_details') }}</span>
                            </li>
                            <li class="min-w-0">
                                <span class="mx-auto mb-1.5 flex size-8 items-center justify-center rounded-full border-2 border-strong font-semibold text-muted">3</span>
                                <span class="block break-words font-semibold text-strong">{{ __('forms_ui.step_send') }}</span>
                            </li>
                        </ol>

                        <x-segmented-control
                            name="type"
                            :value="$type"
                            block
                            size="lg"
                            :options="['free_question' => __('consultation.type_free'), 'paid_booking' => __('consultation.type_paid')]"
                            wire:model.live="type"
                            :label="__('forms_ui.consult_type_label')"
                        />
                        <p class="mt-2 text-sm text-body" aria-live="polite">{{ $isPaid ? __('forms_ui.type_paid_hint') : __('forms_ui.type_free_hint') }}</p>

                        <form x-on:submit.prevent="$wire.submit().then(() => $nextTick(() => $el.querySelector('[aria-invalid=&quot;true&quot;]')?.focus()))" class="mt-6 space-y-5" novalidate>
                            @guest
                                <div class="grid gap-5 sm:grid-cols-2">
                                    <x-input name="guest_name" icon="user" :label="__('consultation.name')" wire:model="guest_name" autocomplete="name" maxlength="150" required />
                                    <x-input name="guest_email" icon="envelope" type="email" :label="__('consultation.email')" wire:model="guest_email" autocomplete="email" maxlength="255" required />
                                </div>
                            @endguest

                            <x-input name="topic" icon="tag" :label="__('consultation.topic')" :hint="__('forms_ui.topic_hint')" wire:model="topic" maxlength="255" optional />

                            <x-textarea name="question" :label="__('consultation.question')" :hint="__('forms_ui.question_hint')" wire:model="question" :rows="6" maxlength="5000" :counter="5000" required />

                            @if ($isPaid)
                                <div class="rounded-2xl border border-subtle bg-tint p-4 sm:p-5" x-transition>
                                    <x-input name="preferred_datetime" icon="calendar-days" type="datetime-local" :label="__('consultation.preferred_datetime')" :hint="__('forms_ui.datetime_hint')" wire:model="preferred_datetime" :min="now()->format('Y-m-d\TH:i')" required />
                                </div>
                            @endif

                            <p class="flex items-start gap-2 text-sm text-body">
                                <x-icon name="lock-closed" class="mt-0.5 size-5 shrink-0 text-brand-primary" aria-hidden="true" />
                                <span class="min-w-0">{{ __('forms_ui.privacy_note') }}</span>
                            </p>

                            <x-button type="submit" variant="primary" size="lg" block magnetic icon="paper-airplane" wire:loading.attr="disabled" wire:target="submit">
                                <span wire:loading.remove wire:target="submit">{{ __('consultation.submit') }}</span>
                                <span wire:loading wire:target="submit">{{ __('forms_ui.sending') }}</span>
                            </x-button>
                        </form>
                    </div>
                @endif
            </div>

            <aside class="min-w-0 space-y-6 lg:sticky lg:top-28" aria-label="{{ __('forms_ui.consult_how_title') }}">
                <div class="card-surface relative overflow-hidden p-5 sm:p-6">
                    <img src="{{ asset('images/illustrations/illus-consultation.svg') }}" alt="" width="400" height="300" loading="lazy" decoding="async" class="mx-auto mb-4 h-auto w-full max-w-56">
                    <h2 class="heading-3 break-words">{{ __('forms_ui.consult_how_title') }}</h2>
                    <x-steps :items="$howSteps" layout="vertical" class="mt-6" />
                </div>

                <div class="card-surface flex items-start gap-4 p-5">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary"><x-icon name="clock" class="size-6" aria-hidden="true" /></span>
                    <div class="min-w-0">
                        <h3 class="heading-4 break-words">{{ __('forms_ui.response_title') }}</h3>
                        <p class="mt-1 text-body">{{ __('forms_ui.response_text') }}</p>
                    </div>
                </div>

                <div class="gradient-border-gold rounded-2xl bg-surface-raised p-5">
                    <p class="flex items-center gap-2 font-semibold text-strong"><span class="star-mark text-sm" aria-hidden="true"></span>{{ __('forms_ui.reassure_title') }}</p>
                    <p class="mt-2 text-body">{{ __('forms_ui.reassure_text') }}</p>
                </div>
            </aside>
        </div>
    </x-section>
</div>
