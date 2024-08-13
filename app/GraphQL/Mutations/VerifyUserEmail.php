<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Crypt;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class VerifyUserEmail
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $token = $args['token'];

        $id = Crypt::decrypt($token);

        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return ['message' => 'Email already verified'];
        }

        if($user->markEmailAsVerified()) {
            event(new Verified($user));
            return ['message' => 'Email verified', "user" => $user];
        }

        return ['message' => 'Email not verified'];
    }
}
