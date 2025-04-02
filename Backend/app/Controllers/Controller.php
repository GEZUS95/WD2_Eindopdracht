<?php

namespace Controllers;

use Enums\Role;

class Controller
{
    public function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    public function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    protected function createObjectFromPostedJson($className)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        // Maak dynamisch een object aan van de klasse
        $object = new $className();
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            if ($key === 'birthday' && !empty($value)) {
                $object->{$key} = new \DateTime($value);
            } elseif ($key === 'role' && !empty($value)) {
                $object->{$key} = Role::from($value);
            } elseif ($key === 'status' && !empty($value)) {
                $object->{$key} = \Enums\Status::from($value);
            } elseif ($key === 'priority' && !empty($value)) {
                $object->{$key} = \Enums\Priority::from($value);
            } elseif ($key === 'user' && !empty($value)) {
                $object->{$key} = new \Models\User();
                foreach ($value as $subKey => $subValue) {
                    if ($subKey === 'role') {
                        $object->{$key}->{$subKey} = Role::from($subValue);
                    } else {
                        $object->{$key}->{$subKey} = $subValue;
                    }
                }
            } else {
                $object->{$key} = $value;
            }
        }

        return $object;
    }
}
