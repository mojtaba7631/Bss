<?php

namespace App\Http\Controllers\employer;

use App\Http\Controllers\Controller;
use App\Models\Delay;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\Role;
use App\Models\SaveTime;
use App\Models\User;
use App\Models\UserRole;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class contractController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'users.type as type')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->join('users', 'users.id', '=', 'projects.user_id')
            ->where('status', 3)
            ->where('projects.employer_id', $user_id)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($projects as $project) {
            $project['contract_created_at'] = verta($project->contract_created_at)->format('d/%B/Y');

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 3)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', 3)
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('employer.contracts.index', ['phases_status' => $phases_status, 'projects' => $projects, 'users' => $users, 'projects_status' => $projects_status, 'searched' => $searched]);
    }

    public function search_index(Request $request)
    {
        $input = $request->all();

        if ($request->has('project_unique_code_search') and $input['project_unique_code_search'] != '') {
            $code = $input['project_unique_code_search'];
        } else {
            $code = 0;
        }

        if ($request->has('title') and $input['title'] != '') {
            $title = $input['title'];
        } else {
            $title = '';
        }

        if ($request->has('user_id') and $input['user_id'] != '') {
            $user_id = $input['user_id'];
        } else {
            $user_id = 0;
        }

        if ($request->has('status') and $input['status'] != '') {
            $status = intval($input['status']);
        } else {
            $status = 0;
        }

        if ($status > 100) {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
                    'phases.status as f_status', 'projects.status as status', 'phases_status.status_css as status_css')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('phases_status', 'phases_status.id', '=', 'phases.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 3);
                    $q->where('phases.status', ($status - 100));
                })
                ->where('projects.status', 3)
                ->orderByDesc('projects.created_at')
                ->get();

        } else {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.status as status')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', $status);
                })
                ->where('projects.status', 3)
                ->get();
        }

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 3)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', 3)
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('employer.contracts.index', ['users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function check_project_phase($project_id)
    {
        $phase = Phase::query()
            ->where('project_id', $project_id)
            ->where('status', '!=', 7)
            ->first();

        if ($phase) {
            $status_info = phases_status::query()
                ->where('id', $phase->status)
                ->first();

            $phase_number = $phase['phase_number'];
            if ($phase_number == 0) {
                $phase_number = 'پیش پرداخت';
            } else {
                $phase_number = 'فاز ' . $phase_number;
            }
        } else {
            $status_info = phases_status::query()
                ->where('id', 7)
                ->first();

            $phase_number = 'فاز آخر';
        }

        $s_title = $status_info->title;
        return [$s_title, $phase_number];
    }

    public function view($project_id)
    {

        $project = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('status', 3)
            ->where('projects.id', $project_id)
            ->firstOrFail();

        $project_info = Project::query()
            ->where('id', $project_id)
            ->first();

        $user_id = $project_info->user_id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $user_img = $user_info->image;

        if ($project->status == 8) {
            $result = $this->check_project_phase($project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        } else {
            $project['s_title'] = '<b class="mr-3px ' . $project['status_css'] . '">' . $project['s_title'] . '</b>';
        }

        $project_start_date = verta($project->start_date)->format('d/%B/Y');
        $project_start_date_jalali = explode(' ', $project_start_date)[0];

        $project_end_date = verta($project->end_date)->format('d/%B/Y');
        $project_end_date_jalali = explode(' ', $project_end_date)[0];


        $phases = Phase::query()
            ->where('project_id', $project_id)
            ->get();

        foreach ($phases as $phase) {
            $phase['start_date_jalali'] = verta($phase->start_date)->format('d/%B/Y');

            $phase['end_date_jalali'] = verta($phase->end_date)->format('d/%B/Y');
        }

        $user_ids = Role::query()
            ->where('roles', 4)->pluck('user_id');

        $users = User::query()
            ->whereIn('id', $user_ids)->get();

        return view('employer.contracts.view', ['user_img' => $user_img, 'users' => $users, 'phases' => $phases, 'project' => $project, 'project_start_date_jalali' => $project_start_date_jalali, 'project_end_date_jalali' => $project_end_date_jalali]);
    }

    function convertDateToGregorian($date)
    {
        $date = explode('/', $date);
        $date = Verta::getGregorian($date[0], $date[1], $date[2]);
        return join('-', $date);
    }

    function calculate_delayed($startDate, $endDate)
    {
        // Parse dates for conversion
        $startArry = date_parse($startDate);
        $endArry = date_parse($endDate);

        // Convert dates to Julian Days
        $start_date = gregoriantojd($startArry["month"], $startArry["day"], $startArry["year"]);
        $end_date = gregoriantojd($endArry["month"], $endArry["day"], $endArry["year"]);

        // Return difference
        return intval(round(($end_date - $start_date), 0));
    }

    public function employer_verify(Request $request)
    {
        $input = $request->all();

        $today = \Carbon\Carbon::now();

        $project_id = $request->accept_project_id;

        $project_info = Project::query()
            ->where('id', $project_id)
            ->first();

        $project_title = $project_info->title;

        $employer_info = User::query()
            ->where('id', $project_info->employer_id)
            ->first();

        $employer_name = $employer_info->name . ' ' . $employer_info->family;

        $user_info = User::query()
            ->where('id', $project_info->user_id)
            ->first();

        $user_name = $user_info->co_name;

        $day_count = $this->calculate_delayed($today, $project_info->contract_created_at);

        if ($day_count < -2) {
            //has delay
            $day_count = abs($day_count);

            $delay_info = Delay::query()
                ->where('delay.user_id', auth()->user()->id)
                ->first();

            if (!$delay_info) {
                //add
                Delay::create([
                    'user_id' => auth()->user()->id,
                    'day_count' => $day_count - 2,
                    'role' => 3
                ]);
            } else {
                //update
                $delay_info->update([
                    'day_count' => $delay_info['day_count'] + ($day_count - 3),
                ]);
            }
        }

        $project = Project::query()
            ->where('id', $request->accept_project_id)
            ->firstOrFail();

        $project->status = 4;
        $project->confirmed_by_employer = 1;
        $project->rejected_by_employer = 0;
        $project->save();

        $mali_info = Role::query()
            ->where('roles', 6)
            ->get();

        SaveTime::query()->create([
            'project_id' => $request->accept_project_id,
            'user_id' => auth()->id(),
            'role_id' => 3,
            'level' => 3,
            'comments' => 'کارفرما (' . auth()->user()->name . ' ' . auth()->user()->family . ') قرارداد را تایید کرد',
        ]);

        foreach ($mali_info as $mali_inf) {
            $user_info = User::query()
                ->where('id', $mali_inf->user_id)
                ->first();
            $user_mobile = $user_info->mobile;

            sms_otp($user_mobile, 'managersContractForm', ['param1' => 'مالی ']);
        }
        alert()->success('فرم قرارداد توسط شما تایید شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('employer_contract_index');
    }

    public function notActive(Request $request)
    {
        $project_id = $request->project;

        $project_error_info = Project_error::query()
            ->where('project_id', $project_id)
            ->first();

        $project_info = Project::query()
            ->where('id', $project_id)
            ->first();

        $employer_id = $project_info->employer_id;

        if ($project_error_info) {
            $project_error_info->update([
                'message' => $request->message,
                'sender' => $employer_id,
                'read_message' => 0,
            ]);

        } else {
            Project_error::create([
                'message' => $request->message,
                'project_id' => $project_id,
                'sender' => $employer_id
            ]);
        }

        $project_info->update([
            'status' => 2,
            'rejected_by_employer' => 1,
        ]);

        $user_info = User::query()
            ->where('id', $project_info->user_id)
            ->first();

        if ($user_info->type == 0) {
            $real_legal_name = $user_info->name . ' ' . $user_info->family;
        } elseif ($user_info->type == 1) {
            $real_legal_name = $user_info->co_name;
        }

        sms_otp($user_info->mobile, 'userRejectContract', ['param1' => $real_legal_name, 'param2' => 'کارفرما ']);

        return response()->json([
            'error' => false,
            'errorMsg' => 'عدم تایید قرارداد اعمال شد'
        ]);
    }
}
