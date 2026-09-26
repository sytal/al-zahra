@php extract(require resource_path('views/vendor/mail/html/tokens.php')); @endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', $mailLocale) }}" dir="{{ $mailDir }}">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
<style>
.content-cell { word-break: break-word; overflow-wrap: anywhere; }
:root { color-scheme: light dark; supported-color-schemes: light dark; }
@@media only screen and (max-width: 640px) {
.inner-body, .footer { width: 100% !important; table-layout: fixed !important; }
.content-cell { padding: 28px 22px !important; }
.footer .content-cell { padding: 24px 22px 32px !important; }
h1 { font-size: 23px !important; }
}
@@media only screen and (max-width: 500px) {
.button { display: block !important; text-align: center !important; }
.button-cell { width: 100% !important; }
}
@@media only screen and (prefers-color-scheme: dark) {
html { background-color: {{ $c['dpage'] }} !important; }
body, .wrapper, .body { background-color: {{ $c['dpage'] }} !important; color: {{ $c['dbody'] }} !important; }
.inner-body { background-color: {{ $c['dcard'] }} !important; border-color: {{ $c['dborder'] }} !important; }
.header { background-color: {{ $c['dband'] }} !important; }
.thread { background-color: {{ $c['dgold'] }} !important; }
p, ul, ol, blockquote, li, .table td { color: {{ $c['dbody'] }} !important; }
h1, h2, h3, strong, .table th { color: {{ $c['dstrong'] }} !important; }
a { color: {{ $c['dlink'] }} !important; }
.subcopy { border-color: {{ $c['dborder'] }} !important; }
.subcopy p, .footer p, .footer a { color: {{ $c['dmuted'] }} !important; }
.button-primary, .button-cell-primary { background-color: {{ $c['dbrand'] }} !important; color: {{ $c['donbrand'] }} !important; }
.button-primary { border-color: {{ $c['dbrand'] }} !important; }
.panel { border-color: {{ $c['dgold'] }} !important; }
.panel-content { background-color: {{ $c['dtint'] }} !important; }
.panel-content p { color: {{ $c['dbody'] }} !important; }
.header a { color: {{ $c['dbody'] }} !important; }
}
[data-ogsc] body, [data-ogsc] .wrapper, [data-ogsc] .body { background-color: {{ $c['dpage'] }} !important; }
[data-ogsc] .inner-body { background-color: {{ $c['dcard'] }} !important; }
[data-ogsc] p, [data-ogsc] li { color: {{ $c['dbody'] }} !important; }
[data-ogsc] h1, [data-ogsc] h2, [data-ogsc] strong { color: {{ $c['dstrong'] }} !important; }
</style>
{!! $head ?? '' !!}
</head>
<body dir="{{ $mailDir }}" bgcolor="{{ $c['page'] }}">

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" bgcolor="{{ $c['page'] }}">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{!! $header ?? '' !!}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
<table class="inner-body" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" bgcolor="{{ $c['card'] }}">
<!-- Body content -->
<tr>
<td class="content-cell" dir="{{ $mailDir }}" align="{{ $mailAlign }}">
{!! Illuminate\Mail\Markdown::parse($slot) !!}

{!! $subcopy ?? '' !!}
</td>
</tr>
</table>
</td>
</tr>

{!! $footer ?? '' !!}
</table>
</td>
</tr>
</table>
</body>
</html>
