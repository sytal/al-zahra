@props(['name', 'size' => 'size-5'])

<x-dynamic-component :component="'heroicon-o-' . $name" :class="$size . ' ' . $attributes->get('class')" />
