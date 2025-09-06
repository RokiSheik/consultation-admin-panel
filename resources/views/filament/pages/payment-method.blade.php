<x-filament::page>
    <div class="w-full max-w-3xl mx-auto">

        <!-- PayPal Form -->
        <form wire:submit.prevent="savePaypal" class=" shadow-md rounded-lg p-6">
            {{ $this->paypalForm }}
            <div class="flex justify-center mt-4 pt-4">
                <x-filament::button type="submit">
                    Save PayPal Settings
                </x-filament::button>
            </div>
        </form>

        <!-- SSLCommerz Form -->
        <form wire:submit.prevent="saveSslcommerz" class=" shadow-md rounded-lg p-6 mt-6">
            {{ $this->sslcommerzForm }}
            <div class="flex justify-center mt-4 pt-4">
                <x-filament::button type="submit">
                    Save SSLCommerz Settings
                </x-filament::button>
            </div>
        </form>

    </div>
</x-filament::page>