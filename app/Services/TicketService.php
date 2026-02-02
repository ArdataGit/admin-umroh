<?php

namespace App\Services;

use App\Models\Ticket;

class TicketService
{
    public function getAll()
    {
        return Ticket::with('maskapai')->get();
    }

    public function getById($id)
    {
        return Ticket::with('maskapai')->find($id);
    }

    public function create($data)
    {
        return Ticket::create($data);
    }

    public function update($id, $data)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->update($data);
            return $ticket;
        }
        return null;
    }

    public function delete($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->delete();
            return true;
        }
        return false;
    }
}
