@props(['title', 'meta' => null, 'share' => null, 'related' => null])

<article {{ $attributes->merge(['class' => 'mx-auto max-w-3xl px-4 py-10']) }} data-aos="fade-up">
    <h1 class="text-3xl font-bold text-ink md:text-4xl">{{ $title }}</h1>

    @isset($meta)
        <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-ink/60">{{ $meta }}</div>
    @endisset

    <div class="prose prose-ink mt-8 max-w-none">
        {{ $slot }}
    </div>

    @isset($share)
        <div class="mt-8 border-t border-ink/10 pt-6">{{ $share }}</div>
    @endisset

    @isset($related)
        <div class="mt-12 border-t border-ink/10 pt-8">{{ $related }}</div>
    @endisset
</article>
