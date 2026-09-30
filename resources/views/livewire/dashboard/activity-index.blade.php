@php
$locale = app()->getLocale();

$iconFor = function ($subjectType) {
    $basename = $subjectType ? class_basename($subjectType) : null;

    return match ($basename) {
        'Article' => 'newspaper',
        'Course', 'Enrollment' => 'academic-cap',
        'Certificate' => 'document-check',
        'Consultation' => 'chat-bubble-left-right',
        'Setting', 'User' => 'cog',
        default => 'bell',
    };
};

$redactedKeys = ['password', 'token', 'secret'];

$isSensitiveKey = function (string $key) use ($redactedKeys) {
    foreach ($redactedKeys as $needle) {
        if (str_contains(mb_strtolower($key), $needle)) {
            return true;
        }
    }

    return false;
};

$changesFor = function ($activity) use ($isSensitiveKey) {
    $attributes = $activity->properties['attributes'] ?? null;
    $old = $activity->properties['old'] ?? null;

    if (!is_array($attributes) || $attributes === []) {
        return [];
    }

    $rows = [];
    foreach ($attributes as $key => $value) {
        if ($isSensitiveKey((string) $key)) {
            continue;
        }

        $rows[] = [
            'key' => $key,
            'from' => is_array($old) ? ($old[$key] ?? null) : null,
            'to' => $value,
        ];
    }

    return $rows;
};
@endphp

<div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-6 sm:px-6 sm:py-8">
    <x-app.page-header
        :eyebrow="__('dashboard.nav_dashboard')"
        :title="__('dash_activity_ui.page_title')"
        :description="$isStaff ? __('dash_activity_ui.description_staff') : __('dash_activity_ui.description_student')"
    />

    <div data-results>
        @if ($activities->isEmpty())
            <x-empty-state illustration="empty" icon="clock" :title="__('dash_activity_ui.empty_title')" :message="__('dash_activity_ui.empty_message')">
                <x-slot name="action">
                    <x-button :href="route('dashboard.courses.index', $locale)" variant="primary">{{ __('dash_activity_ui.empty_cta') }}</x-button>
                </x-slot>
            </x-empty-state>
        @else
            <ul class="flex flex-col gap-3" aria-label="{{ __('dash_activity_ui.list_label') }}">
                @foreach ($activities as $activity)
                    @php $changes = $changesFor($activity); @endphp
                    <li wire:key="activity-{{ $activity->id }}" class="card-surface flex min-w-0 gap-4 p-4 sm:p-5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary">
                            <x-icon :name="$iconFor($activity->subject_type)" class="size-5" aria-hidden="true" />
                        </span>

                        <div class="flex min-w-0 flex-1 flex-col gap-1">
                            <p class="min-w-0 break-words text-sm font-medium text-strong">
                                {{ $activity->description }}
                            </p>
                            <time datetime="{{ $activity->created_at?->toIso8601String() }}" class="text-xs text-muted">
                                {{ $activity->created_at?->translatedFormat('j M Y, g:i A') }}
                            </time>

                            @if (!empty($changes))
                                <details class="mt-1 [&_summary::-webkit-details-marker]:hidden">
                                    <summary class="w-fit cursor-pointer select-none text-xs font-semibold text-link outline-none focus-visible:ring-4 focus-visible:ring-brand/30 rounded">
                                        {{ __('dash_activity_ui.what_changed') }}
                                    </summary>
                                    <dl class="mt-2 flex flex-col gap-1.5 rounded-xl bg-surface-sunken p-3 text-xs">
                                        @foreach ($changes as $change)
                                            <div class="min-w-0">
                                                <dt class="font-semibold text-strong break-words">{{ $change['key'] }}</dt>
                                                <dd class="min-w-0 break-words text-muted">
                                                    @if ($change['from'] !== null)
                                                        <span class="line-through" dir="auto">{{ is_scalar($change['from']) ? $change['from'] : __('dash_activity_ui.value_complex') }}</span>
                                                        <span aria-hidden="true">&rarr;</span>
                                                    @endif
                                                    <span dir="auto">{{ is_scalar($change['to']) ? $change['to'] : __('dash_activity_ui.value_complex') }}</span>
                                                </dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                </details>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            <x-pagination :paginator="$activities" />
        @endif
    </div>
</div>
