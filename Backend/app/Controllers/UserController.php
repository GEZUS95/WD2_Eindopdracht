<?php

namespace Controllers;

use Helpers\Middleware;
use Models\User;
use Services\UserService;

class UserController extends Controller
{
    private $service;
    private $midleware;

    // initialize services
    public function __construct()
    {
        $this->service = new UserService();
        $this->midleware = new Middleware();
    }

    public function GetAll($lim = 50, $off = 0): void
    {
        $users = $this->service->getAll($lim, $off);
        $this->respond($users);
    }

    public function Get($id): void
    {
        $user = $this->service->Get($id);
        if ($user) {
            $this->respond($user);
        } else {
            $this->respondWithError(404, "User with id '$id' not found");
        }
    }

    public function Create()
    {
        $data = $this->createObjectFromPostedJson(User::class);
        $user = $this->service->create($data);
        if ($user == null) {
            $this->respondWithError(400, "User with email '$data->email' already exists");
            return;
        }
        $this->respond($user);
    }

    public function Update($id): void
    {
        $data = $this->createObjectFromPostedJson(User::class);
        $data->id = $id;
        $user = $this->service->update($data);
        $this->respond($user);
    }

    public function UpdatePassword($user): void
    {
        $user = $this->service->updatePassword($user);
        $this->respond($user);
    }

    public function Delete($id): void
    {
        $this->service->delete($id);
        $this->respond("The user with id '$id' has been deleted");
    }

    public function Login(): void
    {
        $data = json_decode(file_get_contents('php://input'));
        $user = $this->service->checkUsernamePassword($data->username, $data->password);

        if ($user) {
            $jwt = $this->midleware->GenerateJWT($user);
            $this->respond($jwt);
        } else {
            $this->respondWithError(401, "Invalid username or password");
        }
    }
}
