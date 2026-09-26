{{-- Props: citation (ready string) OR authors (array), year, title, source. Shows a document-style citation strip with a copy button. --}}
@props(['citation' => null, 'authors' => [], 'year' => null, 'title' => null, 'source' => null])

@php
if (!$citation) {
    $who = is_array($authors) ? implode(', ', $authors) : (string) $authors;
    $citation = trim(implode(' ', array_filter([
        $who !== '' ? rtrim($who, '.') . '.' : null,
        $year ? '(' . $year . ').' : null,
        $title ? rtrim($title, '.') . '.' : null,
        $source ? rtrim($source, '.') . '.' : null,
    ])));
}
@endphp

@if ($citation)
    <div {{ $attributes->merge(['class' => 'relative min-w-0 overflow-hidden rounded-card border border-dashed border-strong bg-surface-sunken']) }}
        x-data="copyToClipboard({ text: @js($citation), message: @js(__('kit_sections.citation_copied')) })">
        <div class="flex items-center justify-between gap-3 border-b border-dashed border-strong px-4 py-2">
            <p class="flex min-w-0 items-center gap-2 text-xs font-semibold text-muted"><x-icon name="bookmark" class="size-4 shrink-0 text-secondary-text" aria-hidden="true" /><span class="truncate">{{ __('kit_sections.cite_this') }}</span></p>
            <button type="button" class="focus-ring tap-target -me-2 gap-1.5 rounded-full px-3 text-xs font-semibold text-brand-primary transition duration-fast active:scale-[0.97] [@media(hover:hover)]:hover:bg-tint" x-on:click="copy">
                <x-icon name="clipboard-document" class="size-4" x-show="!copied" aria-hidden="true" />
                <x-icon name="check" class="size-4 text-success" x-show="copied" x-cloak aria-hidden="true" />
                <span x-show="!copied">{{ __('kit_sections.copy_citation') }}</span>
                <span x-show="copied" x-cloak>{{ __('kit_sections.copied') }}</span>
            </button>
        </div>
        <p class="break-words px-4 py-3 font-display text-sm leading-relaxed text-strong sm:text-base" dir="auto">{{ $citation }}</p>
        <span class="sr-only" role="status" aria-live="polite" x-text="copied ? @js(__('kit_sections.citation_copied')) : ''"></span>
    </div>
@endif
