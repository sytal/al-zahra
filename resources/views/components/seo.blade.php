@props(['title', 'description' => null, 'image' => null, 'type' => 'website', 'schema' => null])

@php
$segments = explode('/', trim(request()->path(), '/'));
$currentLocale = app()->getLocale();
@endphp

<title>{{ $title }} — {{ config('app.name') }}</title>
@if ($description)<meta name="description" content="{{ $description }}">@endif
<link rel="canonical" href="{{ url()->current() }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
@if ($description)<meta property="og:description" content="{{ $description }}">@endif
@if ($image)<meta property="og:image" content="{{ $image }}">@endif

@foreach (config('app.locales') as $locale)
    @php
    $localeSegments = $segments;
    if (($localeSegments[0] ?? null) === $currentLocale) {
        $localeSegments[0] = $locale;
    } else {
        array_unshift($localeSegments, $locale);
    }
    @endphp
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ url(implode('/', $localeSegments)) }}">
@endforeach

@if ($schema)
    <script type="application/ld+json">{!! json_encode($schema) !!}</script>
@endif
