<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Order;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class OrdersResolver
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args,GraphQLContext $context,ResolveInfo $info)
    {
        $orders = Order::where('user_id', Auth::id())->get();

        //filter the orders
        if(isset($args['status'])){
            $orders = $orders->where('status', $args['status']);
        }

        //sort the orders
        if(isset($args['sort'])){
            switch($args['sort']){
                case 'created_at_desc':
                    $orders = $orders->latest();
                    break;
                case 'created_at_asc':
                    $orders = $orders->oldest();
                    break;
            }
        }

        //paginate the orders
        if(isset($args['first'])){
            $orders = $orders->take($args['first']);
        }

        return $orders;
    }

}
