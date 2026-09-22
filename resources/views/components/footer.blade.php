@props(['minimal' => false])

@php
$locale = app()->getLocale();
$footerLinks = \App\Modules\Setting\Models\Setting::where('key', 'footer_links')->value('value') ?? [];
$aboutTextRaw = \App\Modules\Setting\Models\Setting::where('key', 'footer_about_text')->value('value');
$aboutText = is_array($aboutTextRaw) ? ($aboutTextRaw[$locale] ?? $aboutTextRaw['en'] ?? null) : $aboutTextRaw;
@endphp

<footer class="border-t border-ink/10 bg-surface py-10">
    <div class="mx-auto max-w-6xl px-4">
        <div class="flex flex-col gap-8 md:flex-row md:justify-between">
            <div class="max-w-sm">
                <p class="text-lg font-semibold text-ink">{{ config('app.name') }}</p>
                @if ($aboutText)<p class="mt-2 text-sm text-ink/60">{{ $aboutText }}</p>@endif
            </div>

            @unless ($minimal)
                <nav class="flex flex-wrap gap-4 text-sm text-ink/70">
                    @foreach ($footerLinks as $link)
                        <a href="{{ $link['url'] }}" wire:navigate class="hover:text-brand-primary">{{ $link['label'][$locale] ?? $link['label']['en'] ?? '' }}</a>
                    @endforeach
                </nav>

                <div>
                    <x-newsletter-form />
                </div>
            @endunless
        </div>

        <p class="mt-8 text-xs text-ink/40">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('common.all_rights_reserved') }}</p>
    </div>
</footer>
