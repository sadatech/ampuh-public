<?php

namespace App\Http\Controllers\Utilities;

use App\OpenTicket;
use App\OpenTicketDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticket = OpenTicket::with('user')->where('user_id', @Auth::id())->orderBy('id', 'desc')->get();
        return view('utilities.support', compact("ticket"));
    }

    public function staff()
    {
        return view('utilities.support_staff');
    }

    public function detail($id)
    {
        $ticket = OpenTicket::with('user', 'detail')->find($id);
        return view('utilities.support_detail', compact('ticket'));
    }

    public function replyMessage(Request $request, $id)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);
        $fillablereply = $request->all();
        $fillablereply['ticket_id'] = $id;
        $fillablereply['attachment'] = '';
        $fillablereply['status'] = '';
        $fillablereply['url'] = 'utilities/detail/' . $id;
        //jika developer yang jawab pasti update status
        if ($fillablereply['role'] == '0') {
            $replydeveloper = OpenTicket::find($id);
            if ($request->due_date != null) {
                $due_date = Carbon::parse($request->due_date)->toDateString();
                $replydeveloper->due_date = $due_date;
            }
            $replydeveloper->status = $request->status;
            $replydeveloper->save();
        }
        if ($request->attachment != '') {
            $foto_attach = $request->file('attachment');
            $foto_attach_orig = time() . '-' . $foto_attach->getClientOriginalName();
            $foto_attach->move('attachment/support', $foto_attach_orig);
            $fillablereply['attachment'] = $foto_attach_orig;
        }
        if ($fillablereply['status'] == 'Reopen') {
            $subject = @Auth::user()->name . " Reopen Ticket";
            $reopen = OpenTicket::find($id);
            $reopen->status = 'Reopen';
            $reopen->save();
        } else {
            $subject = @Auth::user()->name . " Reply a Message";
        }

        if (@Auth::user()->role != 'developer') {
            $developer = User::where('role', 'developer')->get();
            foreach ($developer as $key => $value) {
                Mail::send('utilities.email', ['ticketMessage' => $fillablereply], function ($m) use ($value, $request, $subject) {
                    $m->from(@Auth::user()->email, @Auth::user()->role . $subject);
                    $m->to($value->email)->subject($subject);
                });
            }
        }

        DB::transaction(function () use ($fillablereply) {
            OpenTicketDetail::create($fillablereply);
            \Session::flash('reply', 'yes');
        });

        if (@Auth::user()->role == 'developer' && $fillablereply['status'] != null && $fillablereply['status'] == 'Completed') {
            return redirect()->to('utilities/support');
        } else {
            return redirect()->to('utilities/detail/' . $id);
        }


    }


    public function dataReplyMessage($id)
    {
        $data = OpenTicketDetail::find($id);
        return response()->json($data);
    }

    public function editReplyMessage(Request $request, $id, $idticket)
    {
        $message = OpenTicketDetail::find($id);
        $message->message = $request->message;
        if ($request->attachment != '') {
            $foto_attach = $request->file('attachment');
            $foto_attach_orig = time() . '-' . $foto_attach->getClientOriginalName();
            $foto_attach->move('attachment/support', $foto_attach_orig);
            $message->attachment = $foto_attach_orig;
        }
        $message->save();
        \Session::flash('edit', 'yes');
        return redirect()->to('utilities/detail/' . $idticket);
    }

    public function reOPenTicket($id)
    {
        $reopen = OpenTicket::find($id);
        $reopen->status = 'Reopen';
        $reopen->save();
        return response()->json($reopen);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
            'attachment' => 'mimes:jpeg,bmp,png'
        ]);
        $fillableTicket = $request->all();
        $fillableTicket['status'] = 'New';
        $fillableTicket['user_id'] = Auth::id();
        $fillableTicket['attachment'] = '';
        if ($request->attachment != '') {
            $foto_attach = $request->file('attachment');
            $foto_attach_orig = time() . '-' . $foto_attach->getClientOriginalName();
            $foto_attach->move('attachment/support', $foto_attach_orig);
            $fillableTicket['attachment'] = $foto_attach_orig;
        }

        DB::transaction(function () use ($fillableTicket, $request) {
            $ticket = OpenTicket::create($fillableTicket);
            $fillableTicket['ticket_id'] = $ticket->id;
            $fillableTicket['url'] = 'utilities/detail/' . $ticket->id;
            $fillableTicket['role'] = true;
            OpenTicketDetail::create($fillableTicket);
            $developer = User::where('role', 'developer')->get();
            foreach ($developer as $key => $value) {
                Mail::send('utilities.email', ['ticketMessage' => $fillableTicket], function ($m) use ($value, $request) {
                    $m->from(@Auth::user()->email, 'Open Ticket From ' . @Auth::user()->role);
                    $m->to($value->email)->subject($request->title);
                });
            }
            \Session::flash('message', 'Ticket Berhasil di kirim');
        });

        return redirect()->to('utilities/support');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request)
    {
        $archived = $request->exists('archived');
        $link = ($archived) ? 'Restore' : 'Archive';
        $sym = ($archived) ? 'fa-undo' : 'fa-archive';

        if (@Auth::user()->role == 'developer') {
            $ticket = OpenTicket::with('user')->orderBy('id', 'desc')->where('status', '!=', 'Completed');
        } else {
            $ticket = OpenTicket::with('user')->where('user_id', @Auth::id())->orderBy('id', 'desc');
        }
        if ($archived) {
            $ticket->onlyTrashed();
        }

        return Datatables::of($ticket)
            ->addColumn('cek', "<label class='mt-checkbox mt-checkbox-single mt-checkbox-outline'>
                                                    <input type='checkbox' class='checkboxes' value='1'/>
                                                    <span></span>
                                                </label>")
            ->editColumn('status', function ($item) {
                switch ($item->status) {
                    case 'New':
                        return "<span class=\"label label-sm label-warning\"> New </span>";
                    case 'Processed':
                        return "<span class=\"label label-sm label-info\"> Processed </span>";
                    case 'Completed':
                        return "<span class=\"label label-sm label-success\"> Completed </span>";
                    case 'Pending':
                        return "<span class=\"label label-sm label-default\"> Pending </span>";
                    case 'Reopen':
                        return "<span class=\"label label-sm label-danger\"> Reopen </span>";

                }
            })
            ->editColumn('title', '<a href="/utilities/detail/{{$id}}">{{$title}}</a>')
            ->editColumn('user.email', function ($item) {
                return '<a href="mailto:"' . $item['user']['email'] . '>' . $item['user']['email'] . '</a>';
            })
            ->editColumn('created_at', function ($item) {
                return $this->readableDateFormat($item->created_at);
            })
            ->editColumn('due_date', function ($item) {
                return $this->readableDateFormat($item->due_date);
            })
            ->addColumn('archive', '<a href="#" id="{{ $id }}" onclick="' . $link . '(this.id)" class="btn btn-danger">' . $link . ' <i class="fa ' . $sym . '"></i> </a>')
            ->make(true);
    }

    public function destroy($id)
    {
        $ticket = OpenTicket::find($id)->delete();
        return response()->json('success');

    }

    public function restore($id)
    {
        $ticket = OpenTicket::withTrashed()->find($id)->restore();
        return response()->json('success');
    }


    public function datatablestaff()
    {
        $user = User::get()->where('role', 'developer');
        return Datatables::of($user)
            ->withTrashed()
            ->addColumn('cek', "<label class='mt-checkbox mt-checkbox-single mt-checkbox-outline'>
                                                    <input type='checkbox' class='checkboxes' value='1'/>
                                                    <span></span>
                                                </label>")
            ->editColumn('email', '<a href="mailto:{{$email}}"> {{$email}}</a>')
            ->editColumn('created_at', function ($item) {
                return $this->readableDateFormat($item->created_at);
            })
            ->make(true);
    }

    private function readableDateFormat($date)
    {
        return Carbon::parse($date)->format('d-M-y H:i:s');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
