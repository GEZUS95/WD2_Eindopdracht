<?php

namespace Repositories;

use Models\Message;
use Models\Ticket;
use Models\User;
use Services\UserService;

class ticketRepository extends Repository
{
    private UserService $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function getAll(int $lim, int $off)
    {
        $stmt = $this->connection->prepare(" SELECT t.*, u.username, u.email FROM dev.tickets t JOIN dev.users u ON t.user_id = u.id ORDER BY created_at DESC LIMIT :lim OFFSET :off");
        $stmt->bindParam(':lim', $lim, \PDO::PARAM_INT);
        $stmt->bindParam(':off', $off, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Ticket::class);
    }

    public function getOneById(string $id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM dev.tickets WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch();
        if ($data) {
            $ticket = new Ticket();
            $ticket->id = $data['id'];
            $ticket->title = $data['title'];
            $ticket->description = $data['description'];
            $ticket->status = $data['status'];
            $ticket->priority = $data['priority'];
            $ticket->user = $this->userService->get($data['user_id']);
            $ticket->assigned_to = $this->getUsers($data['id']);
            $ticket->resolved_at = $data['resolved_at'];
            $ticket->created_at = $data['created_at'];
            $ticket->updated_at = $data['updated_at'];
            return $ticket;
        }
        return null;
    }

    public function create(Ticket $ticket)
    {
        try {
            $stat = $ticket->status->toString();
            $stmt = $this->connection->prepare("INSERT INTO dev.tickets (id, title, description, status, user_id) VALUES (:id, :title, :description, :status, :user_id)");
            $stmt->bindParam(':id', $ticket->id);
            $stmt->bindParam(':title', $ticket->title);
            $stmt->bindParam(':description', $ticket->description);
            $stmt->bindParam(':status', $stat);
            $stmt->bindParam(':user_id', $ticket->user->id);
            $stmt->execute();
            
            if (!empty($ticket->assigned_to)) {  
                foreach ($ticket->assigned_to as $u) {
                    $stmt = $this->connection->prepare("INSERT INTO dev.ticket_user (ticket_id, user_id) VALUES (:ticket_id, :user_id)");
                    $stmt->bindParam(':ticket_id', $insertedTicket->id);
                    $stmt->bindParam(':user_id', $u->id);
                    $stmt->execute();
                }
            }
        } catch (\Exception $e) {
            // Handle exception (e.g., log the error, rethrow, return null, etc.)
            throw $e;
        }
    }

    public function update(Ticket $ticket)
    {
        $stmt = $this->connection->prepare("UPDATE dev.tickets SET title = :title, description = :description, status = :status, user_id = :user_id WHERE id = :id");
        $stmt->bindParam(':title', $ticket->title);
        $stmt->bindParam(':description', $ticket->description);
        $stmt->bindParam(':status', $ticket->status);
        $stmt->bindParam(':user_id', $ticket->user->id);
        $stmt->bindParam(':id', $ticket->id);
        $stmt->bindParam(':updated_at', $ticket->updated_at);
        $stmt->bindParam(':assigned', $ticket->assigned_to); // need multiple users

        $stmt->execute();

        // Verwijder bestaande relaties om fouten te vermijden
        $stmt = $this->connection->prepare("DELETE FROM dev.ticket_user WHERE ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket->id);
        $stmt->execute();

        // Voeg de nieuwe relaties toe
        foreach ($ticket->assigned_to as $user) {
            $stmt = $this->connection->prepare("INSERT INTO dev.ticket_user (ticket_id, user_id) VALUES (:ticket_id, :user_id)");
            $stmt->bindParam(':ticket_id', $ticket->id);
            $stmt->bindParam(':user_id', $user->id);
            $stmt->execute();
        }
        return $this->getOneById($ticket->id);
    }

    public function delete(string $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM dev.tickets WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function setResolved(string $id): void
    {
        $stmt = $this->connection->prepare("UPDATE dev.tickets SET resolved_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function addMessage(Message $message): void
    {
        $stmt = $this->connection->prepare("INSERT INTO dev.ticket_message (ticket_id, user_id, message) VALUES (:ticket_id, :user_id, :message)");
        $stmt->bindParam(':ticket_id', $message->ticket_id);
        $stmt->bindParam(':user_id', $message->user_id);
        $stmt->bindParam(':message', $message->message);
        $stmt->execute();
    }

    public function getMessages(string $ticket_id): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM dev.ticket_message WHERE ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Message::class);
    }

    public function deleteMessage(string $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM dev.ticket_message WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getUsers(string $ticket_id): array
    {
        $stmt = $this->connection->prepare("SELECT u.* FROM dev.ticket_user tu JOIN dev.users u ON tu.user_id = u.id WHERE tu.ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, User::class);
    }

}
