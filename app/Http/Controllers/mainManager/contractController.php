<?php

namespace App\Http\Controllers\mainManager;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class contractController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('status', 6)
            ->paginate(10);

        foreach ($projects as $project) {
            $project['end_date'] = verta($project->end_date)->format('d/%B/Y');

            $user_info = User::query()
                ->where('id', $project->user_id)
                ->first();

            if ($user_info->type == 0) {
                $project['name_info'] = $user_info->name . ' ' . $user_info->family;
            } elseif ($user_info->type == 1) {
                $project['name_info'] = $user_info->co_name;
            }


            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
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
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        $searched = false;

        return view('main_manager.contracts.index', ['users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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

        if ($request->has('supervisor') and $input['supervisor'] != '') {
            $supervisor = $input['supervisor'];
        } else {
            $supervisor = 0;
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
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user != 0, function ($q) use ($user) {
                    $q->where('projects.user_id', $user);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 8);
                    $q->where('phases.status', ($status - 100));
                })
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
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user != 0, function ($q) use ($user) {
                    $q->where('projects.user_id', $user);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', $status);
                })
                ->get();
        }

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $user_info = User::query()
                ->where('id', $project->user_id)
                ->first();

            if ($user_info->type == 0) {
                $project['name_info'] = $user_info->name . ' ' . $user_info->family;
            } elseif ($user_info->type == 1) {
                $project['name_info'] = $user_info->co_name;
            }

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
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
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('main_manager.contracts.index', ['search_info' => $search_info, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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
                $phase_number = '?????? ????????????';
            } else {
                $phase_number = '?????? ' . $phase_number;
            }
        } else {
            $status_info = phases_status::query()
                ->where('id', 7)
                ->first();

            $phase_number = '?????? ??????';
        }

        $s_title = $status_info->title;
        return [$s_title, $phase_number];
    }

    public function view($project_id)
    {

        $project = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('status', 6)
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

        $roles = Role::query()
            ->select('user_id')
            ->where('roles', 4)
            ->get();

        foreach ($roles as $role) {
            $users = User::query()
                ->where('id', $role->user_id)
                ->get();
        }

        return view('main_manager.contracts.view', ['user_img' => $user_img, 'users' => $users, 'phases' => $phases, 'project' => $project, 'project_start_date_jalali' => $project_start_date_jalali, 'project_end_date_jalali' => $project_end_date_jalali]);
    }

    public function mainManager_verify(Request $request)
    {
        $project_id = $request->project_id;

        $project = Project::query()
            ->where('id', $project_id)
            ->first();

        $user_info = User::query()
            ->where('id', $project->user_id)
            ->first();

        $user_type = $user_info->type;

        $user_mobile = $user_info->mobile;

        if ($user_type == 1) {
            $user_name = $user_info->co_name;
        } else {
            $user_name = $user_info->name . ' ' . $user_info->family;
        }
        $project->status = 7;
        $project->save();

        sms_otp($user_mobile, 'UserSignContract', ['param1' => $user_name]);

        alert()->success('?????????????? ???????? ?????? ?????????? ????.', '???? ????????')->autoclose(9000);

        return redirect()->route('mainManager_contract_index');
    }

    public function notActive(Request $request)
    {
        $user_id = auth()->user()->id;

        $project_id = $request->project;

        $project_error_info = Project_error::query()
            ->where('project_id', $project_id)
            ->first();

        $project_info = Project::query()
            ->where('id', $project_id)
            ->first();


        if ($project_error_info) {

            $project_error_info->update([
                'message' => $request->message,
                'sender' => $user_id,
                'not_active_code' => 3,
                'read_message' => 0
            ]);

            $project_info->update([
                'status' => 5,
                'rejected_by_main_manager' => 1
            ]);


        } else {
            Project_error::create([
                'message' => $request->message,
                'project_id' => $project_id,
                'sender' => $user_id,
                'not_active_code' => 3
            ]);
            return response()->json([
                'error' => false,
                'errorMsg' => '?????? ?????????? ?????????????? ?????????? ????'
            ]);
        }
        $project_info->update([
            'status' => 5,
            'rejected_by_main_manager' => 1
        ]);

        $user_info = User::query()
            ->where('id', $project_info->user_id)
            ->first();

        if ($user_info->type == 0) {
            $real_legal_name = $user_info->name . ' ' . $user_info->family;
        } elseif ($user_info->type == 1) {
            $real_legal_name = $user_info->co_name;
        }

        sms_otp($user_info->mobile, 'userRejectContract', ['param1' => $real_legal_name, 'param2' => '????????']);

        return response()->json([
            'error' => false,
            'errorMsg' => '?????? ?????????? ?????????????? ?????????? ????'
        ]);
    }

    public function Accept_contract()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->paginate(10);

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $user_info = User::query()
                ->where('id', $project->user_id)
                ->first();

            $calculated = $this->calculate_total_payed_reminding($project->project_id, $project->contract_cost);

            $project['payed'] = $calculated[0];
            $project['total'] = $calculated[1];
            $project['reminding'] = $calculated[2];

            if ($user_info->type == 0) {
                $project['name_info'] = $user_info->name . ' ' . $user_info->family;
            } elseif ($user_info->type == 1) {
                $project['name_info'] = $user_info->co_name;
            }

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

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


        $phases_status = phases_status::query()
            ->get();

        $searched = false;

        return view('main_manager.contracts.accept_contract', ['users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function signed_minot_mali(Project $project)
    {
        $user = User::query()->where('id', $project->user_id)->first();

        $account = Account::query()
            ->select('*', 'accounts.id as a_id', 'banks.id as b_id', 'users.id as u_id')
            ->join('users', 'users.id', 'accounts.user_id')
            ->join('banks', 'banks.id', 'accounts.bank')
            ->first();

        $user_co_reg_date = verta($user->co_reg_date)->format('d/%B/Y');


        $role = 5;
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

    public function search_accept_main(Request $request)
    {
        $input = $request->all();

        $search_info = [];

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

        if ($request->has('supervisor') and $input['supervisor'] != '') {
            $supervisor = $input['supervisor'];
        } else {
            $supervisor = 0;
        }

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
            $search_info['employer'] = $employer;
        } else {
            $employer = 0;
        }

        if ($request->has('user') and $input['user'] != '') {
            $user = intval($input['user']);
            $search_info['user'] = $user;
        } else {
            $user = 0;
        }

        if ($request->has('status') and $input['status'] != '') {
            $status = intval($input['status']);
        } else {
            $status = 0;
        }

        if ($status > 100) {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'phases.status as f_status', 'projects.status as status', 'phases_status.status_css as status_css')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('phases_status', 'phases_status.id', '=', 'phases.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user != 0, function ($q) use ($user) {
                    $q->where('projects.user_id', $user);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 8);
                    $q->where('phases.status', ($status - 100));
                })
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
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user != 0, function ($q) use ($user) {
                    $q->where('projects.user_id', $user);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', $status);
                })
                ->get();
        }


        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $user_info = User::query()
                ->where('id', $project->user_id)
                ->first();

            if ($user_info->type == 0) {
                $project['name_info'] = $user_info->name . ' ' . $user_info->family;
            } elseif ($user_info->type == 1) {
                $project['name_info'] = $user_info->co_name;
            }

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
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
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('main_manager.contracts.accept_contract', ['search_info' => $search_info, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function download_file($project)
    {

        $projectt_info = Project::query()
            ->where('id', $project)
            ->firstOrFail();
        return response()->download($projectt_info->file);
    }

    public function delete_contract(Request $request)
    {

        $project_info = Project::query()
            ->where('id', $request->delete_contract_id)
            ->first();

        $project_info->update([
            'required_outputs' => '',
            'supervisor_id' => 0,
            'status' => 2,
            'contracts' => 0,
            'service_description' => '',
            'day_count' => '',
            'contract_created_at' => '',
            'prepayment' => 0,
            'contract_cost' => 0,
            'signed_by_user' => 0,
            'remaining' => 0,
            'confirmed_by_employer' => 0,
            'rejected_by_employer' => 0,
            'project_unique_code' => '',
            'project_unique_code_search' => '',
            'rejected_by_main_manager' => 0
        ]);

        Phase::query()
            ->where('project_id', $request->delete_contract_id)
            ->delete();

        alert()->success('?????????????? ???????? ?????? ?????? ??????????', '???? ????????')->autoclose(9000);
        return back();
    }

    public function delete_project(Request $request)
    {
        $project_info = Project::query()
            ->where('id', $request->delete_project_id)
            ->first();


        Project::query()
            ->where('id', $request->delete_project_id)
            ->delete();

        alert()->success('?????????? ???????? ?????? ?????? ??????????', '???? ????????')->autoclose(9000);
        return back();
    }

    public function gantt_chart($employer_id = 0)
    {
        $employers = User::query()
            ->select('*', 'users.id as employer_id')
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->join('projects', 'projects.user_id', '=', 'users.id')
            ->where('user_role.roles', 7)
            ->where('projects.status', '!=', 9)
            ->where('projects.project_unique_code', '!=', null)
            ->groupBy('projects.user_id')
            ->orderBy('users.co_name')
            ->get();

        if ($employer_id > 0) {
            $employer_info = User::query()->where('id', $employer_id)->firstOrFail();

            $projects = Project::query()
                ->select('*', 'projects.id as project_id')
                ->where('user_id', $employer_id)
                ->where('projects.project_unique_code', '!=', null)
                ->where('projects.status', '!=', 9)
                ->get();

            foreach ($projects as $project) {
                $project['phases'] = $this->check_project_chart_4($project['project_id']);
            }

        } else {
            $employer_info = [];
            $projects = [];
        }

        return view('main_manager.contracts.gantt_chart', compact('employers', 'employer_info', 'employer_id', 'projects'));
    }

    function check_project_chart_4($project_id)
    {
        $phases = Phase::query()
            ->where('project_id', $project_id)
            ->get();

        return $phases;
    }

    function calculate_total_payed_reminding($project_id, $total)
    {
        $payed = Payment::query()
            ->where('project_id', $project_id)
            ->where('status', 1)
            ->sum('price');

        $reminding = floatval($total) - $payed;

        return [$payed, $total, $reminding];
    }
}
