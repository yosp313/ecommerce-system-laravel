<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class CompleteOrder
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args,GraphQLContext $context,ResolveInfo $info)
    {
        $order = Order::findOrFail($args['order_id']);

        if ($order->status !== 'Pending') {
            throw new \Exception('Order is not pending.');
        }

        $order->status = 'Successful';
        $order->save();

        $user = Auth::user();

        Mail::to($user)->send(new OrderConfirmation($order));
    }
}
