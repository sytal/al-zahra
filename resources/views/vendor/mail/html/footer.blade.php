@php extract(require resource_path('views/vendor/mail/html/tokens.php')); @endphp
<tr>
<td>
<table class="footer" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
<p style="text-align:center;margin:0 0 6px;"><span style="color:{{ $c['gold'] }};">&#10022;</span></p>
<p style="text-align:center;margin:0 0 14px;">{{ __('mail_ui.tagline') }}</p>
<p style="text-align:center;margin:0 0 14px;">
<a href="{{ $mailBase }}" target="_blank" rel="noopener">{{ __('mail_ui.visit_site') }}</a>
&nbsp;&middot;&nbsp;
<a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
</p>
<p style="text-align:center;margin:0 0 6px;">{{ __('mail_ui.sent_because', ['app' => config('app.name')]) }}</p>
<p style="text-align:center;margin:0;">{!! trim($slot) !!}</p>
</td>
</tr>
</table>
</td>
</tr>
