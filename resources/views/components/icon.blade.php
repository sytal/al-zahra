@props(['name', 'size' => 'size-5'])

{!! svg('heroicon-o-' . $name)->class($size . ' ' . $attributes->get('class'))->toHtml() !!}
