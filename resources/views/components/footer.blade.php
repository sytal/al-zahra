@props(['minimal' => false])

@php
$locale = app()->getLocale();
$footerLinks = \App\Modules\Setting\Models\Setting::where('key', 'footer_links')->value('value') ?? [];
$aboutTextRaw = \App\Modules\Setting\Models\Setting::where('key', 'footer_about_text')->value('value');
$aboutText = is_array($aboutTextRaw) ? ($aboutTextRaw[$locale] ?? $aboutTextRaw['en'] ?? null) : $aboutTextRaw;
@endphp

<footer class="border-t border-ink/10 bg-white dark:bg-surface">
    <div class="mx-auto max-w-6xl space-y-8 px-4 py-12 sm:px-6 lg:space-y-16 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div>
                <p class="text-lg font-semibold text-ink">{{ config('app.name') }}</p>
                @if ($aboutText)<p class="mt-4 max-w-xs text-sm text-ink/60">{{ $aboutText }}</p>@endif
            </div>

            @unless ($minimal)
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <div>
                            <p class="font-medium text-ink">{{ __('common.explore') }}</p>
                            <ul class="mt-4 space-y-3 text-sm">
                                @foreach ($footerLinks as $link)
                                    <li>
                                        <a href="{{ $link['url'] }}" wire:navigate class="text-ink/70 transition duration-200 ease-in-out hover:text-brand-primary">
                                            {{ $link['label'][$locale] ?? $link['label']['en'] ?? '' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <p class="font-medium text-ink">{{ __('common.stay_updated') }}</p>
                            <div class="mt-4">
                                <x-newsletter-form />
                            </div>
                        </div>
                    </div>
                </div>
            @endunless
        </div>

        <p class="text-xs text-ink/40">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('common.all_rights_reserved') }}</p>
    </div>
</footer>
