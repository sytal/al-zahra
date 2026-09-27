@php
$locale = app()->getLocale();
$statusColors = ['pending' => 'warning', 'answered' => 'success', 'scheduled' => 'brand', 'completed' => 'success', 'cancelled' => 'danger'];
$statusOrder = ['pending', 'answered', 'scheduled', 'completed', 'cancelled'];
$counts = ['all' => $consultations->count()];
foreach ($statusOrder as $s) {
    $counts[$s] = $consultations->filter(fn ($c) => $c->status->value === $s)->count();
}
$typeLabel = fn ($c) => $c->type->value === 'paid_booking' ? __('consultation.type_paid') : __('consultation.type_free');
$topicOf = fn ($c) => $c->topic ?: __('dash_home_ui.consultation_no_topic');
@endphp

<div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-6 sm:px-6 sm:py-8">
    <x-app.page-header :eyebrow="__('dashboard.nav_consultations')" :title="__('dashboard.consultations_page_title')" :description="__('dash_home_ui.consultations_lead')">
        <x-slot name="actions">
            <x-button :href="route('consultation.show', $locale)" variant="primary" icon="plus" magnetic>{{ __('dashboard.consultations_ask_new') }}</x-button>
        </x-slot>
    </x-app.page-header>

    @if ($consultations->isEmpty())
        <x-empty-state illustration="consultation" icon="chat-bubble-left-right" :title="__('dashboard.no_consultations_yet')" :message="__('dash_home_ui.empty_consultations_message')">
            <x-slot name="action">
                <x-button :href="route('consultation.show', $locale)" variant="primary">{{ __('dashboard.consultations_ask_new') }}</x-button>
            </x-slot>
        </x-empty-state>
    @else
        <div x-data="{ f: 'all', counts: @js($counts) }" class="flex min-w-0 flex-col gap-5">
            <div role="group" aria-label="{{ __('dash_home_ui.filter_label') }}" class="no-scrollbar -mx-4 flex gap-2 overflow-x-auto overscroll-x-contain px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0">
                <button type="button" x-on:click="f = 'all'" :aria-pressed="f === 'all'" class="inline-flex min-h-11 shrink-0 items-center gap-2 rounded-full border px-4 text-sm font-semibold outline-none transition duration-fast focus-visible:ring-4 focus-visible:ring-brand/30 active:scale-[0.98]" :class="f === 'all' ? 'border-transparent bg-brand text-on-brand shadow-soft' : 'border-strong bg-surface-raised text-strong [@media(hover:hover)]:hover:bg-tint'">
                    {{ __('dash_home_ui.filter_all') }}
                    <span class="rounded-full bg-black/10 px-2 py-0.5 text-xs tabular-nums" x-text="counts.all">{{ $counts['all'] }}</span>
                </button>
                @foreach ($statusOrder as $s)
                    @if ($counts[$s] > 0)
                        <button type="button" x-on:click="f = '{{ $s }}'" :aria-pressed="f === '{{ $s }}'" class="inline-flex min-h-11 shrink-0 items-center gap-2 rounded-full border px-4 text-sm font-semibold outline-none transition duration-fast focus-visible:ring-4 focus-visible:ring-brand/30 active:scale-[0.98]" :class="f === '{{ $s }}' ? 'border-transparent bg-brand text-on-brand shadow-soft' : 'border-strong bg-surface-raised text-strong [@media(hover:hover)]:hover:bg-tint'">
                            {{ __('enums.consultation_status.'.$s) }}
                            <span class="rounded-full bg-black/10 px-2 py-0.5 text-xs tabular-nums">{{ $counts[$s] }}</span>
                        </button>
                    @endif
                @endforeach
            </div>

            <x-table :headers="[__('dashboard.consultations_col_topic'), __('dashboard.consultations_col_type'), __('dashboard.consultations_col_status'), __('dashboard.consultations_col_date'), '']">
                @foreach ($consultations as $consultation)
                    <tr x-show="f === 'all' || f === '{{ $consultation->status->value }}'" wire:key="row-{{ $consultation->uuid }}">
                        <td class="min-w-0 max-w-xs break-words text-sm text-strong">{{ $topicOf($consultation) }}</td>
                        <td class="text-sm text-body">{{ $typeLabel($consultation) }}</td>
                        <td><x-badge dot :color="$statusColors[$consultation->status->value] ?? 'neutral'" :text="__('enums.consultation_status.'.$consultation->status->value)" /></td>
                        <td class="text-sm text-body">{{ $consultation->created_at->translatedFormat('M d, Y') }}</td>
                        <td class="text-end">
                            <x-button wire:click="view('{{ $consultation->uuid }}')" x-on:click="$dispatch('open-modal', 'consultation-detail')" variant="soft" size="sm" icon="chat-bubble-left-ellipsis">{{ __('dashboard.consultations_view') }}</x-button>
                        </td>
                    </tr>
                @endforeach
            </x-table>

            <div x-show="counts[f] === 0" x-cloak>
                <x-empty-state compact icon="funnel" :title="__('dash_home_ui.filter_empty_title')" :message="__('dash_home_ui.filter_empty_message')">
                    <x-slot name="action">
                        <x-button type="button" variant="outline" size="sm" x-on:click="f = 'all'">{{ __('dash_home_ui.filter_show_all') }}</x-button>
                    </x-slot>
                </x-empty-state>
            </div>
        </div>
    @endif

    <x-modal name="consultation-detail" max-width="xl" :title="$activeConsultation ? $topicOf($activeConsultation) : null">
        @if ($activeConsultation)
            @php
            $c = $activeConsultation;
            $st = $c->status->value;
            $steps = [['tl_submitted', $c->created_at, true]];
            if ($st === 'cancelled') {
                $steps[] = ['tl_cancelled', null, true];
            } else {
                $steps[] = [$c->answer ? 'tl_answered' : 'tl_pending', $c->answered_at, (bool) $c->answer];
                if ($c->scheduled_datetime || in_array($st, ['scheduled', 'completed'])) {
                    $steps[] = ['tl_scheduled', $c->scheduled_datetime, true];
                }
                if ($st === 'completed') {
                    $steps[] = ['tl_completed', null, true];
                }
            }
            @endphp
            <div class="space-y-6">
                <div class="flex flex-wrap items-center gap-2">
                    <x-badge dot :color="$statusColors[$st] ?? 'neutral'" :text="__('enums.consultation_status.'.$st)" />
                    <x-badge color="neutral" :text="$typeLabel($c)" />
                </div>

                <ol class="relative space-y-4 ps-7 before:absolute before:inset-y-2 before:start-[0.7rem] before:w-px before:bg-border-strong" aria-label="{{ __('dash_home_ui.timeline_heading') }}">
                    @foreach ($steps as [$key, $when, $done])
                        <li class="relative">
                            <span class="absolute -start-7 top-0.5 flex size-6 items-center justify-center rounded-full {{ $done ? 'bg-brand text-on-brand' : 'border-2 border-dashed border-strong bg-surface-raised text-muted' }}" aria-hidden="true">
                                @if ($done)<x-icon name="check" class="size-3.5" />@else<span class="star-mark text-xs"></span>@endif
                            </span>
                            <p class="text-sm font-semibold {{ $done ? 'text-strong' : 'text-body' }}">{{ __('dash_home_ui.'.$key) }}</p>
                            @if ($when)<p class="text-xs text-body">{{ $when->translatedFormat('M d, Y H:i') }}</p>@endif
                        </li>
                    @endforeach
                </ol>

                <div class="space-y-4">
                    <div class="flex flex-col items-start gap-1 sm:me-10">
                        <p class="text-xs font-semibold text-secondary-text">{{ __('dash_home_ui.thread_you') }}</p>
                        <div class="w-full rounded-2xl rounded-ss-md bg-tint p-4">
                            <p class="whitespace-pre-line break-words text-sm text-strong">{{ $c->question }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 sm:ms-10">
                        <p class="text-xs font-semibold text-secondary-text">{{ __('dash_home_ui.thread_institute') }}</p>
                        @if ($c->answer)
                            <div class="w-full rounded-2xl rounded-se-md border border-brand/30 bg-surface-sunken p-4">
                                <p class="whitespace-pre-line break-words text-sm text-strong">{{ $c->answer }}</p>
                            </div>
                        @else
                            <div class="w-full rounded-2xl rounded-se-md border border-dashed border-strong p-4">
                                <p class="text-sm text-body">{{ __('dash_home_ui.thread_waiting') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <x-slot name="footer">
            <x-button type="button" x-on:click="show = false" variant="outline">{{ __('dashboard.close') }}</x-button>
        </x-slot>
    </x-modal>
</div>
