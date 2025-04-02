<?php

namespace Models;

class Message
{
    public string $id;
    public string $ticket_id;
    public string $message;
    public array $attachments;
    public string $user_id;
    public string $created_at;
}
