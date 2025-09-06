<x-filament::page>
    <div class="w-full max-w-3xl mx-auto">

        <!-- Facebook Form -->
        <form wire:submit.prevent="saveFacebook" class=" shadow-md rounded-lg p-6">
            {{ $this->facebookForm }}
            <div class="flex justify-center mt-4 pt-4">
                <x-filament::button type="submit">
                    Save
                </x-filament::button>
            </div>
        </form>

        <!-- Google Form -->
        <form wire:submit.prevent="saveGoogle" class=" shadow-md rounded-lg p-6 mt-6">
            {{ $this->googleForm }}
            <div class="flex justify-center mt-4 pt-4">
                <x-filament::button type="submit">
                    Save
                </x-filament::button>
            </div>
        </form>

    </div>
</x-filament::page>