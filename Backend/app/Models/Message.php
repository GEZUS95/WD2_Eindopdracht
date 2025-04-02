<?php

namespace Models;

use JsonSerializable;

class Message implements JsonSerializable
{
    public string $id;
    public string $ticket_id;
    public string $message;
    public ?array $attachments = null;
    public User $user;
    public string $created_at;

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'message' => $this->message,
            'attachments' => $this->attachments,
            'user' => $this->user,
            'created_at' => $this->created_at
        ];
    }
}
