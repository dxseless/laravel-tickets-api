<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;

class UserTicketsController extends Controller
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
        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $user_id,
        ];

        return new TicketResource(Ticket::create($model));
    }
}
