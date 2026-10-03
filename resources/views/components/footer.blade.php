@props(['minimal' => false])

@php
use App\Modules\Setting\Models\Setting;

$locale = app()->getLocale();
$footerLinks = Setting::where('key', 'footer_links')->value('value') ?? [];
$aboutTextRaw = Setting::where('key', 'footer_about_text')->value('value');
$aboutText = (is_array($aboutTextRaw) ? ($aboutTextRaw[$locale] ?? $aboutTextRaw['en'] ?? null) : $aboutTextRaw) ?: __('shell_public.footer_blurb');
$phone = Setting::where('key', 'contact_phone')->value('value');
$email = Setting::where('key', 'contact_email')->value('value');
$socialLinks = Setting::where('key', 'social_links')->value('value') ?? [];

$exploreLinks = [
    [route('about', $locale), __('nav.about')],
    [route('articles.index', $locale), __('nav.articles')],
    [route('courses.index', $locale), __('nav.courses')],
    [route('research.index', $locale), __('nav.research')],
    [route('resources.index', $locale), __('nav.resources')],
];

$instituteLinks = [
    [route('consultation.show', $locale), __('shell_public.book_consultation')],
    [route('contact.show', $locale), __('nav.contact')],
    [route('certificates.verify.form', $locale), __('shell_public.verify_certificate')],
];
foreach ($footerLinks as $link) {
    $label = $link['label'][$locale] ?? $link['label']['en'] ?? '';
    $url = $link['url'] ?? '';
    if ($label === '' || $url === '') {
        continue;
    }
    if (str_starts_with($url, '/') && ! preg_match('#^/(' . implode('|', config('app.locales')) . ')(/|$)#', $url)) {
        $url = url('/' . $locale . $url);
    }
    if (! collect($exploreLinks)->contains(fn ($l) => $l[0] === $url) && ! collect($instituteLinks)->contains(fn ($l) => $l[0] === $url)) {
        $instituteLinks[] = [$url, $label];
    }
}

$linkClass = 'link-underline inline-flex min-h-11 items-center text-body/85 transition-colors hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-secondary sm:min-h-9';
@endphp

<footer class="bg-section-dark relative isolate overflow-hidden text-body print:hidden" aria-label="{{ __('shell_public.footer_nav') }}">
    <div class="bg-pattern-islamic pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></div>
    <div class="glow-teal -z-10" style="inset-block-start: -8rem; inset-inline-start: -6rem;" aria-hidden="true"></div>
    <div class="glow-gold -z-10" style="inset-block-end: -10rem; inset-inline-end: -6rem;" aria-hidden="true"></div>
    <div class="gold-thread" aria-hidden="true"></div>

    <div class="mx-auto max-w-7xl px-4 pb-safe sm:px-6 lg:px-8">
        @unless ($minimal)
            <div class="relative -mt-px py-10 sm:py-14">
                <div class="glass overflow-hidden rounded-3xl border p-6 sm:p-8 lg:p-10">
                    <div class="grid items-center gap-6 md:grid-cols-2 md:gap-10">
                        <div class="min-w-0">
                            <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-secondary-text">
                                <span class="star-mark text-sm" aria-hidden="true"></span>
                                {{ __('common.stay_updated') }}
                            </p>
                            <p class="heading-3 mt-3 text-strong">{{ __('shell_public.newsletter_blurb') }}</p>
                        </div>
                        <div class="min-w-0">
                            <x-newsletter-form />
                        </div>
                    </div>
                </div>
            </div>
        @endunless

        <div class="grid gap-10 pb-10 pt-4 sm:grid-cols-2 md:grid-cols-12 md:gap-8 {{ $minimal ? 'pt-10' : '' }}">
            <div class="min-w-0 space-y-5 sm:col-span-2 md:col-span-4">
                <a href="{{ route('home', $locale) }}" wire:navigate class="inline-block rounded-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-secondary" aria-label="{{ config('app.name') }}">
                    <x-brand.logo style="direction:ltr" class="me-3 h-11 w-auto overflow-visible" />
                </a>
                <p class="max-w-md text-sm leading-relaxed text-body/85">{{ $aboutText }}</p>

                @unless ($minimal)
                    <ul class="space-y-1 text-sm">
                        @if ($phone)
                            <li><a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="inline-flex min-h-11 items-center gap-3 text-body/90 transition-colors hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-secondary"><x-icon name="phone" class="size-5 shrink-0 text-secondary-text" /><span class="sr-only">{{ __('common.phone') }}:</span><bdi dir="ltr">{{ $phone }}</bdi></a></li>
                        @endif
                        @if ($email)
                            <li class="min-w-0"><a href="mailto:{{ $email }}" class="inline-flex min-h-11 max-w-full items-center gap-3 text-body/90 transition-colors hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand-secondary"><x-icon name="envelope" class="size-5 shrink-0 text-secondary-text" /><span class="sr-only">{{ __('shell_public.email_label') }}:</span><span class="break-anywhere min-w-0">{{ $email }}</span></a></li>
                        @endif
                        <li class="flex min-h-11 items-center gap-3 text-body/85"><x-icon name="clock" class="size-5 shrink-0 text-secondary-text" /><span class="sr-only">{{ __('shell_public.hours_label') }}:</span>{{ __('shell_public.hours') }}</li>
                    </ul>

                    @if (collect($socialLinks)->filter()->isNotEmpty())
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-muted">{{ __('shell_public.follow_us') }}</p>
                            <ul class="flex flex-wrap gap-2" aria-label="{{ __('shell_public.social_nav') }}">
                                @foreach ($socialLinks as $platform => $url)
                                    @if ($url)
                                        @php($platformLabel = trans()->has('enums.social.'.$platform) ? __('enums.social.'.$platform) : $platform)
                                        <li><a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center gap-2 rounded-full border px-4 text-sm font-medium text-body/90 transition duration-200 hover:border-brand-secondary hover:text-strong focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-secondary active:scale-[0.97]"><x-icon name="link" class="size-4 text-secondary-text" />{{ $platformLabel }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endunless
            </div>

            @unless ($minimal)
                <nav class="min-w-0 md:col-span-2" aria-labelledby="footer-explore">
                    <h2 id="footer-explore" class="flex items-center gap-2 text-sm font-semibold text-strong"><span class="star-mark text-xs" aria-hidden="true"></span>{{ __('shell_public.col_explore') }}</h2>
                    <ul class="mt-3 space-y-0.5 text-sm">
                        @foreach ($exploreLinks as [$href, $label])
                            <li><a href="{{ $href }}" wire:navigate class="{{ $linkClass }}">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <nav class="min-w-0 md:col-span-3" aria-labelledby="footer-institute">
                    <h2 id="footer-institute" class="flex items-center gap-2 text-sm font-semibold text-strong"><span class="star-mark text-xs" aria-hidden="true"></span>{{ __('shell_public.col_institute') }}</h2>
                    <ul class="mt-3 space-y-0.5 text-sm">
                        @foreach ($instituteLinks as [$href, $label])
                            <li><a href="{{ $href }}" wire:navigate class="{{ $linkClass }}">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <nav class="min-w-0 md:col-span-3" aria-labelledby="footer-account">
                    <h2 id="footer-account" class="flex items-center gap-2 text-sm font-semibold text-strong"><span class="star-mark text-xs" aria-hidden="true"></span>{{ __('shell_public.col_account') }}</h2>
                    <ul class="mt-3 space-y-0.5 text-sm">
                        @guest
                            <li><a href="{{ route('login', $locale) }}" wire:navigate class="{{ $linkClass }}">{{ __('nav.login') }}</a></li>
                            <li><a href="{{ route('register', $locale) }}" wire:navigate class="{{ $linkClass }}">{{ __('nav.register') }}</a></li>
                        @else
                            <li><a href="{{ route('dashboard', $locale) }}" wire:navigate class="{{ $linkClass }}">{{ __('nav.dashboard') }}</a></li>
                            <li><a href="{{ route('dashboard.profile.edit', $locale) }}" wire:navigate class="{{ $linkClass }}">{{ __('shell_public.profile') }}</a></li>
                        @endguest
                    </ul>
                </nav>
            @endunless
        </div>

        <div class="star-divider" aria-hidden="true"><span class="star-mark"></span></div>

        <div class="flex flex-col gap-4 py-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-body/80">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('common.all_rights_reserved') }}</p>
            <x-language-switcher variant="list" />
        </div>
    </div>
</footer>
