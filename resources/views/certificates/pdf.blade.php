<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), config('app.rtl_locales')) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('certificates.pdf_title') }}</title>
    <style>
        body {
            font-family: xbriyaz, dejavusans, sans-serif;
            color: #1E293B;
            text-align: center;
        }
        .frame {
            margin: 20px;
            padding: 40px 50px;
            height: 150mm;
            border: 8px solid #0F766E;
        }
        .institute {
            font-size: 14px;
            letter-spacing: 2px;
            color: #D4A24C;
        }
        h1 {
            font-size: 32px;
            margin: 30px 0 10px;
        }
        .name {
            font-size: 26px;
            font-weight: bold;
            color: #0F766E;
            margin: 20px 0;
        }
        .course {
            font-size: 20px;
            margin: 10px 0 30px;
        }
        .meta {
            font-size: 12px;
            color: #64748B;
            margin-top: 60px;
        }
        .code {
            font-size: 12px;
            color: #64748B;
        }
    </style>
</head>
@php
    $dirOf = fn (string $text) => preg_match('/\p{Arabic}/u', $text) ? 'rtl' : 'ltr';
@endphp
<body>
<div class="frame">
    <p class="institute">{{ config('app.name') }}</p>
    <h1>{{ __('certificates.pdf_heading') }}</h1>
    <p>{{ __('certificates.pdf_awarded_to') }}</p>
    <p class="name" dir="{{ $dirOf($user->name) }}">{{ $user->name }}</p>
    <p>{{ __('certificates.pdf_for_completing') }}</p>
    <p class="course" dir="{{ $dirOf($course->title) }}">{{ $course->title }}</p>

    <div class="meta">
        <p>{{ __('certificates.pdf_issued_on', ['date' => $certificate->issued_at->format('d M Y')]) }}</p>
        <p class="code">{{ __('certificates.pdf_verification_code') }}: {{ $certificate->verification_code }}</p>
    </div>
</div>
</body>
</html>
