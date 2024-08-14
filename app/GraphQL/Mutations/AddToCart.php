<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class AddToCart
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args,GraphQLContext $context,ResolveInfo $info)
    {
        $product = Product::find($args['product_id']);

        if($product->stock < $args['quantity']){
            throw new \Exception('Not enough stock');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $cartItem = CartItem::where('product_id', $product->id)
            ->where('cart_id', $cart->id)
            ->first();

        if($cartItem){
            $cartItem->quantity += $args['quantity'];
            $cartItem->total = $cartItem->quantity * $product->price;
        }else{
            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $args['quantity'],
                'total' => $args['quantity'] * $product->price
            ]);
        }
        $cartItem->save();

        $cart->total += $cart->items->sum('total');
        $cart->save();

        $product->stock -= $args['quantity'];
        $product->save();

        return $cart;
    }
}
