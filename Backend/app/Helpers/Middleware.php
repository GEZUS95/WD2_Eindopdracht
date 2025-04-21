<?php

namespace Helpers;

use Firebase\JWT\ExpiredException;
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

    public function auth(array $roles): bool
    {
        $jwt = explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[1];
        $decodedJWT = $this->decodeJWT($jwt);
        $user = $this->userService->GetUserByEmail($decodedJWT->data->email);
        if (!in_array($user->role, $roles)) {
            $this->respondWithCode(401, "Unauthorized");
            die();
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
                "role" => $user->role->toString(),
            )
        );
        $jwt = JWT::encode($payload, $key, 'HS256');

        return array(
            'message' => 'Successful login',
            'token' => $jwt,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role->toString(),
            'expireAt' => $expireTime
        );
    }

    public function decodeJWT(string $jwt): object
    {
        $key = "login_key_ticketinator";
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        } catch (ExpiredException $e) {
            $this->respondWithCode(401, $e->getMessage());
            die();
        } catch (\Exception $e) {
            $this->respondWithCode(500, $e->getMessage());
            die();
        }
        return $decoded;
    }

    public function getUserFromJWT(): User
    {
        $jwt = explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[1];
        $decodedJWT = $this->decodeJWT($jwt);
        return $this->userService->GetUserByEmail($decodedJWT->data->email);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }
}

