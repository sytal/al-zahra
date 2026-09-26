{{-- Props: items = [['q' => question, 'a' => answer], ...] (also accepts title/content keys), open (index open initially, null for none). Single-open accordion, keyboard accessible. --}}
@props(['items' => [], 'open' => null])

@php $uid = 'faq-' . \Illuminate\Support\Str::random(5); @endphp

<div {{ $attributes->merge(['class' => 'mx-auto flex min-w-0 max-w-3xl flex-col gap-3']) }} x-data="{ open: {{ $open === null ? 'null' : (int) $open }} }">
    @foreach ($items as $i => $item)
        @php
            $q = $item['q'] ?? $item['title'] ?? '';
            $a = $item['a'] ?? $item['content'] ?? '';
        @endphp
        <div class="card-surface overflow-hidden transition-shadow duration-base" x-bind:class="open === {{ $i }} && 'shadow-lift'">
            <h3>
                <button type="button" id="{{ $uid }}-h-{{ $i }}" aria-controls="{{ $uid }}-p-{{ $i }}" x-bind:aria-expanded="(open === {{ $i }}).toString()"
                    x-on:click="open = open === {{ $i }} ? null : {{ $i }}"
                    class="focus-ring flex min-h-14 w-full items-center justify-between gap-4 px-5 py-4 text-start text-base font-semibold text-strong">
                    <span class="min-w-0 break-words">{{ $q }}</span>
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-tint text-brand-primary transition-transform duration-base ease-enter" x-bind:class="open === {{ $i }} && 'rotate-45 bg-brand-secondary text-on-secondary'">
                        <x-icon name="plus" class="size-5" aria-hidden="true" />
                    </span>
                </button>
            </h3>
            <div id="{{ $uid }}-p-{{ $i }}" role="region" aria-labelledby="{{ $uid }}-h-{{ $i }}" x-show="open === {{ $i }}" x-collapse @if ($open !== $i) x-cloak @endif>
                <p class="whitespace-pre-line break-words px-5 pb-5 text-body">{{ $a }}</p>
            </div>
        </div>
    @endforeach
</div>
