<?php

namespace Controllers;

use Helpers\Middleware;
use Models\Ticket;
use Services\ticketService;

class TicketController extends Controller
{
    private ticketService $service;
    private Middleware $middleware;

    public function __construct()
    {
        $this->service = new ticketService();
        $this->middleware = new Middleware();
    }

    public function getAll($lim = 50, $off = 0): void
    {
        $this->middleware->auth(\Enums\Role::User);
        $tickets = $this->service->getAll($lim, $off);
        if (empty($tickets)) {
            $this->respondWithError(404, "No tickets found");
            return;
        }
        $this->respond($tickets);
    }

    public function get($id): void
    {
        $this->middleware->auth(\Enums\Role::User);
        $ticket = $this->service->getOneById($id);
        $this->respond($ticket);
    }

    public function create(): void
    {
        $this->middleware->auth(\Enums\Role::User);
        $user = $this->middleware->getUserFromJWT();
        $ticket = $this->createObjectFromPostedJson(Ticket::class);
        $ticket->user = $user;
        $res = $this->service->create($ticket);
        $this->respond($res);
    }

    public function update($id): void
    {
        $this->middleware->auth(\Enums\Role::User);
        $post = $this->createObjectFromPostedJson(Ticket::class);
        $post->id = $id;
        $ticket = $this->service->update($post);
        $this->respond($ticket);
    }

    public function delete($id): void
    {
        $this->middleware->auth(\Enums\Role::Admin);
        $this->service->delete($id);
        $this->respond("The ticket with id '$id' has been deleted");
    }

    public function setResolved($id): void
    {
        $this->middleware->auth(\Enums\Role::Admin);
        $this->service->setResolved($id);
        $this->respond("The ticket with id '$id' has been resolved");
    }

    public function addMessage($message): void
    {
        $this->middleware->auth(\Enums\Role::User);
        $this->service->addMessage($message);
        $this->respond("The message has been added to the ticket");
    }

    public function getMessages($ticket_id): void
    {
        $this->middleware->auth(\Enums\Role::User);
        $messages = $this->service->getMessages($ticket_id);
        $this->respond($messages);
    }

    public function deleteMessage($id): void
    {
        $this->middleware->auth(\Enums\Role::User);
        $this->service->deleteMessage($id);
        $this->respond("The message with id '$id' has been deleted");
    }


}
