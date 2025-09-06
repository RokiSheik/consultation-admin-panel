<div x-data="{ filter: @entangle('filter') }" class="p-6 bg-white rounded shadow max-w-sm mx-auto">
    <h2 class="text-xl font-semibold mb-4">Total Revenue</h2>

    <select
        x-model="filter"
        @change="$wire.filterChanged(filter)"
        class="mb-4 px-3 py-2 border rounded w-full"
    >
        @foreach ($filters as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @endforeach
    </select>

    <p class="text-4xl font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</p>
</div>
