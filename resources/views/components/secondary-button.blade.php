<x-button variant="outline" :type="$attributes->get('type', 'button')" {{ $attributes->except('type') }}>{{ $slot }}</x-button>
