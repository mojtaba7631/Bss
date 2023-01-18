<?php

namespace App\Http\Controllers\employer;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\LetterAccess;
use App\Models\LetterContacts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class employerLetterController extends Controller
{
    function sent_index()
    {
        $letters = Letter::query()
            ->where('user_id', auth()->id())
            ->where('has_signature', 1)
            ->paginate(30);

        // remove old letters without signature
        Letter::query()
            ->where('user_id', auth()->id())
            ->where('has_signature', 0)
            ->whereDate('created_at', '<=', Carbon::today()->subDay())
            ->delete();

        foreach ($letters as $letter) {
            $letter['jalali_date'] = verta($letter['updated_at'])->format('d %B Y');
        }

        return view('employer.letter.index', compact('letters'));
    }

    function delivered_index()
    {
        $letters = Letter::query()
            ->select('*', 'letters.user_id as sender_id')
            ->join('letter_contacts', 'letter_contacts.letter_id', '=', 'letters.id')
            ->join('users', 'users.id', '=', 'letters.user_id')
            ->where('letter_contacts.user_id', auth()->id())
            ->where('has_signature', 1)
            ->where('sent', 1)
            ->paginate(30);

        // remove old letters without signature
        Letter::query()
            ->where('user_id', auth()->id())
            ->where('has_signature', 0)
            ->whereDate('created_at', '<=', Carbon::today()->subDay())
            ->delete();

        foreach ($letters as $letter) {
            $letter['jalali_date'] = verta($letter['updated_at'])->format('d %B Y');
        }

        return view('employer.letter.delivered', compact('letters'));
    }

    function new()
    {
        $contacts = LetterAccess::query()
            ->join('users', 'users.id', '=', 'letter_access.contact')
            ->where('user_id', auth()->id())
            ->get();

        return view('employer.letter.new', compact('contacts'));
    }

    function signature(Request $request)
    {
        $input = $request->all();

        $mobile = '09215215298';

        $validation = Validator::make($input, [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:50000',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => true,
                'errorMsg' => $validation->errors()->first(),
            ]);
        }

        $code = rand(10000, 99999);
        $message = 'کد تایید نامه در سامانه نما: ' . $code;
        $sign_sms = sms($mobile, $message);

        if ($sign_sms) {
            $letter = Letter::create([
                'title' => $input['title'],
                'content' => $input['content'],
                'has_signature' => 0,
                'confirm_code' => $code,
                'user_id' => auth()->id(),
            ]);

            $contacts = json_decode($request->contacts);

            foreach ($contacts as $contact) {
                LetterContacts::create([
                    'letter_id' => $letter->id,
                    'user_id' => $contact,
                    'seen' => 0,
                ]);
            }

            return response()->json([
                'error' => false,
                'errorMsg' => 'کد تایید را وارد نمایید',
                'letter_id' => $letter->id
            ]);
        } else {
            return response()->json([
                'error' => true,
                'errorMsg' => 'ارسال کد تایید با خطا مواجه شد، لطفا مجددا تلاش نمایید.',
            ]);
        }
    }

    function add(Request $request)
    {
        $input = $request->all();
        $code = $input['code'];
        $letter_id = $input['letter_id'];
        $title = $input['title'];
        $content = $input['content'];

        $letter_info = Letter::query()
            ->where('id', $letter_id)
            ->first();

        if ($letter_info) {
            if ($letter_info['confirm_code'] == $code) {
                $letter_info->update([
                    'confirm_code' => 0,
                    'has_signature' => 1,
                    'title' => $title,
                    'content' => $content,
                ]);

                return response()->json([
                    'error' => false,
                    'errorMsg' => 'نامه با موفقیت امضا شد',
                    'location' => route('employer_letter_preview', ['letter_id' => $letter_id]),
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'errorMsg' => 'کد تایید صحیح نیست',
                    'refresh' => false,
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'errorMsg' => 'خطایی رخ داده است لطفا مجددا تلاش تمایید',
                'refresh' => true,
            ]);
        }
    }

    function preview($letter_id)
    {
        $letter_info = Letter::query()
            ->select('*', 'letters.id as letter_id')
            ->join('users', 'users.id', 'letters.user_id')
            ->where('letters.id', $letter_id)
            ->where('letters.user_id', auth()->id())
            ->firstOrFail();

        return view('employer.letter.letter_preview', compact('letter_info'));
    }

    function view($letter_id)
    {
        $letter_info = Letter::query()
            ->select('*', 'letters.id as letter_id')
            ->join('users', 'users.id', 'letters.user_id')
            ->where('letters.id', $letter_id)
            ->firstOrFail();

        LetterContacts::query()
            ->where('letter_id', $letter_id)
            ->where('user_id', auth()->id())
            ->update([
                'seen' => 1
            ]);

        return view('employer.letter.letter_preview', compact('letter_info'));
    }

    function final_submit($letter_id)
    {
        $letter_info = Letter::query()
            ->where('letters.id', $letter_id)
            ->firstOrFail();

        $letter_info->update([
            'sent' => 1
        ]);

        alert()->success('نامه با موفقیت ارسال شد.');
        return redirect()->route('employer_letter_sent_index');
    }
}
