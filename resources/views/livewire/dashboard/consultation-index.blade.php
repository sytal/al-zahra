@php
$statusColors = [
    'pending' => 'warning',
    'answered' => 'success',
    'scheduled' => 'brand',
    'completed' => 'success',
    'cancelled' => 'danger',
];
@endphp

<div class="mx-auto max-w-6xl px-4 py-8" data-aos="fade-up">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-ink">{{ __('dashboard.consultations_page_title') }}</h1>
        <x-button :href="route('consultation.show', app()->getLocale())" variant="primary" icon="plus">
            {{ __('dashboard.consultations_ask_new') }}
        </x-button>
    </div>

    <div class="mt-6">
        @if ($consultations->isEmpty())
            <x-empty-state icon="chat-bubble-left-right" :title="__('dashboard.no_consultations_yet')">
                <x-slot name="action">
                    <x-button :href="route('consultation.show', app()->getLocale())" variant="primary">
                        {{ __('dashboard.ask_question_cta') }}
                    </x-button>
                </x-slot>
            </x-empty-state>
        @else
            <x-table :headers="[__('dashboard.consultations_col_topic'), __('dashboard.consultations_col_type'), __('dashboard.consultations_col_status'), __('dashboard.consultations_col_date'), '']">
                @foreach ($consultations as $consultation)
                    <tr>
                        <td class="px-4 py-3 text-sm text-ink">{{ $consultation->topic ?: __('consultation.mail_no_topic') }}</td>
                        <td class="px-4 py-3 text-sm text-ink/70">
                            {{ $consultation->type->value === 'paid_booking' ? __('consultation.type_paid') : __('consultation.type_free') }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge :color="$statusColors[$consultation->status->value] ?? 'neutral'" :text="ucfirst($consultation->status->value)" />
                        </td>
                        <td class="px-4 py-3 text-sm text-ink/70">{{ $consultation->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-end">
                            <x-button wire:click="view('{{ $consultation->uuid }}')" variant="ghost" size="sm">
                                {{ __('dashboard.consultations_view') }}
                            </x-button>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </div>

    <x-modal name="consultation-detail" max-width="lg">
        @if ($activeConsultation)
            <h3 class="text-lg font-semibold text-ink">{{ $activeConsultation->topic ?: __('consultation.mail_no_topic') }}</h3>

            <div class="mt-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-ink/50">{{ __('dashboard.consultations_your_question') }}</p>
                <p class="mt-1 whitespace-pre-line text-sm text-ink">{{ $activeConsultation->question }}</p>
            </div>

            <div class="mt-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-ink/50">{{ __('dashboard.consultations_answer') }}</p>
                @if ($activeConsultation->answer)
                    <p class="mt-1 whitespace-pre-line text-sm text-ink">{{ $activeConsultation->answer }}</p>
                @else
                    <p class="mt-1 text-sm text-ink/60">{{ __('dashboard.consultations_not_answered_yet') }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <x-button x-on:click="show = false" variant="outline">{{ __('dashboard.close') }}</x-button>
            </div>
        @endif
    </x-modal>
</div>
