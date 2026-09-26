@php
    $rtl = in_array(app()->getLocale(), config('app.rtl_locales'), true);
    $dirOf = fn (string $text) => preg_match('/^[^\p{L}]*\p{Arabic}/u', $text) ? 'rtl' : 'ltr';
    $fontOf = fn (string $text) => preg_match('/\p{Arabic}/u', $text) ? 'xbriyaz' : 'dejavuserif';
    $align = $rtl ? 'right' : 'left';

    $L = config('theme.roles.light');
    $P = config('theme.palettes');
    $teal = $P['primary']['700'];
    $tealDeep = $P['primary']['800'];
    $gold = $L['brand-secondary'];
    $goldText = $L['secondary-text'];
    $ink = $L['text-strong'];
    $muted = $L['text-muted'];
    $paper = $L['surface-raised'];
    $tint = $L['tint'];
    $border = $L['border'];

    $star = function (float $cx, float $cy, float $outer, ?float $inner = null) {
        $inner ??= $outer * 0.62;
        $pts = [];
        for ($i = 0; $i < 16; $i++) {
            $r = $i % 2 === 0 ? $outer : $inner;
            $a = deg2rad($i * 22.5 - 90);
            $pts[] = round($cx + $r * cos($a), 3).','.round($cy + $r * sin($a), 3);
        }

        return implode(' ', $pts);
    };

    $corner = function (float $x, float $y) use ($star, $gold, $paper) {
        return '<polygon points="'.$star($x, $y, 5.2).'" fill="'.$gold.'"/><polygon points="'.$star($x, $y, 2.6, 1.6).'" fill="'.$paper.'"/>';
    };

    $frame = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 297 210" width="297" height="210">'
        .'<rect width="297" height="210" fill="'.$paper.'"/>'
        .'<rect x="14" y="14" width="269" height="182" fill="'.$tint.'"/>'
        .'<rect x="6" y="6" width="285" height="198" fill="none" stroke="'.$teal.'" stroke-width="2.4"/>'
        .'<rect x="10" y="10" width="277" height="190" fill="none" stroke="'.$gold.'" stroke-width="0.7"/>'
        .'<rect x="14" y="14" width="269" height="182" fill="none" stroke="'.$teal.'" stroke-width="0.35"/>'
        .$corner(14, 14).$corner(283, 14).$corner(14, 196).$corner(283, 196)
        .'<polygon points="'.$star(148.5, 14, 3.4).'" fill="'.$teal.'"/>'
        .'<polygon points="'.$star(148.5, 196, 3.4).'" fill="'.$teal.'"/>'
        .'</svg>';

    $mark = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">'
        .'<path fill-rule="evenodd" fill="'.$teal.'" d="M32 2L40.78 10.8L53.21 10.79L53.2 23.22L62 32L53.2 40.78L53.21 53.21L40.78 53.2L32 62L23.22 53.2L10.79 53.21L10.8 40.78L2 32L10.8 23.22L10.79 10.79L23.22 10.8ZM19 32a13 13 0 1 0 26 0a13 13 0 1 0 -26 0Z"/>'
        .'<rect x="22.5" y="29" width="3" height="6" rx="1.5" fill="'.$gold.'"/><rect x="26.5" y="26" width="3" height="12" rx="1.5" fill="'.$gold.'"/>'
        .'<rect x="30.5" y="23" width="3" height="18" rx="1.5" fill="'.$gold.'"/><rect x="34.5" y="26" width="3" height="12" rx="1.5" fill="'.$gold.'"/>'
        .'<rect x="38.5" y="29" width="3" height="6" rx="1.5" fill="'.$gold.'"/></svg>';

    $thread = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 8" width="120" height="8">'
        .'<line x1="0" y1="4" x2="48" y2="4" stroke="'.$gold.'" stroke-width="0.6"/><line x1="72" y1="4" x2="120" y2="4" stroke="'.$gold.'" stroke-width="0.6"/>'
        .'<polygon points="'.$star(60, 4, 3.6).'" fill="'.$gold.'"/></svg>';

    $seal = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" width="60" height="60">'
        .'<polygon points="'.$star(30, 30, 29, 25).'" fill="'.$gold.'"/>'
        .'<polygon points="'.$star(30, 30, 29, 25).'" fill="none" stroke="'.$goldText.'" stroke-width="0.6" transform="rotate(22.5 30 30)"/>'
        .'<circle cx="30" cy="30" r="19" fill="'.$teal.'"/><circle cx="30" cy="30" r="16.5" fill="none" stroke="'.$gold.'" stroke-width="0.8"/>'
        .'<path d="M21.5 30.5L27.5 36.5L39 24" fill="none" stroke="'.$paper.'" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    $uri = fn (string $svg) => 'data:image/svg+xml;base64,'.base64_encode($svg);

    $verifyUrl = route('certificates.verify.form', ['locale' => app()->getLocale(), 'code' => $certificate->verification_code]);
    $displayDate = $certificate->issued_at->translatedFormat('d M Y');
    $headFont = $rtl ? 'xbriyaz' : 'dejavuserif';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('certificates.pdf_title') }}</title>
    <style>
        body { font-family: xbriyaz, dejavusans, sans-serif; color: {{ $ink }}; margin: 0; padding: 0; }
        .stage { position: absolute; left: 26mm; top: 25mm; width: 245mm; text-align: center; }
        .org { font-family: dejavuserif; font-size: 11pt; letter-spacing: 3px; color: {{ $goldText }}; margin: 3mm 0 0; }
        .title { font-family: {{ $headFont }}; font-size: 34pt; color: {{ $tealDeep }}; margin: 4mm 0 2mm; line-height: 1.25; }
        .lead { font-size: 12pt; color: {{ $muted }}; margin: 2mm 0 0; }
        .name { font-size: 32pt; color: {{ $teal }}; margin: 4mm 0 0; padding: 0 0 2mm; line-height: 1.3; border-bottom: 0.4mm solid {{ $gold }}; }
        .course { font-size: 19pt; color: {{ $ink }}; margin: 3mm 0 0; line-height: 1.4; }
        .label { font-size: 8.5pt; color: {{ $muted }}; margin: 0; }
        .value { font-size: 11pt; color: {{ $ink }}; margin: 0.5mm 0 0; }
        .code { font-family: dejavusansmono; font-size: 9pt; color: {{ $tealDeep }}; margin: 1mm 0 0; }
        .sig-name { font-size: 12pt; color: {{ $ink }}; border-top: 0.3mm solid {{ $ink }}; margin: 0 8mm; padding-top: 1.5mm; }
        .sig-title { font-size: 9pt; color: {{ $muted }}; margin: 0.5mm 0 0; }
        td { vertical-align: bottom; }
    </style>
</head>
<body>
<div dir="ltr" style="position: absolute; left: 0; top: 0; width: 297mm; height: 210mm;"><img src="{{ $uri($frame) }}" style="width: 297mm; height: 210mm;"></div>

<div class="stage">
    <img src="{{ $uri($mark) }}" style="width: 13mm; height: 13mm;">
    <p class="org"><span dir="ltr">{{ strtoupper(config('app.name')) }}</span> &middot; <span style="font-family: {{ app()->getLocale() === 'en' ? 'dejavuserif' : 'xbriyaz' }};">{{ app()->getLocale() === 'en' ? strtoupper(__('certificate_pdf.institute')) : __('certificate_pdf.institute') }}</span></p>
    <p class="title">{{ __('certificates.pdf_heading') }}</p>
    <img src="{{ $uri($thread) }}" style="width: 40mm; height: 2.7mm;">
    <p class="lead">{{ __('certificates.pdf_awarded_to') }}</p>
    <p class="name" dir="{{ $dirOf($user->name) }}" style="font-family: {{ $fontOf($user->name) }};">{{ $user->name }}</p>
    <p class="lead" style="margin-top: 4mm;">{{ __('certificates.pdf_for_completing') }}</p>
    <p class="course" dir="{{ $dirOf($course->title) }}" style="font-family: {{ $fontOf($course->title) }};">{{ $course->title }}</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 17mm;" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
        <tr>
            <td width="34%" align="center">
                <p class="label">{{ __('certificate_pdf.code_label') }}</p>
                <p class="code">{{ $certificate->verification_code }}</p>
                <p class="label" style="margin-top: 3mm;" dir="ltr">{{ str_replace(['https://', 'http://'], '', strtok($verifyUrl, '?')) }}</p>
            </td>
            <td width="32%" align="center">
                <img src="{{ $uri($seal) }}" style="width: 26mm; height: 26mm;">
                <p class="label" style="margin-top: 1mm;">{{ __('certificate_pdf.issued_label') }}</p>
                <p class="value" dir="{{ $dirOf($displayDate) }}">{{ $displayDate }}</p>
            </td>
            <td width="34%" align="center">
                <div>
                    @if ($director)
                        <p class="sig-name" dir="{{ $dirOf($director['name']) }}" style="font-family: {{ $fontOf($director['name']) }};">{{ $director['name'] }}</p>
                        <p class="sig-title" dir="{{ $dirOf($director['title'] ?: __('certificate_pdf.director')) }}">{{ $director['title'] ?: __('certificate_pdf.director') }}</p>
                    @else
                        <p class="sig-name">{{ __('certificate_pdf.director') }}</p>
                        <p class="sig-title">{{ config('app.name') }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
