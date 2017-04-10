<?php

namespace App\Http\Controllers\Utilities;

use App\OpenTicket;
use App\OpenTicketDetail;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public function sendEmailReminder()
    {
        $user = User::findOrFail(1);
        $ticket = OpenTicket::findOrFail(1);
        $ticketMessage = OpenTicketDetail::findOrFail(1);
        Mail::send('utilities.email', ['ticketMessage' => $ticketMessage], function ($m) use($user,$ticket)  {
            $m->from('hello@app.com', 'Your Application');

            $m->to($user->email)->subject($ticket->title);
        });

    }
}
