<x-button variant="primary" :type="$attributes->get('type', 'submit')" {{ $attributes->except('type') }}>{{ $slot }}</x-button>
