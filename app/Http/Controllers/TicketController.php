<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TicketController extends Controller
{
    public function ticketIndex()
    {
        $all_ticket =Ticket::where('customer_id', Auth::user()->id)
           ->orderBy('id', 'desc')->paginate(5);
        return view('user_panel.support.support' , compact('all_ticket'));
    }

    public function ticketCreate()
    {

        return view('user_panel.support.add_ticket');
    }

    public function ticketStore(Request $request)
    {
        $this->validate($request, [
            'subject' =>'required',
            'detail' => 'required'
        ]);

        $a = strtoupper(md5(uniqid(rand(), true)));

        $ticket = Ticket::create([
           'subject' => $request->subject,
            'ticket' => substr($a, 0, 8),
            'customer_id' => Auth::user()->id,
            'status' => 1,
        ]);

        TicketComment::create([
           'ticket_id' => $ticket->ticket,
           'type' => 1,
           'comment' => $request->detail,
        ]);

        Session::flash('message', 'Successfully Created Ticket');
        return redirect()->route('ticket.customer.reply',$ticket->ticket);


    }

    public function ticketReply($ticket)
    {
        $ticket_object = Ticket::where('customer_id', Auth::user()->id)
            ->where('ticket', $ticket)->first();
        $ticket_data = TicketComment::where('ticket_id', $ticket)->get();

        if ($ticket_object  == '')
        {
            return back();
        }else{
            return view('user_panel.support.view_reply', compact('ticket_data', 'ticket_object'));
        }
    }

    public function ticketReplyStore(Request $request, $ticket)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);

        TicketComment::create([
            'ticket_id' => $ticket,
            'type' => 1,
            'comment' => $request->comment,
        ]);

        Ticket::where('ticket', $ticket)
            ->update([
               'status' => 3
            ]);

        return redirect()->back()->with('message', 'Message Send Successful');
    }

    public function indexSupport()
    {
        $all_ticket = Ticket::orderBy('id', 'desc')->paginate(20);
        $page_title  = "Support Tickets";
        return view('admin.support.support', compact('all_ticket', 'page_title'));
    }

    public function adminSupport($ticket)
    {
        $page_title  = "Support Reply";
        $ticket_object = Ticket::where('ticket', $ticket)->first();
        $ticket_data = TicketComment::where('ticket_id', $ticket)->get();
        return view('admin.support.view_reply', compact('ticket_object', 'ticket_data', 'page_title'));
    }

    public function adminReply(Request $request, $ticket)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);

        TicketComment::create([
            'ticket_id' => $ticket,
            'type' => 0,
            'comment' => $request->comment,
        ]);

        Ticket::where('ticket', $ticket)
            ->update([
                'status' => 2
            ]);

        return redirect()->back()->with('message', 'Message Send Successful');

    }

    public function ticketClose($ticket)
    {
        Ticket::where('ticket', $ticket)
            ->update([
                'status' => 9
            ]);
        return redirect()->back()->with('message', 'Conversation closed, But you can start again');
    }

    public function pendingTicketAdmin()
    {
        $all_ticket = Ticket::where('status',1)->orWhere('status' ,3)->paginate(20);
        $page_title  = "Support Tickets";
        return view('admin.support.support', compact('all_ticket', 'page_title'));
    }


}
