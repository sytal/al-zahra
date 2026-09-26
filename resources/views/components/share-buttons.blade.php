{{-- Props: url, title, direction row|col (col for sticky side rails). Copy link + native share (only where supported) + four networks. --}}
@props(['url', 'title', 'direction' => 'row'])

@php
$networks = [
    'whatsapp' => ['https://wa.me/?text=' . urlencode($title . ' ' . $url), 'chat-bubble-left-ellipsis'],
    'linkedin' => ['https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url), 'briefcase'],
    'x' => ['https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title), 'x-mark'],
    'facebook' => ['https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url), 'user-group'],
];
$btn = 'focus-ring tap-target size-11 rounded-full border bg-surface-raised text-body transition duration-fast ease-enter active:scale-95 [@media(hover:hover)]:hover:-translate-y-0.5 [@media(hover:hover)]:hover:border-brand-primary [@media(hover:hover)]:hover:text-brand-primary [@media(hover:hover)]:hover:shadow-soft';
@endphp

<div {{ $attributes->merge(['class' => 'flex min-w-0 flex-wrap items-center gap-2 ' . ($direction === 'col' ? 'lg:flex-col' : '')]) }}
    x-data="{ canShare: typeof navigator !== 'undefined' && !!navigator.share, nativeShare() { navigator.share({ title: @js($title), url: @js($url) }).catch(() => {}); } }"
    role="group" aria-label="{{ __('kit_sections.share') }}">
    <span class="me-1 text-sm font-semibold text-strong">{{ __('kit_sections.share') }}</span>
    <span x-data="copyToClipboard({ text: @js($url), message: @js(__('kit_sections.link_copied')) })" class="inline-flex">
        <button type="button" class="{{ $btn }}" x-on:click="copy" title="{{ __('kit_sections.copy_link') }}" aria-label="{{ __('kit_sections.copy_link') }}">
            <x-icon name="link" class="size-5" x-show="!copied" aria-hidden="true" />
            <x-icon name="check" class="size-5 text-success" x-show="copied" x-cloak aria-hidden="true" />
        </button>
        <span class="sr-only" role="status" aria-live="polite" x-text="copied ? @js(__('kit_sections.link_copied')) : ''"></span>
    </span>
    <button type="button" class="{{ $btn }}" x-show="canShare" x-cloak x-on:click="nativeShare()" title="{{ __('kit_sections.share_device') }}" aria-label="{{ __('kit_sections.share_device') }}">
        <x-icon name="share" class="size-5" aria-hidden="true" />
    </button>
    @foreach ($networks as $name => [$shareUrl, $icon])
        <a href="{{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="{{ $btn }}"
            title="{{ __('kit_sections.share_on', ['network' => __('kit_sections.networks.' . $name)]) }}"
            aria-label="{{ __('kit_sections.share_on', ['network' => __('kit_sections.networks.' . $name)]) }}">
            <x-icon :name="$icon" class="size-5" aria-hidden="true" />
        </a>
    @endforeach
</div>
