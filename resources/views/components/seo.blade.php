@props(['title', 'description' => null, 'image' => null, 'type' => 'website', 'schema' => null])

<title>{{ $title }} — {{ config('app.name') }}</title>
@if ($description)<meta name="description" content="{{ $description }}">@endif
<link rel="canonical" href="{{ url()->current() }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
@if ($description)<meta property="og:description" content="{{ $description }}">@endif
@if ($image)<meta property="og:image" content="{{ $image }}">@endif

@foreach (config('app.locales', ['en', 'ur', 'hi', 'fa', 'ur-roman']) as $locale)
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ url()->current() }}">
@endforeach

@if ($schema)
    <script type="application/ld+json">{!! json_encode($schema) !!}</script>
@endif
