<?php

namespace Services;

use Enums\Status;
use Models\Message;
use Models\Ticket;
use Ramsey\Uuid\Uuid;
use Repositories\ticketRepository;

class ticketService
{
    private ticketRepository $repository;

    public function __construct()
    {
        $this->repository = new TicketRepository();
    }

    public function getAll(int $lim, int $off)
    {
        return $this->repository->getAll($lim, $off);
    }

    public function getOneById(string $id)
    {
        return $this->repository->getOneById($id);
    }

    public function create(Ticket $ticket)
    {
        $ticket->id = Uuid::uuid4()->toString();
        $ticket->status = Status::Unassigned;
        $this->repository->create($ticket);
        return $this->repository->getOneById($ticket->id);
    }

    public function update(Ticket $ticket)
    {
        return $this->repository->update($ticket);
    }

    public function delete(string $id)
    {
        $this->repository->delete($id);
    }

    public function setResolved(string $id)
    {
        $this->repository->setResolved($id);
    }

    public function addMessage(Message $message)
    {
        $message->id = Uuid::uuid4()->toString();
        $this->repository->addMessage($message);
    }

    public function getMessages(string $ticket_id)
    {
        return $this->repository->getMessages($ticket_id);
    }

    public function deleteMessage(string $ticket_id)
    {
        $this->repository->deleteMessage($ticket_id);
    }
}
