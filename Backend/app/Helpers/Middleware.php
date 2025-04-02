<?php

namespace Helpers;

use Enums\Role;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Models\User;
use Services\UserService;

class Middleware
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function auth(Role $role): bool
    {
        $jwt = explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[1];
        $decodedJWT = $this->decodeJWT($jwt);
        $user = $this->userService->GetUserByEmail($decodedJWT->data->email);

        if ($user->role != $role) {
            return false;
        }
        return true;
    }

    public function generateJWT(User $user): array
    {
        $time = time();
        $expireTime = $time + 10000;
        $key = "login_key_ticketinator";
        $payload = array(
            "iss" => "http://ticketinator.org",
            "aud" => "http://ticketinator.com",
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expireTime,
            "data" => array(
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email,
            )
        );
        $jwt = JWT::encode($payload, $key, 'HS256');

        return array(
            'message' => 'Successful login',
            'token' => $jwt,
            'username' => $user->username,
            'email' => $user->email,
            'expireAt' => $expireTime
        );
    }

    public function decodeJWT(string $jwt): object
    {
        $key = "login_key_ticketinator";
        return JWT::decode($jwt, new Key($key, 'HS256'));
    }

    public function getUserFromJWT(): User
    {
        $jwt = explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[1];
        $decodedJWT = $this->decodeJWT($jwt);
        return $this->userService->GetUserByEmail($decodedJWT->data->email);
    }
}

