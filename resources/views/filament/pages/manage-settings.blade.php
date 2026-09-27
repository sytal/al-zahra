<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div style="display:flex;justify-content:flex-end;margin-block-start:1.5rem">
            <x-filament::button type="submit" icon="heroicon-o-check">
                {{ __('admin_ui.settings.save') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
