@php
$contactEmail = \App\Modules\Setting\Models\Setting::where('key', 'contact_email')->value('value');
$contactPhone = \App\Modules\Setting\Models\Setting::where('key', 'contact_phone')->value('value');
$socialLinks = \App\Modules\Setting\Models\Setting::where('key', 'social_links')->value('value') ?? [];
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

<div class="mx-auto max-w-6xl px-4 py-16" data-aos="fade-up">
    <header class="mb-10 text-center">
        <h1 class="text-3xl font-bold text-ink">{{ __('contact.page_title') }}</h1>
        <p class="mt-2 text-ink/60">{{ __('contact.page_intro') }}</p>
    </header>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <div class="md:py-4">
            <h2 class="text-xl font-semibold text-ink">{{ __('contact.info_title') }}</h2>

            <dl class="mt-6 space-y-4">
                @if ($contactEmail)
                    <div>
                        <dt class="sr-only">{{ __('contact.email') }}</dt>
                        <dd class="grid grid-cols-[24px_1fr] items-center gap-2 text-ink/70">
                            <x-icon name="envelope" class="size-5 text-brand-primary" />
                            <span class="font-medium">{{ $contactEmail }}</span>
                        </dd>
                    </div>
                @endif

                @if ($contactPhone)
                    <div>
                        <dt class="sr-only">{{ __('common.phone') }}</dt>
                        <dd class="grid grid-cols-[24px_1fr] items-center gap-2 text-ink/70">
                            <x-icon name="phone" class="size-5 text-brand-primary" />
                            <span class="font-medium">{{ $contactPhone }}</span>
                        </dd>
                    </div>
                @endif

                @foreach ($socialLinks as $platform => $url)
                    @if ($url)
                        <div>
                            <dt class="sr-only">{{ ucfirst($platform) }}</dt>
                            <dd class="grid grid-cols-[24px_1fr] items-center gap-2 text-ink/70">
                                <x-icon name="link" class="size-5 text-brand-primary" />
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="font-medium hover:text-brand-primary">{{ ucfirst($platform) }}</a>
                            </dd>
                        </div>
                    @endif
                @endforeach
            </dl>
        </div>

        <div>
            @if ($submitted)
                <x-card class="text-center">
                    <x-icon name="check-circle" class="mx-auto size-10 text-success" />
                    <h2 class="mt-3 text-xl font-semibold text-ink">{{ __('contact.success_title') }}</h2>
                    <p class="mt-1 text-ink/60">{{ __('contact.success_message') }}</p>
                </x-card>
            @else
                <form wire:submit="submit" class="space-y-4 rounded-lg border border-ink/10 bg-surface p-6">
                    <x-input name="name" :label="__('contact.name')" wire:model="name" />
                    <x-input name="email" type="email" :label="__('contact.email')" wire:model="email" />
                    <x-input name="subject" :label="__('contact.subject')" wire:model="subject" />
                    <x-textarea name="message" :label="__('contact.message')" wire:model="message" :rows="5" />

                    <x-button type="submit" variant="primary" class="w-full justify-center">
                        {{ __('contact.submit') }}
                    </x-button>
                </form>
            @endif
        </div>
    </div>
</div>
