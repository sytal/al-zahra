{{-- Props: hours = ['mon' => '09:00 - 17:00', 'tue' => null (closed), ...] (keys mon..sun) or [['label' => 'Mon - Fri', 'time' => '9 - 5'], ...], title, note. Today is highlighted when keys are weekdays. --}}
@props(['hours' => [], 'title' => null, 'note' => null])

@php
$order = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
$keyed = collect($hours)->keys()->every(fn ($k) => in_array($k, $order, true));
$todayKey = $order[now()->dayOfWeekIso - 1];
$rows = $keyed
    ? collect($order)->filter(fn ($d) => array_key_exists($d, $hours))->map(fn ($d) => ['key' => $d, 'label' => __('kit_sections.days.' . $d), 'time' => $hours[$d]])->values()
    : collect($hours)->map(fn ($r) => ['key' => null, 'label' => $r['label'] ?? '', 'time' => $r['time'] ?? null])->values();
@endphp

<div {{ $attributes->merge(['class' => 'card-surface relative min-w-0 overflow-hidden p-5 sm:p-6']) }}>
    <div class="mb-4 flex items-center gap-3">
        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary"><x-icon name="clock" class="size-6" aria-hidden="true" /></span>
        <h3 class="heading-4 min-w-0 break-words">{{ $title ?? __('kit_sections.opening_hours') }}</h3>
    </div>
    <dl class="divide-y divide-[var(--border-color)] text-sm">
        @foreach ($rows as $row)
            @php $isToday = $row['key'] && $row['key'] === $todayKey; @endphp
            <div class="flex items-center justify-between gap-4 py-2.5 {{ $isToday ? '-mx-2 rounded-lg bg-tint px-2 font-semibold' : '' }}">
                <dt class="flex min-w-0 items-center gap-2 text-strong">
                    @if ($isToday)<span class="star-mark text-xs" aria-hidden="true"></span>@endif
                    <span class="truncate">{{ $row['label'] }}</span>
                    @if ($isToday)<span class="sr-only">({{ __('kit_sections.today') }})</span>@endif
                </dt>
                <dd class="shrink-0 tabular-nums {{ $row['time'] ? 'text-body' : 'text-muted' }}" @if ($row['time']) dir="ltr" @endif>{{ $row['time'] ?: __('kit_sections.closed') }}</dd>
            </div>
        @endforeach
    </dl>
    @if ($note)<p class="mt-4 text-xs text-muted">{{ $note }}</p>@endif
</div>
