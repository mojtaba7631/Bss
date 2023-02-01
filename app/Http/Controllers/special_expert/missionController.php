<?php

namespace App\Http\Controllers\special_expert;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveStatus;
use App\Models\Mission;
use App\Models\MissionStatus;
use App\Models\Role;
use App\Models\RoleTitle;
use Illuminate\Http\Request;
use App\Models\User;
use Hekmatinasser\Verta\Verta;

class missionController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $missions = Mission::query()
            ->where('user_id', $user_id)
            ->paginate(10);

        foreach ($missions as $mission) {
            $mission['start_day'] = verta($mission->start_day)->format('d/%B/Y');
            $mission['end_day'] = verta($mission->end_day)->format('d/%B/Y');
            $mission['status'] = MissionStatus::query()
                ->where('id', $mission->status)
                ->first();
        }

        $user_img = $user_info->image;

        $searched = false;
        return view('special_expert.mission.index', compact('user_img', 'user_info', 'missions', 'searched'));

    }

    public function create()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $role_title = 'کارشناس ارشد مرکز عملیات';
        $job = 'کارشناس';
        $center_title = 'عملیات ویژه';
        $user_img = $user_info->image;
        return view('special_expert.mission.create', compact('user_img', 'user_info','role_title','center_title','job'));
    }

    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $input = $request->all();

        if ($input['type_select_leave'] == 1) {
            $start_day = $this->convertDateToGregorian($input['start_day']);
            $end_day = $this->convertDateToGregorian($input['end_day']);
            Mission::create([
                'user_id' => $user_id,
                'parent' => 12,
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

            Mission::create([
                'user_id' => $user_id,
                'parent' => 12,
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


        alert()->success('درخواست ماموریت شما ثبت و به مدیر مستقیم شما ارجاع داده شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('special_expert_mission_index');
    }

    public function Confirmation()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $missions = Mission::query()
            ->select('*','mission.id as mission_id')
            ->join('users','users.id','=','mission.user_id')
            ->where('parent',19)
            ->where('confirmation',0)
            ->where('main_manager_approval',0)
            ->paginate();

        foreach ($missions as $mission) {
            $mission['start_day'] = verta($mission->start_day)->format('d/%B/Y');
            $mission['end_day'] = verta($mission->end_day)->format('d/%B/Y');
            $mission['leave_user_info'] = User::query()
                ->where('id',$mission['user_id'])
                ->first();

            $mission['status'] = MissionStatus::query()
                ->where('id',$mission->status)
                ->first();

            $roles_info = Role::query()
                ->where('user_id',$mission['leave_user_info']['id'])
                ->get();

            foreach ($roles_info as $role_info){
                $mission['roles'] = RoleTitle::query()
                    ->where('id',$role_info['roles'])
                    ->get();
            }

        }

        $user_img = $user_info->image;
        $searched = false;

        return view('special_expert.leave.confirmation', compact('user_img', 'user_info', 'missions', 'searched'));
    }

    function convertDateToGregorian($date)
    {

        $date = explode('/', $date);
        $date = Verta::getGregorian($this->convertDigitsToEnglish($date[0]), $this->convertDigitsToEnglish($date[1]), $this->convertDigitsToEnglish($date[2]));
        return join('-', $date);
    }

    function convertDigitsToEnglish($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }


}
