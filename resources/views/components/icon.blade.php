@props(['name', 'size' => 'size-5'])

{!! svg('heroicon-o-' . $name, trim($size . ' ' . $attributes->get('class')), $attributes->except('class')->getAttributes())->toHtml() !!}
