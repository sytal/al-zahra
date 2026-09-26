@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
@php extract(require resource_path('views/vendor/mail/html/tokens.php')); @endphp
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="button-cell button-cell-{{ $color }}" bgcolor="{{ $c['brand'] }}" style="border-radius:10px;background-color:{{ $c['brand'] }};">
<a href="{{ $url }}" class="button button-{{ $color }}" target="_blank" rel="noopener" style="background-color:{{ $c['brand'] }};border-radius:10px;color:{{ $c['onbrand'] }};display:inline-block;font-weight:bold;padding:15px 34px;text-decoration:none;">{!! $slot !!}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
