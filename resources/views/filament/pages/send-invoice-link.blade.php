<x-filament::page>
    <form wire:submit.prevent="submit" class="shadow-md rounded-lg p-6 mt-6 space-y-4">
        {{ $this->form }}

        <div class="mt-4">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md bg-primary-600 px-6 py-2 text-white text-sm font-semibold shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition"
            >
                🚀 Send Invoice
            </button>
        </div>

        {{-- Show success message after submit --}}
        @if ($success)
            <div class="mt-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-md">
                ✅ Invoice sent successfully!
            </div>
        @endif
    </form>
</x-filament::page>
