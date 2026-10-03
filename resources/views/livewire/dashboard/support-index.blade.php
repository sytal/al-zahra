@php
$locale = app()->getLocale();
$statusColors = ['open' => 'warning', 'answered' => 'success', 'closed' => 'neutral'];
$statusOrder = ['open', 'answered', 'closed'];
$counts = ['all' => $tickets->count()];
foreach ($statusOrder as $s) {
    $counts[$s] = $tickets->filter(fn ($t) => $t->status === $s)->count();
}
@endphp

<div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-6 sm:px-6 sm:py-8">
    <x-app.page-header :eyebrow="__('dashboard.nav_support')" :title="__('dashboard.support_page_title')" :description="__('dashboard.support_lead')">
        <x-slot name="actions">
            <x-button x-on:click="$dispatch('open-modal', 'support-ticket-new')" variant="primary" icon="plus" magnetic>{{ __('dashboard.support_ask_new') }}</x-button>
        </x-slot>
    </x-app.page-header>

    @if ($tickets->isEmpty())
        <x-empty-state icon="lifebuoy" :title="__('dashboard.no_support_tickets_yet')" :message="__('dashboard.support_empty_message')">
            <x-slot name="action">
                <x-button x-on:click="$dispatch('open-modal', 'support-ticket-new')" variant="primary">{{ __('dashboard.support_ask_new') }}</x-button>
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
                            {{ __('enums.support_status.'.$s) }}
                            <span class="rounded-full bg-black/10 px-2 py-0.5 text-xs tabular-nums">{{ $counts[$s] }}</span>
                        </button>
                    @endif
                @endforeach
            </div>

            <x-table :headers="[__('dashboard.support_col_subject'), __('dashboard.support_col_status'), __('dashboard.support_col_date'), '']">
                @foreach ($tickets as $ticket)
                    <tr x-show="f === 'all' || f === '{{ $ticket->status }}'" wire:key="row-{{ $ticket->uuid }}">
                        <td class="min-w-0 max-w-xs break-words text-sm text-strong">{{ $ticket->subject }}</td>
                        <td><x-badge dot :color="$statusColors[$ticket->status] ?? 'neutral'" :text="__('enums.support_status.'.$ticket->status)" /></td>
                        <td class="text-sm text-body">{{ $ticket->created_at->translatedFormat('M d, Y') }}</td>
                        <td class="text-end">
                            <x-button wire:click="view('{{ $ticket->uuid }}')" x-on:click="$dispatch('open-modal', 'support-ticket-detail')" variant="soft" size="sm" icon="chat-bubble-left-ellipsis">{{ __('dashboard.support_view') }}</x-button>
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

    <x-modal name="support-ticket-new" max-width="lg" :title="__('dashboard.support_ask_new')">
        <form wire:submit="submit" class="space-y-4">
            <x-input name="subject" wire:model="subject" :label="__('dashboard.support_form_subject')" />
            <x-textarea name="body" wire:model="body" :label="__('dashboard.support_form_message')" rows="5" />
        </form>
        <x-slot name="footer">
            <x-button type="button" x-on:click="show = false" variant="outline">{{ __('dashboard.close') }}</x-button>
            <x-button type="submit" wire:submit="submit" wire:click="submit" variant="primary">{{ __('dashboard.support_send') }}</x-button>
        </x-slot>
    </x-modal>

    <x-modal name="support-ticket-detail" max-width="xl" :title="$activeTicket?->subject">
        @if ($activeTicket)
            @php $t = $activeTicket; @endphp
            <div class="space-y-6">
                <div class="flex flex-wrap items-center gap-2">
                    <x-badge dot :color="$statusColors[$t->status] ?? 'neutral'" :text="__('enums.support_status.'.$t->status)" />
                </div>

                <div class="space-y-4">
                    @foreach ($t->messages as $message)
                        <div class="flex flex-col {{ $message->author_id === $t->user_id ? 'items-start sm:me-10' : 'items-end sm:ms-10' }} gap-1">
                            <p class="text-xs font-semibold text-secondary-text">
                                {{ $message->author_id === $t->user_id ? __('dash_home_ui.thread_you') : __('dash_home_ui.thread_institute') }}
                            </p>
                            <div class="w-full rounded-2xl {{ $message->author_id === $t->user_id ? 'rounded-ss-md bg-tint' : 'rounded-se-md border border-brand/30 bg-surface-sunken' }} p-4">
                                <p class="whitespace-pre-line break-words text-sm text-strong">{{ $message->body }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($t->status !== 'closed')
                    <form wire:submit="reply" class="space-y-3">
                        <x-textarea name="reply" wire:model="reply" :label="__('dashboard.support_form_reply')" rows="4" />
                        <x-button type="submit" variant="primary" size="sm">{{ __('dashboard.support_send') }}</x-button>
                    </form>
                @endif
            </div>
        @endif
        <x-slot name="footer">
            <x-button type="button" x-on:click="show = false" variant="outline">{{ __('dashboard.close') }}</x-button>
        </x-slot>
    </x-modal>
</div>
