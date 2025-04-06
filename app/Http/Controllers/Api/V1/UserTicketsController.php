<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserTicketsController extends ApiController
{
    public function index($user_id, TicketFilter $filter)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $user_id)
                ->filter($filter)
                ->get()
        );
    }

    public function store($user_id, StoreTicketRequest $request)
    {
        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    public function destroy($user_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $user_id) {
                $ticket->delete();

                return $this->ok('Ticket successfully deleted', []);
            }
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found.', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, $user_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrfail($ticket_id);

            if ($ticket->user_id == $user_id) {
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
            }
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }

    public function update(UpdateTicketRequest $request, $user_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrfail($ticket_id);

            if ($ticket->user_id == $user_id) {
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
            }
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket can not be found', 404);
        }
    }
}
