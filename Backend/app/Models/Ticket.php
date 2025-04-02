<?php

namespace Models;

use Enums\Priority;
use Enums\Status;
use JsonSerializable;

class Ticket implements JsonSerializable
{
    public string $id;
    public string $title;
    public string $description;
    public ?array $attachments = null; // table ticket_attachment
    public Status $status;
    public Priority $priority;

    public User $user;
    public array $assigned_to; // table ticket_user
    public ?string $resolved_at = null;

    public string $created_at;
    public string $updated_at;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'attachments' => $this->attachments,
            'status' => $this->status,
            'priority' => $this->priority,
            'user' => $this->user,
            'assigned_to' => $this->assigned_to,
            'resolved_at' => $this->resolved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
