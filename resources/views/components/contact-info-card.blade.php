{{-- Props: type email|phone|address|whatsapp|other, label, value, href (auto for email/phone/whatsapp), icon (auto by type), note. Long emails wrap. --}}
@props(['type' => 'other', 'label', 'value', 'href' => null, 'icon' => null, 'note' => null])

@php
$icon = $icon ?? ['email' => 'envelope', 'phone' => 'phone', 'address' => 'map-pin', 'whatsapp' => 'chat-bubble-left-ellipsis'][$type] ?? 'information-circle';
$href = $href ?? match ($type) {
    'email' => 'mailto:' . $value,
    'phone' => 'tel:' . preg_replace('/[^\d+]/', '', (string) $value),
    'whatsapp' => 'https://wa.me/' . preg_replace('/\D/', '', (string) $value),
    default => null,
};
$ltr = in_array($type, ['email', 'phone', 'whatsapp'], true);
@endphp

<div {{ $attributes->merge(['class' => 'card-surface card-hover group relative flex min-w-0 items-start gap-4 p-4 sm:p-5']) }}>
    <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-tint text-brand-primary transition-colors duration-base [@media(hover:hover)]:group-hover:bg-brand-primary [@media(hover:hover)]:group-hover:text-on-brand">
        <x-icon :name="$icon" class="size-6" aria-hidden="true" />
    </span>
    <div class="min-w-0 flex-1">
        <p class="text-xs font-semibold uppercase tracking-wide text-muted">{{ $label }}</p>
        @if ($href)
            <a href="{{ $href }}" @if ($type === 'whatsapp') target="_blank" rel="noopener noreferrer" @endif @if ($ltr) dir="ltr" @endif class="focus-ring link-underline mt-1 block break-anywhere text-base font-semibold text-strong after:absolute after:inset-0 rtl:text-end">{{ $value }}</a>
        @else
            <p class="mt-1 break-words text-base font-semibold text-strong">{{ $value }}</p>
        @endif
        @if ($note)<p class="mt-1 text-sm text-muted">{{ $note }}</p>@endif
    </div>
</div>
