<?php

namespace Services;

use Ramsey\Uuid\Uuid;
use Repositories\UserRepository;

class UserService
{

    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function checkUsernamePassword($username, $password)
    {
        return $this->repository->checkUsernamePassword($username, $password);
    }

    public function getAll($lim, $off)
    {
        return $this->repository->getUsers($lim, $off);
    }

    public function get($id)
    {
        return $this->repository->getUserById($id);
    }

    public function create($user)
    {
        $firstuser = $this->repository->getUserByEmail($user->email);
        if ($firstuser) {
            return null;
        }
        $user->id = Uuid::uuid4()->toString();
        $user->token = Uuid::uuid4()->toString();
        $this->repository->saveUser($user);
        $newUser = $this->repository->getUserByEmail($user->email);
        return $newUser;
    }

    public function GetUserByEmail($email)
    {
        return $this->repository->getUserByEmail($email);
    }

    public function update($user)
    {
        $this->repository->updateUser($user);
        return $this->repository->getUserById($user->id);
    }

    public function updatePassword($user)
    {
        $this->repository->updatePassword($user);
        return $this->repository->getUserById($user->id);
    }

    public function delete($id)
    {
        $this->repository->deleteUser($id);
    }

}
