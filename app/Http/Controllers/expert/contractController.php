<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\project_status;
use App\Models\User;
use App\Models\Role;
use App\Models\Project_error;
use Illuminate\Http\Request;

class contractController extends Controller
{
    public function index()
    {

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            // ->whereIn('projects.status', [8])
            ->where('projects.status',8)
            ->Orwhere('projects.status',9)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $user_info = User::query()
                ->where('id', $project['user_id'])
                ->firstOrFail();

            if ($user_info['type'] == 0) {
                $project['user_name'] = $user_info['name'] . ' ' . $user_info['family'];
            } else {
                $project['user_name'] = $user_info['co_name'];
            }

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }

            if ($project['supervisor']){
                $project['super_name'] = $project['supervisor']['name'];
                $project['super_family'] = $project['supervisor']['family'];
            }
            else{
                $project['super_name'] = '-';
                $project['super_family'] = '-';
            }

        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $users = User::query()
            ->select('*', 'users.id as userId')
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 8)
            ->orWhere('user_role.roles', 7)
            ->orderByDesc('users.type')
            ->orderBy('users.co_name')
            ->orderBy('users.family')
            ->get();


        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '=', 4)
            ->orWhere('id', '=', 10)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', '=', 6)
            ->get();

        $searched = false;

        return view('expert.contracts.index', ['searched' => $searched, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status , 'users' => $users]);
    }

    function check_project_phase($project_id)
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

        $role = 9;

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

        return view('share_minot.print_by_sign', ['role' => $role, 'user_stamp_img' => $user_stamp_img, 'user_co_reg_date' => $user_co_reg_date, 'user_type' => $user_type, 'phases' => $phases, 'user_Signature_img' => $user_Signature_img, 'supervisor_Signature_img' => $supervisor_Signature_img, 'employer_Signature_img' => $employer_Signature_img, 'user_name' => $user_name, 'supervisor_name' => $supervisor_name, 'employer_name' => $employer_name, 'project' => $project, 'user' => $user, 'account' => $account, 'project_start_date' => $project_start_date, 'project_end_date' => $project_end_date]);
    }

    public function search_index(Request $request){
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
        if ($request->has('user') and $input['user'] != '') {
            $user = intval($input['user']);
            $search_info['user'] = $user;
        } else {
            $user = 0;
        }

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.created_at as p_created_at')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->when($code != 0, function ($q) use ($code) {
                $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
            })
            ->when($title != '', function ($q) use ($title) {
                $q->where('projects.title', 'like', '%' . $title . '%');
            })
            ->when($employer != 0, function ($q) use ($employer) {
                $q->where('employer_id', $employer);
            })
            ->when($user != 0, function ($q) use ($user) {
                $q->where('projects.user_id', $user);
            })
            ->where('projects.status', '!=', 9)
            ->orderByDesc('projects.created_at')
            ->get();

        foreach ($projects as $project) {
            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
            $user_info = User::query()
                ->where('id', $project['user_id'])
                ->firstOrFail();

            if ($user_info['type'] == 0) {
                $project['user_name'] = $user_info['name'] . ' ' . $user_info['family'];
            } else {
                $project['user_name'] = $user_info['co_name'];
            }
        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $users = User::query()
            ->select('*', 'users.id as userId')
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 8)
            ->orWhere('user_role.roles', 7)
            ->orderByDesc('users.type')
            ->orderBy('users.co_name')
            ->orderBy('users.family')
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 5)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $project['project_error'] = Project_error::query()
                ->where('project_id', $project->id)
                ->get();

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
                $project['reportable'] = $this->get_phase_reportable($project['project_id']);
            } else {
                $project['reportable'] = false;
            }
        }

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('expert.contracts.search_index', ['users' => $users,'projects' => $projects, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);

    }

    public function get_phase_reportable($project_id){
        $phase_reportable_count = Phase::query()
            ->where('project_id', $project_id)
            ->where('status', 1)
            ->count();

        if ($phase_reportable_count > 0) {
            return true;
        } else {
            return false;
        }
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

        return view('expert.contracts.view', ['supervisor_info'=> $supervisor_info , 'user_img' => $user_img, 'supervisor' => $supervisor, 'users' => $users, 'phase' => $phase, 'phases' => $phases, 'project' => $project, 'project_start_date_jalali' => $project_start_date_jalali, 'project_end_date_jalali' => $project_end_date_jalali]);
    }

     public function download_propusal($project){
        $project_info = Project::query()
            ->where('id',$project)
            ->firstOrFail();
        return response()->download($project_info->file);
    }
}
