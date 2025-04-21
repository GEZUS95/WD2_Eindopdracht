<?php

namespace Controllers;

use Enums\Role;
use Helpers\Middleware;
use Models\Message;
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
        $this->middleware->auth([Role::User, Role::Support_Agent, Role::Admin]);
        $tickets = $this->service->getAll($lim, $off);
        if (empty($tickets)) {
            $this->respondWithError(404, "No tickets found");
            return;
        }
        $this->respond($tickets);
    }

    public function get($id): void
    {
        $this->middleware->auth([Role::User, Role::Support_Agent, Role::Admin]);
        $ticket = $this->service->getOneById($id);
        $this->respond($ticket);
    }

    public function create(): void
    {
        $this->middleware->auth([Role::User, Role::Support_Agent, Role::Admin]);
        $user = $this->middleware->getUserFromJWT();
        $ticket = $this->createObjectFromPostedJson(Ticket::class);
        $ticket->user = $user;
        $res = $this->service->create($ticket);
        $this->respond($res);
    }

    public function update($id): void
    {
        $this->middleware->auth([Role::Support_Agent, Role::Admin]);
        $post = $this->createObjectFromPostedJson(Ticket::class);
        $post->id = $id;
        $ticket = $this->service->update($post);
        $this->respond($ticket);
    }

    public function delete($id): void
    {
        $this->middleware->auth([Role::User, Role::Support_Agent, Role::Admin]);
        $this->service->delete($id);
        $this->respond("The ticket with id '$id' has been deleted");
    }

    public function setResolved($id): void
    {
        $this->middleware->auth([Role::Support_Agent, Role::Admin]);
        $this->service->setResolved($id);
        $this->respond("The ticket with id '$id' has been resolved");
    }

    public function addMessage($id): void
    {
        $this->middleware->auth([Role::User, Role::Support_Agent, Role::Admin]);
        $message = $this->createObjectFromPostedJson(Message::class);
        $message->ticket_id = $id;
        $message->user = $this->middleware->getUserFromJWT();
        $this->service->addMessage($message);
        $this->respond("The message has been added to the ticket");
    }

    public function getMessages($ticket_id): void
    {
        $this->middleware->auth([Role::User, Role::Support_Agent, Role::Admin]);
        $messages = $this->service->getMessages($ticket_id);
        $this->respond($messages);
    }


}
