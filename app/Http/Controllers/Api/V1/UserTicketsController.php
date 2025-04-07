<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserTicketsController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index(TicketFilter $filter, $user_id)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $user_id)
                ->filter($filter)
                ->get()
        );
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            $this->isAble('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes([
                'user' => 'user_id',
            ])));
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to store this resource', 401);
        }
    }

    public function destroy($user_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $this->isAble('delete', $ticket);
            $ticket->delete();

            return $this->ok('Ticket successfully deleted', []);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete this resource', 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $user_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $this->isAble('replace', $ticket);
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    public function update(UpdateTicketRequest $request, $user_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $this->isAble('update', $ticket);
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }
}
