<?php

namespace App\Http\Controllers\realUser;


use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class realTicketController extends Controller
{
    function index($contact_id = '')
    {
        $messages = [];
        if ($contact_id == "") {
            $contact_info = User::query()
                ->where('id', auth()->id())
                ->firstOrFail();
        } else {
            $contact_info = User::query()
                ->where('id', $contact_id)
                ->firstOrFail();

            Ticket::query()
                ->orWhere('from', $contact_id)
                ->where('to', auth()->id())
                ->update([
                    'seen' => 1
                ]);

            $messages = Ticket::query()
                ->select("*", "tickets.created_at as tickets_created_at", "tickets.id as ticket_id")
                ->join('users', 'users.id', '=', 'tickets.to')
                ->where('from', auth()->id())
                ->where('to', $contact_id)
                ->orWhere('from', $contact_id)
                ->where('to', auth()->id())
                ->get();

            foreach ($messages as $message) {
                $data = date("Y-m-d", strtotime($message['tickets_created_at']));
                $time = date("H:i", strtotime($message['tickets_created_at']));
                $message['jalali_date'] = $this->convertDateToJalali($data) . " " . $time;
            }
        }

        $users = User::query()
            ->where('id', "!=", auth()->id())
            ->get();

        foreach ($users as $user) {
            $user['roles'] = $this->getUserRoles($user['id']);
            $user['new_messages'] = $this->check_new_message($user['id']);
        }

        return view('realUser.tickets.index', compact("users", "contact_id", "contact_info", "messages"));
    }

    function check_new_message($user_id)
    {
        $message_count = Ticket::query()
            ->where('from', $user_id)
            ->where('to', auth()->id())
            ->where('seen', 0)
            ->count();

        return $message_count;
    }

    function download($ticket_id)
    {
        $ticket = Ticket::query()
            ->where('id', $ticket_id)
            ->firstOrFail();

        if (file_exists($ticket['file_src']) and !is_dir($ticket['file_src'])) {
            return response()->download($ticket['file_src']);
        } else {
            return abort(404);
        }
    }

    function getUserRoles($user_id)
    {
        $roles = Role::query()
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_id', $user_id)
            ->get();

        $final = [];

        foreach ($roles as $role) {
            array_push($final, $role['title']);
        }

        return $final;
    }

    function send(Request $request, $contact_id)
    {
        User::query()
            ->where('id', $contact_id)
            ->firstOrFail();

        $input = $request->all();

        if (!$request->has('file')) {
            $validation = Validator::make($input, [
                'content' => "required|string|max:3000"
            ]);

            if ($validation->fails()) {
                alert()->error($validation->errors()->first(), 'خطا')->autoclose(9000);
                return back();
            }
        }

        if ($request->has('file')) {
            $validation = Validator::make($input, [
                'file' => "required|mimes:jpg,png,jpeg,pdf,doc,docx|max:20480"
            ]);

            if ($validation->fails()) {
                alert()->error($validation->errors()->first(), 'خطا')->autoclose(9000);
                return back();
            }
        }

        $ticket = Ticket::create([
            'from' => auth()->id(),
            'to' => $contact_id,
            'content' => $input['content'],
        ]);

        if ($request->has('file')) {
            $img_name = time() . $request->file('file')->getClientOriginalName();
            $ext = time() . $request->file('file')->getClientOriginalExtension();
            $ticket->update([
                'file_src' => $request->file('file')->move('tickets', $img_name),
                'has_file' => 1,
                'ext' => $ext,
            ]);
        }

        alert()->success("پیام شما با موفقیت ارسال شد", 'با تشکر')->autoclose(9000);
        return back();
    }

    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }
}
