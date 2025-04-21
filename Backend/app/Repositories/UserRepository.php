<?php

namespace Repositories;

use Enums\Role;
use Models\User;
use PDO;
use PDOException;

class UserRepository extends Repository
{
    public function checkUsernamePassword($username, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT * FROM dev.users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $res = $stmt->fetch();
            $user = $this->getUserObj($res);

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $res['password']);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // hash the password (currently uses bcrypt)
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }

    // retrieve the user with the given id
    public function getUserById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, email, role, birthday, created_at, updated_at FROM dev.users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $obj = $stmt->fetch();

            $user = $this->getUserObj($obj);

            if (!$user) {
                return null;
            }
            // make sure the password hash is not passed to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // save the given user to the database
    public function saveUser($user)
    {
        try {
            // hash the password before saving it to the database
            $password = $this->hashPassword($user->password);

            $bday = $user->birthday->format('Y-m-d');
            $role = $user->role->toString();

            $stmt = $this->connection->prepare("INSERT INTO dev.users (id, username, password, email, role, birthday, token) VALUES (:id, :username, :password, :email, :role, :birthday, :token)");
            $stmt->bindParam(':id', $user->id);
            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':birthday', $bday);
            $stmt->bindParam(':token', $user->token);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // update the given user in the database
    public function updateUser($user)
    {
        try {
            $bday = $user->birthday->format('Y-m-d');
            $role = $user->role->toString();

            $stmt = $this->connection->prepare("UPDATE dev.users SET username = :username, email = :email, role = :role, birthday = :birthday, updated_at = NOW() WHERE id = :id");
            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':birthday', $bday);
            $stmt->bindParam(':id', $user->id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // update password of the given user in the database
    public function updatePassword($user)
    {
        try {
            // hash the password before saving it to the database
            $user->password = $this->hashPassword($user->password);

            $stmt = $this->connection->prepare("UPDATE dev.users SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $user->password);
            $stmt->bindParam(':id', $user->id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // retrieve the user with the given token
    public function getUserByToken($token)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, email, role, birthday, created_at, updated_at, token  FROM dev.users WHERE token = :token");
//            $stmt = $this->connection->prepare("SELECT u.id, u.username, u.email, r.name as role_name
//            FROM user u
//            JOIN roles r ON u.role_id = r.id
//            WHERE u.token = :token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            // make sure the password hash is not passed to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // get all the users with limit and offset
    public function getUsers($limit, $offset)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, email, role, birthday, created_at, updated_at, token FROM dev.users LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll();
            $response = [];
            foreach ($users as $user) {
                $user = $this->getUserObj($user);
                $response[] = $user;
            }

            return $response;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, email, role, birthday, created_at, updated_at, token FROM dev.users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $res = $stmt->fetch();

            if (!$res) {
                return null;
            }

            $user = $this->getUserObj($res);
            $user->password = ""; // make sure the password hash is not passed to the caller

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function deleteUser($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM dev.users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getUserObj(mixed $user): User
    {
        $userObj = new User();
        $userObj->id = $user['id'];
        $userObj->username = $user['username'];
        $userObj->email = $user['email'];
        $userObj->role = Role::from($user['role']);
        $userObj->birthday = new \DateTime($user['birthday']);
        $userObj->created_at = new \DateTime($user['created_at']);
        $userObj->updated_at = new \DateTime($user['updated_at']);
        return $userObj;
    }

}
