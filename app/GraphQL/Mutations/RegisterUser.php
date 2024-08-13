<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class RegisterUser
{
    /** @param  array{}  $args */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        if ($args['password'] !== $args['password_confirmation']) {
            //send an error message to the user
            return ["message" => "Passwords do not match"];
        }

        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
        ]);

        // Auth::login($user);


        // $user->sendEmailVerificationNotification();

        return $user;
    }
}
