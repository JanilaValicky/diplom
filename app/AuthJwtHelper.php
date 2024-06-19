<?php

namespace App;

use App\Models\User;

class AuthJwtHelper
{
    public static function getJwt(string $email)
    {
        $salt = bin2hex(random_bytes(16));

        $timestamp = time();

        $signature = hash('sha256', $email . $salt . $timestamp . env('SECRET_PHRASE'));

        $token_data = [
            'email' => $email,
            'salt' => $salt,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ];

        return base64_encode(json_encode($token_data));
    }

    public static function isValidToken(string $token)
    {
        return self::auth($token) ? true : false;
    }

    public static function auth(string $token)
    {
        return User::where('email', self::_getEmail($token))->first();
    }

    private static function _getEmail(string $token)
    {
        $token_data = json_decode(base64_decode($token), true);

        if ($token_data && isset($token_data['email'], $token_data['salt'], $token_data['timestamp'], $token_data['signature'])) {
            $calculated_signature = hash('sha256', $token_data['email'] . $token_data['salt'] . $token_data['timestamp'] . env('SECRET_PHRASE'));

            if ($calculated_signature === $token_data['signature']) {
                return $token_data['email'];
            }
        }

        return null;
    }
}
