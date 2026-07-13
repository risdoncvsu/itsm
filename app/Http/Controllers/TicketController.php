<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        // This loads the resources/views/tickets/create.blade.php file
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket in the database.
     */
    public function store(Request $request)
    {
        // For now, let's just dump the data to the screen so you can verify 
        // that the form is sending the correct inputs, including the status (draft/open).
        dd($request->all());

        // Later, you will add your validation and database insertion logic here.
        // e.g., Ticket::create($request->validate([...]));
        
        // return redirect()->route('tickets.create')->with('success', 'Ticket saved!');
    }
}