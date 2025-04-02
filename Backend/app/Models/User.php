<?php

namespace Models;

use Enums\Role;
use JsonSerializable;

class User implements JsonSerializable
{
    public string $id;
    public string $username;
    public string $password;
    public string $email;
    public Role $role;
    public \DateTime $birthday;
    public \DateTime $created_at;
    public \DateTime $updated_at;
    public string $token;

    public function setRole($role): void
    {
        $this->role = Role::from($role);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role->toString(),
            'birthday' => $this->birthday->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'token' => $this->token,
        ];
    }
}
