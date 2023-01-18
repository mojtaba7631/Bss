<?php

namespace App\Http\Controllers\realUser;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Role;
use App\Models\SaveTime;
use App\Models\User;
use App\Models\Report;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class contractController extends Controller
{
    public function index()
    {

    }

    public function add($project_id)
    {
        $project_info = Project::query()
            ->where('id', $project_id)
            ->firstOrFail();

        return view('realUser.contracts.add', compact('project_id', 'project_info'));
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $project_id = $request->project_id;

        $project_info = Project::query()
            ->where('id', $project_id)
            ->firstOrFail();

        $employer_id = $project_info->employer_id;
        $date_start_date = $project_info->start_date;

        $employer_info = User::query()
            ->where('id', $employer_id)
            ->first();

        $user_info = $project_info->user_id;

        $employer_name = $employer_info->name . ' ' . $employer_info->family;

        $project_title = $project_info->title;

        $user_info = User::query()
            ->where('id', $user_info)
            ->first();

        $user_name = $user_info->co_name;

        $date = verta($date_start_date)->format('Ymd');

        $unique_code = User::query()
            ->where('id', $employer_id)
            ->firstOrFail();

        $unique_code_word = $unique_code->unique_code;

        $project_unique_code_search = substr($date, 2, 6) . $unique_code_word . (intval($project_id) + 1000);
        $project_unique_code = substr($date, 2, 6) . ' ' . $unique_code_word . ' ' . (intval($project_id) + 1000);

        $project_info->update([
            'project_unique_code' => $project_unique_code,
            'project_unique_code_search' => $project_unique_code_search
        ]);

        $validation = Validator::make($input, [
            'project_id' => 'required|integer',
            'contract_cost' => 'required|integer',
            'start_date' => 'required|string',
            'total_day_count' => 'required|integer',
            'comment' => 'required|string',
            'required_outputs' => 'required|string',
            'phases' => 'required',
            'prepayment' => 'required|integer',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => true,
                'errorMsg' => $validation->errors(),
            ]);
        }

        $project = Project::query()
            ->where('id', $input['project_id'])
            ->firstOrFail();

        if ($request->has('proposal_file')) {
            $validation = Validator::make($input, [
                'proposal_file' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx,rar',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'error' => true,
                    'errorMsg' => $validation->errors(),
                ]);
            }

            $file_name = time() . $request->file('proposal_file')->getClientOriginalName();
            $project->update([
                'attachment' => $request->file('proposal_file')->move('files/attachments', $file_name),
            ]);
        }

        $project_start_date = $this->convertDateToGregorian($input['start_date']);
        $project_end_date = date('Y-m-d', strtotime($project_start_date . ' + ' . intval($input['total_day_count']) . ' days'));
        if ($project->confirmed_by_employer == 0) {
            $status = 3;
        } else {
            $status = 4;
        }


        $retry = 1;
        while($retry < 6) {
            $project->update([
                // 'contracts' => 1,
                'contract_created_at' => date('Y-m-d'),
                'status' => $status,
                'prepayment' => intval($input['prepayment']),
                'contract_cost' => $input['contract_cost'],
                'remaining' => $input['contract_cost'],
                'start_date' => $project_start_date,
                'end_date' => $project_end_date,
                'day_count' => $input['total_day_count'],
                'service_description' => $input['comment'],
                'required_outputs' => $input['required_outputs'],
            ]);

            $retry++;
        }

        if ($project->status != $status) {
            return response()->json([
                'error' => true,
                'errorMsg' => 'خطایی رخ داده است، مجددا تلاش کنید',
            ]);
        }

        $phases = json_decode($input['phases']);


        if ($project->prepayment > 0) {
            $phase_prepayment = Phase::create([
                'description' => ' ',
                'project_id' => $project->id,
                'day_count' => 0,
                'start_date' => $project_start_date,
                'end_date' => $project_start_date,
                'cost' => $project->prepayment,
                'phase_number' => 0,
                'status' => 5,
            ]);
             Report::create([
                'project_id'=>$project_id,
                'phases_id'=> $phase_prepayment->id,
                'file_src'=>'',
                'comments'=>'',
            ]);
        }
//        else
//        {
//            $phase_prepayment = Phase::create([
//                'description' => ' ',
//                'project_id' => $project->id,
//                'day_count' => 0,
//                'start_date' => $project_start_date,
//                'end_date' => $project_start_date,
//                'cost' => $project->prepayment,
//                'phase_number' => 0,
//                'status' => 1,
//            ]);
//        }

        $phase_end_date = '';
        foreach ($phases as $key => $phase) {
            if ($key == 0) {
                $phase_start_date = date('Y-m-d', strtotime($project_start_date));
                $phase_end_date = date('Y-m-d', strtotime($phase_start_date . ' + ' . intval($phase->day_count) . ' days'));
            } else {
                $phase_start_date = $phase_end_date;
                $phase_end_date = date('Y-m-d', strtotime($phase_start_date . ' + ' . intval($phase->day_count) . ' days'));
            }

            Phase::create([
                'description' => $phase->description,
                'project_id' => $project->id,
                'day_count' => $phase->day_count,
                'start_date' => $phase_start_date,
                'end_date' => $phase_end_date,
                'cost' => $phase->price,
                'phase_number' => $key + 1,
            ]);
        }

        SaveTime::query()->create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'role_id' => 8,
            'level' => 2,
            'comments' => 'مجری (' . auth()->user()->name . ' ' . auth()->user()->family . ') قرارداد را ثبت کرد',
        ]);

        sms_otp($employer_info->mobile, 'employerOne', ['param1' => $employer_name, 'param2' => $project_title, 'param3' =>$user_name]);

        return response()->json([
            'error' => false,
            'errorMsg' => ' قرارداد شما با موفقیت ثبت شد منتظر تایید کارفرما باشید',
        ]);
    }

    public function edit($project)
    {
        $project_info = Project::query()
            ->where('projects.id', $project)
            ->firstOrFail();

        $project_id = $project_info['id'];

        $phases_info = Phase::query()
            ->where('project_id', $project)
            ->where('phase_number', '!=', 0)
            ->get();

        $project_info['start_date_jalali'] = verta($project_info->start_date)->format('Y/m/d');
        $project_info['end_date_jalali'] = verta($project_info->end_date)->format('Y/m/d');

        return view('realUser.contracts.edit', compact('project','project_id' , 'project_info', 'phases_info'));
    }

    public function contract_update(Request $request)
    {
        $input = $request->all();
        $project_id = $request->project_id;

        $project = Project::query()
            ->where('id', $project_id)
            ->firstOrFail();

        $validation = Validator::make($input, [
            'project_id' => 'required|integer',
            'contract_cost' => 'required|integer',
            'start_date' => 'required|string',
            'total_day_count' => 'required|integer',
            'comment' => 'required|string',
            'required_outputs' => 'required|string',
            'phases' => 'required',
            'prepayment' => 'required|integer',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => true,
                'errorMsg' => $validation->errors(),
            ]);
        }

        if ($request->has('proposal_file')) {
            $validation = Validator::make($input, [
                'proposal_file' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx,rar',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'error' => true,
                    'errorMsg' => $validation->errors(),
                ]);
            }

            $file_name = time() . $request->file('proposal_file')->getClientOriginalName();
            $project->update([
                'attachment' => $request->file('proposal_file')->move('files/attachments', $file_name),
            ]);
        }

        $project_start_date = $this->convertDateToGregorian($input['start_date']);
        $project_end_date = date('Y-m-d', strtotime($project_start_date . ' + ' . intval($input['total_day_count']) . ' days'));
        if ($project->confirmed_by_employer == 0) {
            $status = 3;
        } else {
            $status = 4;
        }

        $project->update([
            // 'contracts' => 1,
            'status' => $status,
            'prepayment' => intval($input['prepayment']),
            'contract_cost' => $input['contract_cost'],
            'remaining' => $input['contract_cost'],
            'start_date' => $project_start_date,
            'end_date' => $project_end_date,
            'day_count' => $input['total_day_count'],
            'service_description' => $input['comment'],
            'required_outputs' => $input['required_outputs'],
        ]);

        Phase::query()
            ->where('project_id', $project_id)
            ->delete();

        $phases = json_decode($input['phases']);

        if ($project->prepayment > 0) {
            Phase::create([
                'description' => ' ',
                'project_id' => $project->id,
                'day_count' => 0,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d'),
                'cost' => $project->prepayment,
                'phase_number' => 0,
                'status' => 5,
            ]);
        }
        else{
            Phase::create([
                'description' => ' ',
                'project_id' => $project->id,
                'day_count' => 0,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d'),
                'cost' => $project->prepayment,
                'phase_number' => 0,
                'status' => 1,
            ]);
        }

        $phase_end_date = '';
        foreach ($phases as $key => $phase) {
            if ($key == 0) {
                $phase_start_date = date('Y-m-d', strtotime($project_start_date));
                $phase_end_date = date('Y-m-d', strtotime($phase_start_date . ' + ' . intval($phase->day_count) . ' days'));
            } else {
                $phase_start_date = $phase_end_date;
                $phase_end_date = date('Y-m-d', strtotime($phase_start_date . ' + ' . intval($phase->day_count) . ' days'));
            }

            Phase::create([
                'description' => $phase->description,
                'project_id' => $project->id,
                'day_count' => $phase->day_count,
                'start_date' => $phase_start_date,
                'end_date' => $phase_end_date,
                'cost' => $phase->price,
                'phase_number' => $key + 1,
            ]);
        }

        return response()->json([
            'error' => false,
            'errorMsg' => ' قرارداد شما با موفقیت ویرایش گردید، منتظر تایید مالی باشید',
        ]);
    }

    function convertDateToGregorian($date)
    {
        $date = explode('/', $date);
        $date = Verta::getGregorian($this->convertDigitsToEnglish($date[0]), $this->convertDigitsToEnglish($date[1]), $this->convertDigitsToEnglish($date[2]));
        return join('-', $date);
    }

    public function minot(Project $project)
    {

        $user = User::query()->where('id', $project->user_id)->first();

        $user_co_reg_date = verta($user->co_reg_date)->format('d/%B/Y');

        $account = Account::query()
            ->select('*', 'accounts.id as a_id', 'banks.id as b_id', 'users.id as u_id')
            ->join('users', 'users.id', 'accounts.user_id')
            ->join('banks', 'banks.id', 'accounts.bank')
            ->where('accounts.user_id',$user->id)

            ->first();

        $project_start_date = verta($project->start_date)->format('d/%B/Y');

        $role=8;
        $project_end_date = verta($project->end_date)->format('d/%B/Y');

        $user_info = User::query()
            ->where('id', $project->user_id)
            ->first();
        if ($user_info->type = 0) {
            $user_name = $user_info->name . ' ' . $user_info->family;
            $user_Signature_img = $user_info->Signature_img;
        } elseif ($user_info->type = 1) {
            $user_name = $user_info->ceo_name . ' ' . $user_info->ceo_family;
            $user_Signature_img = $user_info->Signature_img;
        }
        $user_type = User::query()
            ->where('id', $project->user_id)
            ->first();

        $user_type = $user_type->type;

        $phases = Phase::query()
            ->where('project_id', $project->id)
            ->get();
        foreach ($phases as $phase) {
            $phase['start_date_jalali'] = verta($phase->start_date)->format('d/%B/Y');
            $phase['end_date_jalali'] = verta($phase->end_date)->format('d/%B/Y');
        }


        return view('share_minot.print', ['role'=>$role ,'user_co_reg_date' => $user_co_reg_date, 'user_type' => $user_type, 'phases' => $phases, 'project' => $project, 'user' => $user, 'account' => $account, 'project_start_date' => $project_start_date, 'project_end_date' => $project_end_date]);
    }

    public function send_sign(Project $project)
    {
        $user = auth()->user();
        $code = rand(100000, 999999);

        $project->sign_code = $code;
        $project->save();

        $sign_sms = sms_otp($user->mobile, 'signMinotConfirmCode', ['param1' => $code]);

        if ($sign_sms) {
            return response()->json([
                'status' => true,
                'message' => 'کد ارسال شده را در کادر زیر وارد نمایید',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'مشکلی در ارسال پیامک رخ داده لطفا با پشتیبانی تماس بگیرید',
            ]);
        }
    }

    public function verify_sign(Project $project, Request $request)
    {
        $txt_sign = $request->txt_sign;

        User::query()
            ->select('mobile')
            ->where('id', $project->user_id)
            ->firstOrFail();

        $project_title = $project->title;

        if ($txt_sign == $project->sign_code) {
            $project->signed_by_user = 1;
            $project->status = 8;
            $project->save();

            $message = 'مدیریت محترم مالی امضای دیجیتال پروژه' . ' ' . $project_title . ' ' . 'درج گردید لطفا برای بررسی به پنل خود مراجعه بفرمایید.';

            $mali_info = Role::query()
                ->where('roles', 6)
                ->get();

            SaveTime::query()->create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'role_id' => 8,
                'level' => 7,
                'comments' => 'مجری (' . auth()->user()->name . ' ' . auth()->user()->family . ') قرارداد را امضا کرد',
            ]);

            foreach ($mali_info as $mali_inf) {
                $user_info = User::query()
                    ->where('id', $mali_inf->user_id)
                    ->first();

                $user_mobile = $user_info->mobile;

                sms($user_mobile, $message);
            }

            return response()->json([
                'status' => true,
                'message' => 'امضای شما در مینوت درج گردید',
                'location' => route('real_project_in_process'),
                'img' => asset(auth()->user()->Signature_img)
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'کد وارد شده صحیح نیست',
            ]);
        }
    }

    public function signed_minot(Project $project)
    {
        $user = User::query()->where('id', $project->user_id)->first();

        $account = Account::query()
            ->select('*', 'accounts.id as a_id', 'banks.id as b_id', 'users.id as u_id')
            ->join('users', 'users.id', 'accounts.user_id')
            ->join('banks', 'banks.id', 'accounts.bank')
            ->where('accounts.user_id',$user->id)
            ->first();

        $user_co_reg_date = verta($user->co_reg_date)->format('d/%B/Y');


        $role = 8;
        $project_start_date = verta($project->start_date)->format('d/%B/Y');

        $project_end_date = verta($project->end_date)->format('d/%B/Y');

        $user_type = User::query()
            ->where('id', $project->user_id)
            ->first();

        $user_type = $user_type->type;

        $phases = Phase::query()
            ->where('project_id', $project->id)
            ->get();

        foreach ($phases as $phase) {
            $phase['start_date_jalali'] = verta($phase->start_date)->format('d/%B/Y');
            $phase['end_date_jalali'] = verta($phase->end_date)->format('d/%B/Y');
        }

        $employer_info = User::query()
            ->where('id', $project->employer_id)
            ->first();

        $supervisor_info = User::query()
            ->where('id', $project->supervisor_id)
            ->first();

        $user_info = User::query()
            ->where('id', $project->user_id)
            ->first();
        if ($user_info->type = 0) {
            $user_name = $user_info->name . ' ' . $user_info->family;
            $user_Signature_img = $user_info->Signature_img;
            $user_stamp_img = $user_info->stamp_img;
        } elseif ($user_info->type = 1) {
            $user_name = $user_info->ceo_name . ' ' . $user_info->ceo_family;
            $user_Signature_img = $user_info->Signature_img;
            $user_stamp_img = $user_info->stamp_img;
        }
        $employer_name = $employer_info->name . ' ' . $employer_info->family;
        $employer_Signature_img = $employer_info->Signature_img;

        $supervisor_name = $supervisor_info->name . ' ' . $supervisor_info->family;
        $supervisor_Signature_img = $supervisor_info->Signature_img;

        return view('share_minot.print_by_sign', ['role'=>$role , 'user_stamp_img'=>$user_stamp_img,'user_co_reg_date' => $user_co_reg_date, 'user_type' => $user_type, 'phases' => $phases, 'user_Signature_img' => $user_Signature_img, 'supervisor_Signature_img' => $supervisor_Signature_img, 'employer_Signature_img' => $employer_Signature_img, 'user_name' => $user_name, 'supervisor_name' => $supervisor_name, 'employer_name' => $employer_name, 'project' => $project, 'user' => $user, 'account' => $account, 'project_start_date' => $project_start_date, 'project_end_date' => $project_end_date]);
    }

    public function delete_contract(Request $request)
    {

        $project_info = Project::query()
            ->where('id',$request->delete_contract_id)
            ->first();

        $project_info->update([
            'required_outputs' => '',
            'supervisor_id' => 0,
            'status' => 2,
            // 'contracts' => 0,
            'service_description' => '',
            'day_count' => '',
            'contract_created_at' => '',
            'prepayment' => 0,
            'contract_cost' => 0,
            'signed_by_user' => 0,
            'remaining' =>0,
            'confirmed_by_employer' => 0,
            'rejected_by_employer' => 0 ,
            'project_unique_code' => '',
            'project_unique_code_search' => '',
            'rejected_by_main_manager' => 0
        ]);

        Phase::query()
            ->where('project_id', $request->delete_contract_id)
            ->delete();

        alert()->success('قرارداد مورد نظر حذف گردید', 'با تشکر')->autoclose(9000);
        return back();
    }

    function convertDigitsToEnglish($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    function view(Project $project){
        $supervisor = User::where('id', $project->supervisor_id)->first();

        if ($supervisor){
            $supervisor_info = $supervisor->name . ' ' . $supervisor->family;
        }
        else{
            $supervisor_info = 'تعیین نشده است';
        }

        $project_info = Project::query()
            ->where('id', $project->id)
            ->first();

        $user_id = $project_info->user_id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $user_img = $user_info->image;

        $project_start_date_jalali = verta($project->start_date)->format('d/%B/Y');

        $project_end_date_jalali = verta($project->end_date)->format('d/%B/Y');

        $phases = Phase::query()
            ->where('project_id', $project->id)
            ->get();

        foreach ($phases as $phase) {
            $phase['start_date_jalali'] = verta($phase->start_date)->format('d/%B/Y');

            $phase['end_date_jalali'] = verta($phase->end_date)->format('d/%B/Y');
        }

        $roles = Role::query()
            ->select('user_id')
            ->where('roles', 5)
            ->get();

        foreach ($roles as $role) {
            $users = User::query()
                ->where('id', $role->user_id)
                ->get();
        }

        return view('realUser.contracts.view', ['supervisor_info'=> $supervisor_info , 'user_img' => $user_img, 'supervisor' => $supervisor, 'users' => $users, 'phase' => $phase, 'phases' => $phases, 'project' => $project, 'project_start_date_jalali' => $project_start_date_jalali, 'project_end_date_jalali' => $project_end_date_jalali]);
    }

}
