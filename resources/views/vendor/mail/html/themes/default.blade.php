@php extract(require resource_path('views/vendor/mail/html/tokens.php')); @endphp
{{-- Mail CSS generated from config('theme.*') (resources/theme/theme.json). Edit theme.json, never hex here. --}}
body, body *:not(html):not(style):not(br):not(tr):not(code) {
    box-sizing: border-box;
    font-family: {!! $sans !!};
}
body {
    -webkit-text-size-adjust: 100%;
    background-color: {{ $c['page'] }};
    color: {{ $c['body'] }};
    line-height: 1.6;
    margin: 0;
    padding: 0;
    width: 100% !important;
}
p, ul, ol, blockquote {
    color: {{ $c['body'] }};
    font-size: 16px;
    line-height: 1.7;
    margin-top: 0;
    text-align: {{ $mailAlign }};
}
a { color: {{ $c['link'] }}; }
a img { border: none; }
h1 {
    color: {{ $c['strong'] }};
    font-family: {!! $serif !!};
    font-size: 26px;
    font-weight: bold;
    line-height: 1.3;
    margin: 0 0 16px;
    text-align: {{ $mailAlign }};
}
h2 { color: {{ $c['strong'] }}; font-size: 18px; font-weight: bold; margin-top: 0; text-align: {{ $mailAlign }}; }
h3 { color: {{ $c['strong'] }}; font-size: 15px; font-weight: bold; margin-top: 0; text-align: {{ $mailAlign }}; }
strong { color: {{ $c['strong'] }}; }
img { max-width: 100%; }

.wrapper { background-color: {{ $c['page'] }}; margin: 0; padding: 0; width: 100%; }
.content { margin: 0; padding: 0; width: 100%; }

.header { background-color: {{ $c['band'] }}; padding: 28px 24px 24px; text-align: center; }
.header a { color: {{ $c['bandtext'] }}; font-size: 20px; font-weight: bold; text-decoration: none; }
.logo { border: 0; display: inline-block; height: 49px; width: 200px; }
.thread { background-color: {{ $c['gold'] }}; font-size: 0; height: 3px; line-height: 3px; }

.body { background-color: {{ $c['page'] }}; border: 0; margin: 0; padding: 0; width: 100%; }
.inner-body {
    background-color: {{ $c['card'] }};
    border-bottom: 1px solid {{ $c['border'] }};
    margin: 0 auto;
    padding: 0;
    width: 600px;
}
.inner-body a { word-break: break-all; }
.content-cell { max-width: 100vw; padding: 36px 40px; }

.subcopy { border-top: 1px solid {{ $c['border'] }}; margin-top: 28px; padding-top: 24px; }
.subcopy p { color: {{ $c['muted'] }}; font-size: 13px; line-height: 1.6; }

.footer { margin: 0 auto; padding: 0; text-align: center; width: 600px; }
.footer .content-cell { padding: 28px 40px 40px; }
.footer p { color: {{ $c['muted'] }}; font-size: 13px; line-height: 1.6; text-align: center; }
.footer a { color: {{ $c['muted'] }}; text-decoration: underline; }

.table table { margin: 24px auto; width: 100%; }
.table th { border-bottom: 1px solid {{ $c['border'] }}; color: {{ $c['strong'] }}; margin: 0; padding-bottom: 8px; text-align: {{ $mailAlign }}; }
.table td { color: {{ $c['body'] }}; font-size: 15px; line-height: 20px; margin: 0; padding: 10px 0; text-align: {{ $mailAlign }}; }

.action { margin: 28px auto; padding: 0; text-align: center; width: 100%; }
.button {
    border-radius: 10px;
    color: {{ $c['onbrand'] }};
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
    line-height: 1.2;
    padding: 15px 34px;
    text-decoration: none;
}
.button-cell { border-radius: 10px; }
.button-blue, .button-primary { background-color: {{ $c['brand'] }}; color: {{ $c['onbrand'] }}; }
.button-green, .button-success { background-color: {{ config('theme.roles.light.success') }}; color: {{ $c['onbrand'] }}; }
.button-red, .button-error { background-color: {{ config('theme.roles.light.danger') }}; color: {{ $c['onbrand'] }}; }

.panel { border-{{ $mailAlign }}: 4px solid {{ $c['gold'] }}; margin: 22px 0; }
.panel-content { background-color: {{ $c['tint'] }}; color: {{ $c['body'] }}; padding: 16px 20px; }
.panel-content p { color: {{ $c['body'] }}; }
.panel-item { padding: 0; }
.panel-item p:last-of-type { margin-bottom: 0; padding-bottom: 0; }

.break-all { word-break: break-all; }
