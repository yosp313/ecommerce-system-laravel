<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class AddAddress
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args,GraphQLContext $context,ResolveInfo $info)
    {
        $label = $args['label'];
        $name = $args['name'];
        $address_line1 = $args['address_line1'];
        $address_line2 = $args['address_line2'];
        $city = $args['city'];
        $state = $args['state'];
        $postal_code = $args['postal_code'];
        $country = $args['country'];
        $phone_number = $args['phone_number'];

        $address = Address::create([
            'user_id' => Auth::id(),
            'label' => $label,
            'name' => $name,
            'address_line1' => $address_line1,
            'address_line2' => $address_line2,
            'city' => $city,
            'state' => $state,
            'postal_code' => $postal_code,
            'country' => $country,
            'phone_number' => $phone_number,
        ]);

        return $address;
    }

}
