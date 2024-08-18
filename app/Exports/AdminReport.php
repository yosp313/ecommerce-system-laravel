<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminReport implements FromCollection, WithHeadings
{

    public $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'order_total' => $order->pluck('total')->implode(','),
                'items' => $order->orderItems->count(),
                'product_id' => $order->orderItems->pluck('product_id')->implode(','),
                'product_name' => $order->orderItems->pluck('product')->pluck('name')->implode(','),
                'quantity' => $order->orderItems->pluck('quantity')->implode(','),
                'price' => $order->orderItems->pluck('product')->pluck('price')->implode(','),
                'item_total' => $order->orderItems->pluck('total')->implode(','),
                'shipping_address' => $order->address->pluck('label')->implode(','),
                'recipient_name' => $order->address->name,
                'address_line_1' => $order->address->address_line1,
                'address_line_2' => $order->address->address_line2,
                'city' => $order->address->city,
                'state' => $order->address->state,
                'postal_code' => $order->address->postal_code,
                'country' => $order->address->country,
                'phone_number' => $order->address->phone_number,
                'payment_method' => $order->paymentMethod->type,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];
        });

    }

    public function headings(): array
    {
        return [
            'Order ID',
            'User ID',
            'Status',
            'Order Total',
            'Items',
            'Product ID',
            'Product Name',
            'Quantity',
            'Price',
            'Item Total',
            'Shipping Address',
            'Recipient Name',
            'Address Line 1',
            'Address Line 2',
            'City',
            'State',
            'Postal Code',
            'Country',
            'Phone Number',
            'Payment Method',
            'Created At',
            'Updated At',
        ];
    }
}
