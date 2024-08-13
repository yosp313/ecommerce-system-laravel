<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

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
            return [
                'status' => 'error',
                'message' => 'Product out of stock'
            ];
        }

        $cartItem = CartItem::where("user_id", Auth::id())->where("product_id", $product->id)->first();

        if($cartItem){
            $cartItem->quantity += $args['quantity'];
            $cartItem->total = $cartItem->quantity * $product->price;
            $cartItem->save();
        } else{
            $cartItem = new CartItem();
            $cartItem->user_id = Auth::id();
            $cartItem->product_id = $product->id;
            $cartItem->quantity = $args['quantity'];
            $cartItem->total = $args['quantity'] * $product->price;
            $cartItem->save();
        }

        return [
            'status' => 'success',
            'message' => 'Product added to cart',
            'cartItem' => $cartItem
        ];
    }
}
