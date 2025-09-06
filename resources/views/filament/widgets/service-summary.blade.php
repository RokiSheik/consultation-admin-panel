<div class="mt-4 text-sm text-gray-700">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
        <div>
            <strong>Total Orders:</strong> {{ number_format($totalOrders) }}
        </div>
        <div>
            <strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}
        </div>
    </div>
</div>
