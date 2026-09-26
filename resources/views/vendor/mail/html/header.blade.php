@props(['url'])
@php extract(require resource_path('views/vendor/mail/html/tokens.php')); @endphp
<tr>
<td class="header" align="center" bgcolor="{{ $c['band'] }}">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ $mailBase }}/images/brand/logo-email.png" class="logo" width="200" height="49" alt="{{ config('app.name') }}" style="border:0;display:block;height:49px;width:200px;">
</a>
</td>
</tr>
<tr>
<td class="thread" height="3" bgcolor="{{ $c['gold'] }}" style="font-size:0;line-height:3px;height:3px;">&nbsp;</td>
</tr>
