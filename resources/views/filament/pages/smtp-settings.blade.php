<x-filament::page>
    <div>
        {{ $this->form }}
        <div class="pt-4 flex justify-center">
            <x-filament::button wire:click="saveSettings">
                Save Settings
            </x-filament::button>
        </div>
    </div>
</x-filament::page>
