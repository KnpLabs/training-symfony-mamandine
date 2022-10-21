<?php

namespace App\Security;

use Lcobucci\JWT\Token\Plain;

final class UsernameResolver
{
    public static function resolveUsername(Plain $token): ?string
    {
        if ($username = $token->claims()->get('username')) {
            return $username;
        }

        if ($email = $token->claims()->get('email')) {
            return $email;
        }

        return null;
    }

    private function __construct()
    {
    }
}
