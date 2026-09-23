@props(['url', 'title'])

@php
$networks = [
    'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url),
    'twitter' => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title),
    'whatsapp' => 'https://wa.me/?text=' . urlencode($title . ' ' . $url),
    'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url),
];
@endphp

<div class="flex items-center gap-2">
    @foreach ($networks as $name => $shareUrl)
        <a
            href="{{ $shareUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="flex size-9 items-center justify-center rounded-full border border-ink/10 text-ink/60 transition duration-200 ease-in-out hover:border-brand-primary hover:text-brand-primary"
        >
            <x-icon :name="$name === 'twitter' ? 'x-mark' : 'share'" class="size-4" />
        </a>
    @endforeach
</div>
