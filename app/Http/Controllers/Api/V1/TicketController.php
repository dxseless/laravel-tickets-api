<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
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

        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $request->input('data.relationships.user.data.id'),
        ];

        return new TicketResource(Ticket::create($model));
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

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
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
