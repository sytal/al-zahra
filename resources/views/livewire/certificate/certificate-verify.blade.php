<x-layouts.minimal>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    <div class="mx-auto w-full max-w-2xl px-4 py-10" data-aos="fade-up">
        <header class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-ink">{{ __('certificates.page_title') }}</h1>
            <p class="mt-2 text-ink/60">{{ __('certificates.page_intro') }}</p>
        </header>

        <x-card>
            <form wire:submit="verify" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <x-input name="code" :label="__('certificates.code_label')" wire:model="code" placeholder="{{ __('certificates.code_placeholder') }}" />
                </div>
                <x-button type="submit" variant="primary" class="justify-center sm:mb-0">
                    {{ __('certificates.submit') }}
                </x-button>
            </form>
        </x-card>

        @if ($checked)
            @if ($certificate)
                <x-card class="mt-6 border-success/30 bg-success/5 text-center">
                    <x-icon name="shield-check" class="mx-auto size-12 text-success" />
                    <h2 class="mt-3 text-xl font-semibold text-ink">{{ __('certificates.valid_title') }}</h2>
                    <p class="mt-1 text-ink">
                        @php
                            $nameParts = preg_split('/\s+/', trim((string) $certificate->user?->name));
                            $displayName = $nameParts[0] ?? '';
                            if (count($nameParts) > 1) {
                                $displayName .= ' '.mb_substr($nameParts[count($nameParts) - 1], 0, 1).'.';
                            }
                        @endphp
                        {{ $displayName }}
                    </p>
                    <p class="mt-1 text-ink/70">{{ $certificate->course?->title }}</p>
                    <p class="mt-1 text-sm text-ink/50">
                        {{ __('certificates.issued_on') }} {{ $certificate->issued_at?->format('M d, Y') }}
                    </p>
                </x-card>
            @else
                <x-card class="mt-6 text-center">
                    <x-icon name="x-circle" class="mx-auto size-10 text-ink/40" />
                    <h2 class="mt-3 text-lg font-semibold text-ink">{{ __('certificates.invalid_title') }}</h2>
                    <p class="mt-1 text-ink/60">{{ __('certificates.invalid_message') }}</p>
                </x-card>
            @endif
        @endif
    </div>
</x-layouts.minimal>
