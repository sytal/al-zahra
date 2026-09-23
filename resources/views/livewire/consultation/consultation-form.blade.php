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

<div class="mx-auto max-w-2xl px-4 py-10" data-aos="fade-up">
    <header class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-ink">{{ __('consultation.page_title') }}</h1>
        <p class="mt-2 text-ink/60">{{ __('consultation.page_intro') }}</p>
    </header>

    @if ($submitted)
        <x-card class="text-center">
            <x-icon name="check-circle" class="mx-auto size-10 text-success" />
            <h2 class="mt-3 text-xl font-semibold text-ink">{{ __('consultation.success_title') }}</h2>
            <p class="mt-1 text-ink/60">{{ __('consultation.success_message') }}</p>
        </x-card>
    @else
        <x-card>
            <div class="mb-6 flex gap-2">
                <button
                    type="button"
                    wire:click="$set('type', 'free_question')"
                    class="flex-1 rounded-lg border px-4 py-2 text-sm font-medium transition duration-200 ease-in-out {{ $type === 'free_question' ? 'border-brand-primary bg-brand-primary/10 text-brand-primary' : 'border-ink/20 text-ink/60' }}"
                >
                    {{ __('consultation.type_free') }}
                </button>
                <button
                    type="button"
                    wire:click="$set('type', 'paid_booking')"
                    class="flex-1 rounded-lg border px-4 py-2 text-sm font-medium transition duration-200 ease-in-out {{ $type === 'paid_booking' ? 'border-brand-primary bg-brand-primary/10 text-brand-primary' : 'border-ink/20 text-ink/60' }}"
                >
                    {{ __('consultation.type_paid') }}
                </button>
            </div>

            <form wire:submit="submit" class="space-y-4">
                @guest
                    <x-input name="guest_name" :label="__('consultation.name')" wire:model="guest_name" />
                    <x-input name="guest_email" type="email" :label="__('consultation.email')" wire:model="guest_email" />
                @endguest

                <x-input name="topic" :label="__('consultation.topic')" wire:model="topic" />
                <x-textarea name="question" :label="__('consultation.question')" wire:model="question" :rows="5" />

                @if ($type === 'paid_booking')
                    <x-input name="preferred_datetime" type="datetime-local" :label="__('consultation.preferred_datetime')" wire:model="preferred_datetime" />
                @endif

                <x-button type="submit" variant="primary" class="w-full justify-center">
                    {{ __('consultation.submit') }}
                </x-button>
            </form>
        </x-card>
    @endif
</div>
