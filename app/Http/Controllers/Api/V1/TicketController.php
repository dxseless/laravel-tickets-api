<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->get());
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::findOrfail($request->input('data.relationships.user.data.id'));
        } catch (ModelNotFoundException $exception) {
            return $this->ok('User not fount', [
                'error' => 'The provided user does not exist',
            ]);
        }

        return new TicketResource($request->mappedAttributes());
    }

    public function show($ticket_id)
    {
        try {
            $ticket = Ticket::findOrfail($ticket_id);

            if ($this->include('user')) {
                return new TicketResource($ticket->load('user'));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found.', 404);
        }
    }

    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrfail($ticket_id);

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrfail($ticket_id);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }

    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();

            return $this->ok('Ticket successfully deleted', []);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found.', 404);
        }
    }
}
