<?php

namespace App\Http\Controllers\head_special;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveStatus;
use App\Models\Role;
use App\Models\RoleTitle;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class leaveController extends Controller
{
    public function index(){
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $leaves = Leave::query()
            ->where('user_id', $user_id)
            ->paginate(10);

        foreach ($leaves as $leave) {
            $leave['start_day'] = verta($leave->start_day)->format('d/%B/Y');
            $leave['end_day'] = verta($leave->end_day)->format('d/%B/Y');
            $leave['status'] = LeaveStatus::query()
                ->where('id',$leave->status)
                ->first();
        }

        $user_img = $user_info->image;
        $searched = false;
        return view('headSpecial.leave.index', compact('user_img', 'user_info', 'leaves', 'searched'));

    }

    public function Confirmation()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $leaves = Leave::query()
            ->select('*','leave.id as leave_id')
            ->join('users','users.id','=','leave.user_id')
            ->where('parent',12)
            ->where('confirmation',0)
            ->where('main_manager_approval',0)
            ->paginate();

        foreach ($leaves as $leave) {
            $leave['start_day'] = verta($leave->start_day)->format('d/%B/Y');
            $leave['end_day'] = verta($leave->end_day)->format('d/%B/Y');
            $leave['leave_user_info'] = User::query()
                ->where('id',$leave['user_id'])
                ->first();

            $leave['status'] = LeaveStatus::query()
                ->where('id',$leave->status)
                ->first();

            $roles_info = Role::query()
                ->where('user_id',$leave['leave_user_info']['id'])
                ->get();

            foreach ($roles_info as $role_info){
                $leave['roles'] = RoleTitle::query()
                    ->where('id',$role_info['roles'])
                    ->get();
            }

        }

        $user_img = $user_info->image;
        $searched = false;

        return view('headSpecial.leave.confirmation', compact('user_img', 'user_info', 'leaves', 'searched'));
    }

    public function create()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();
        $role_title = '???????? ???????? ???????????? ????????';
        $user_img = $user_info->image;
        return view('headSpecial.leave.create', compact('user_img', 'user_info','role_title'));
    }

    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $input = $request->all();

        if ($input['type_select_leave'] == 1) {
            $start_day = $this->convertDateToGregorian($input['start_day']);
            $end_day = $this->convertDateToGregorian($input['end_day']);
            Leave::create([
                'user_id' => $user_id,
                'parent' => 5,
                'day_leave_count' => $input['day_leave_count'],
                'hour_leave_count' => $input['hour_leave_count'],
                'type' => $input['type_select_leave'],
                'start_hour' => $input['start_hour'],
                'end_hour' => $input['end_hour'],
                'start_day' => $start_day,
                'end_day' => $end_day,
                'confirmation' => 0, // 1 is confirm , 0 is disapproval
                'disapproval_reason' => null,
                'main_manager_approval' => 0, //The manager who disapproved
                'finance_manager_approval' => 0, //The finance manager who disapproved
                'status'=>3
            ]);
        } elseif ($input['type_select_leave'] == 2) {
            $start_day_daily = $this->convertDateToGregorian($input['start_day_daily']);
            $end_day_daily = $this->convertDateToGregorian($input['end_day_daily']);

            Leave::create([
                'user_id' => $user_id,
                'parent' => 5,
                'day_leave_count' => $input['day_leave_count'],
                'hour_leave_count' => $input['hour_leave_count'],
                'type' => $input['type_select_leave'],
                'start_hour' => $input['start_hour'],
                'end_hour' => $input['end_hour'],
                'start_day' => $start_day_daily,
                'end_day' => $end_day_daily,
                'confirmation' => 0, // 1 is confirm , 0 is disapproval
                'disapproval_reason' => null,
                'main_manager_approval' => 0, //The manager who disapproved
                'finance_manager_approval' => 0, //The finance manager who disapproved
                'status'=>3
            ]);
        }


        alert()->success('?????????????? ?????????? ?????? ?????? ?? ???? ???????? ???????????? ?????? ?????????? ???????? ????', '???? ????????')->autoclose(9000);

        return redirect()->route('leave_head_special_index');
    }

    function convertDateToGregorian($date)
    {

        $date = explode('/', $date);
        $date = Verta::getGregorian($this->convertDigitsToEnglish($date[0]), $this->convertDigitsToEnglish($date[1]), $this->convertDigitsToEnglish($date[2]));
        return join('-', $date);
    }

    function convertDigitsToEnglish($string)
    {
        $persian = ['??', '??', '??', '??', '??', '??', '??', '??', '??', '??'];
        $arabic = ['??', '??', '??', '??', '??', '??', '??', '??', '??', '??'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    function agreement(Request  $request)
    {
        $input = $request->all();

        $leave_info = Leave::query()
            ->where('id',$input['leave_id'])
            ->first();

        $leave_info->update([
            'main_manager_approval' => 1,
            'status'=>4,
        ]);

        return response()->json([
            'status' => true,
            'message' => '?????????? ?????????? ???????? ?????? ?????????? ????',
        ]);
    }
    function disagreement(Request $request)
    {
        $input = $request->all();

        $leave_info = Leave::query()
            ->where('id',$input['leave_id'])
            ->first();

        $leave_info->update([
            'main_manager_approval' => 2,
            'status' => 2,
        ]);

        return response()->json([
            'status' => true,
            'message' => '?????????? ?????????? ???????? ?????? ?????????? ??????',
        ]);

    }
}
