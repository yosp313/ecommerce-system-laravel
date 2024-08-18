    <p>Hello,</p>
    <p>Attached is the daily orders report for {{ now()->format('Y-m-d') }}.</p>
    <p>Total Orders: {{ $totalOrders }}</p>
    <p>Total Sales: ${{ $totalSales }}</p>
    <p>Thank you!</p>
