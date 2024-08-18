<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/* The CreateOrder class in PHP creates a new order based on the provided arguments and updates product
stock accordingly. */
final readonly class CreateOrder
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args,GraphQLContext $context,ResolveInfo $info)
    {
        $address_id = $args['address_id'];
        $payment_id = $args['payment_method_id'];
        $cart = Cart::where('user_id', Auth::id())->where('id', $args['cart_id'])->first();

        if ($cart) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $address_id,
                'payment_method_id' => $payment_id,
                'status' => 'Pending',
                'total' => $cart->total,
            ]);

            //create order items
            foreach ($cart->items as $item) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);
            }

            //deduct the quantity of the product from the stock
            foreach ($cart->items as $item) {
                $product = Product::find($item->product_id);

                if($product->stock < $item->quantity){
                    throw new \Exception('Product out of stock');
                }

                $product->stock = $product->stock - $item->quantity;
                $product->save();
            }

            //delete the cart and cart items
            $cart->delete();

            //on order completion send email to the user

            return $order;
        }
    }
}
