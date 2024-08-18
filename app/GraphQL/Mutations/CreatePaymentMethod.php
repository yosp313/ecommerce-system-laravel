<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\PaymentMethod;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class CreatePaymentMethod
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args,GraphQLContext $context,ResolveInfo $info)
    {
        $userId = Auth::id();
        $paymentType = $args['type'];
        $lastFour = $args['last_four'];

        $payMethod = PaymentMethod::create([
            'user_id' => $userId,
            'type' => $paymentType,
            'last_four' => $lastFour
        ]);

        return $payMethod;
    }
}
