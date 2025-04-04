<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
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
}
