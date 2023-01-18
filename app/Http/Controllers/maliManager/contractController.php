<?php

namespace App\Http\Controllers\maliManager;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\Role;
use App\Models\SaveTime;
use App\Models\User;
use Illuminate\Http\Request;

class contractController extends Controller
{
    function index()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->whereIn('projects.status', [4])
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
            ->where('id', '=', 4)
            ->orWhere('id', '=', 10)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', '=', 6)
            ->get();

        $searched = false;

        return view('mali_manager.contracts.index', ['searched' => $searched, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status]);
    }

    function search_index(Request $request)
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
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
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
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 8);
                    $q->where('phases.status', ($status - 100));
                })
                ->whereIn('projects.status', [4, 10])
                ->orderByDesc('projects.created_at')
                ->get();
        } else {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
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
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', $status);
                })
                ->whereIn('projects.status', [4, 10])
                ->orderByDesc('projects.created_at')
                ->get();
        }

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
            ->where('id', '=', 4)
            ->orWhere('id', '=', 10)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', '=', 6)
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('mali_manager.contracts.index', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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

    function view(Project $project)
    {
        if ($project->status == 4) {
            $supervisor = User::where('id', $project->supervisor_id)->first();

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


            return view('mali_manager.contracts.view', ['user_img' => $user_img, 'supervisor' => $supervisor, 'users' => $users, 'phase' => $phase, 'phases' => $phases, 'project' => $project, 'project_start_date_jalali' => $project_start_date_jalali, 'project_end_date_jalali' => $project_end_date_jalali]);
        } elseif ($project->status == 8) {

            $project_id = $project->id;

            $user_info = Project::query()
                ->where('id', $project_id)
                ->first();

            $user_id = $user_info->user_id;

            $account = Account::query()
                ->select('*', 'accounts.id as a_id', 'banks.id as b_id', 'users.id as u_id')
                ->join('users', 'users.id', 'accounts.user_id')
                ->join('banks', 'banks.id', 'accounts.bank')
                ->where('accounts.user_id', $user_id)
                ->first();

            $project_start_date = verta($project->start_date)->format('d/%B/Y');

            $project_end_date = verta($project->end_date)->format('d/%B/Y');

            $phases = Phase::query()
                ->where('project_id', $project_id)
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

            if ($user_info->type == 0) {
                $user_name = $user_info->name . ' ' . $user_info->family;
                $user_Signature_img = $user_info->Signature_img;
                $user_co_reg_date = '';
                $user_Stamp_img = ' ';
            } elseif ($user_info->type == 1) {
                $user_name = $user_info->ceo_name . ' ' . $user_info->ceo_family;
                $user_Signature_img = $user_info->Signature_img;
                $user_co_reg_date = verta($project->co_reg_date)->format('d/%B/Y');
                $user_Stamp_img = $user_info->stamp_img;
            }


            $role = 6;

            $employer_name = $employer_info->name . ' ' . $employer_info->family;
            $employer_Signature_img = $employer_info->Signature_img;

            $supervisor_name = $supervisor_info->name . ' ' . $supervisor_info->family;
            $supervisor_Signature_img = $supervisor_info->Signature_img;

            return view('mali_manager.contracts.print', ['role' => $role, 'user_Stamp_img' => $user_Stamp_img, 'user_info' => $user_info, 'user_co_reg_date' => $user_co_reg_date, 'username' => $user_name, 'phases' => $phases, 'user_Signature_img' => $user_Signature_img, 'supervisor_Signature_img' => $supervisor_Signature_img, 'employer_Signature_img' => $employer_Signature_img, 'user_name' => $user_name, 'supervisor_name' => $supervisor_name, 'employer_name' => $employer_name, 'project' => $project, 'account' => $account, 'project_start_date' => $project_start_date, 'project_end_date' => $project_end_date]);
        }
    }

    function mainManager_verify(Request $request)
    {
        $project = Project::query()
            ->where('id', $request->project_id)
            ->first();
        $project->status = 7;
        $project->save();
        alert()->success('پروژه مورد تایید مدیرعامل قرار گرفت', 'با تشکر')->autoclose(9000);

        return redirect()->route('mainManager_contract_index');
    }

    function minot(Project $project)
    {

        $user = User::query()->where('id', $project->user_id)->first();

        $account = Account::query()->where('user_id', $project->user_id)->first();

        $project->update([
            'status' => 10
        ]);

        $project_start_date = verta($project->start_date)->format('Y/m/d');
        $project_start_date_jalali = explode(' ', $project_start_date)[0];

        $project_end_date = verta($project->end_date)->format('Y/m/d');
        $project_end_date_jalali = explode(' ', $project_end_date)[0];

        $phases = Phase::query()
            ->where('project_id', $project->id)
            ->get();

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
        } elseif ($user_info->type = 1) {
            $user_name = $user_info->ceo_name . ' ' . $user_info->ceo_family;
            $user_Signature_img = $user_info->Signature_img;
        }

        $employer_name = $employer_info->name . ' ' . $employer_info->family;
        $employer_Signature_img = $employer_info->Signature_img;

        $supervisor_name = $supervisor_info->name . ' ' . $supervisor_info->family;
        $supervisor_Signature_img = $supervisor_info->Signature_img;


        return view('mali_manager.contracts.print', ['phases' => $phases, 'user_Signature_img' => $user_Signature_img, 'supervisor_Signature_img' => $supervisor_Signature_img, 'employer_Signature_img' => $employer_Signature_img, 'user_name' => $user_name, 'supervisor_name' => $supervisor_name, 'employer_name' => $employer_name, 'project' => $project, 'user' => $user, 'account' => $account, 'project_start_date_jalali' => $project_start_date_jalali, 'project_end_date_jalali' => $project_end_date_jalali]);
    }

    function final_minot(Project $project)
    {
        $user = User::query()->where('id', $project->user_id)->first();

        $account = Account::query()->where('user_id', $project->user_id)->first();

        $project->update([
            'status' => 10
        ]);

        $project_start_date = verta($project->start_date)->format('Y/m/d');
        $project_start_date_jalali = explode(' ', $project_start_date)[0];

        $project_end_date = verta($project->end_date)->format('Y/m/d');
        $project_end_date_jalali = explode(' ', $project_end_date)[0];


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
            $user_Stamp_img = ' ';
        } elseif ($user_info->type = 1) {
            $user_name = $user_info->ceo_name . ' ' . $user_info->ceo_family;
            $user_Signature_img = $user_info->Signature_img;
            $user_Stamp_img = $user_info->stamp_img;
        }

        $employer_name = $employer_info->name . ' ' . $employer_info->family;
        $employer_Signature_img = $employer_info->Signature_img;

        $supervisor_name = $supervisor_info->name . ' ' . $supervisor_info->family;
        $supervisor_Signature_img = $supervisor_info->Signature_img;


        return view('mali_manager.contracts.print', ['user_Stamp_img' => $user_Stamp_img, 'user_Signature_img' => $user_Signature_img, 'supervisor_Signature_img' => $supervisor_Signature_img, 'employer_Signature_img' => $employer_Signature_img, 'user_name' => $user_name, 'supervisor_name' => $supervisor_name, 'employer_name' => $employer_name, 'project' => $project, 'user' => $user, 'account' => $account, 'project_start_date_jalali' => $project_start_date_jalali, 'project_end_date_jalali' => $project_end_date_jalali]);
    }

    function sign($project)
    {
        $project_info = Project::query()
            ->where('id', $project)
            ->first();

        $project_info->update([
            'status' => 8
        ]);

        alert()->success('قرارداد توسط مجری امضا شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('maliManager_contract_index');
    }

    function accept(Request $request)
    {

        $project_info = Project::query()
            ->where('id', $request->project_id)
            ->firstOrFail();

        $tarh_role = Role::query()
            ->where('roles', 2)
            ->first();

        $tarh_info = User::query()
            ->where('id', $tarh_role->user_id)
            ->first();

        $tarh_mobile = $tarh_info->mobile;

        $project_info->update([
            'status' => 5
        ]);

        SaveTime::query()->create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'role_id' => 6,
            'level' => 4,
            'comments' => 'مدیر مالی (' . auth()->user()->name . ' ' . auth()->user()->family . ') قرارداد را تایید کرد',
        ]);

        sms_otp($tarh_mobile, 'managersContractForm', ['param1' => 'طرح و برنامه']);

        alert()->success('فرم قرارداد توسط شما تایید شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('maliManager_contract_index');
    }

    function full_accept(Project $project)
    {
        $project->status = 6;
        $project->save();

        alert()->success('قرارداد مورد تایید نهایی مدیرمالی قرار گرفت', 'با تشکر')->autoclose(9000);

        return redirect()->route('maliManager_contract_index');
    }

    function sign_list()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.status as p_status', 'projects.end_date as p_end_date',
                'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($projects as $project) {
            $project['jalali_end_date'] = $this->convertDateToJalali($project['p_end_date']);

            $user_info = User::query()
                ->where('id', $project->user_id)
                ->first();

            $user_type = $user_info['type'];

            if ($user_type == 0) {
                $project['user'] = $user_info['name'] . ' ' . $user_info['family'];
            } elseif ($user_type == 1) {
                $project['user'] = $user_info['co_name'];
            }

            $employer = User::query()
                ->select('*')
                ->where('id', $project['employer_id'])
                ->first();

            $project['employer'] = $employer;

            $pay = Payment::query()
                ->where('project_id', $project->project_id)
                ->where('status', 1)
                ->get()
                ->sum('price');

            $remaining = $project->contract_cost - $pay;

            $project['pay'] = $pay;
            $project['remaining'] = $remaining;

            $project['id'] = $project->id;

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
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('mali_manager.contracts.sign_list', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'searched' => $searched, 'phases_status' => $phases_status]);
    }

    function sign_list_search(Request $request)
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

            $user_type = $user_info['type'];

            if ($user_type == 0) {
                $project['user'] = $user_info['name'] . ' ' . $user_info['family'];
            } elseif ($user_type == 1) {
                $project['user'] = $user_info['co_name'];
            }

            $pay = Payment::query()
                ->where('project_id', $project->project_id)
                ->where('status', 1)
                ->get()
                ->sum('price');

            $remaining = $project->contract_cost - $pay;

            $project['pay'] = $pay;
            $project['remaining'] = $remaining;

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

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('mali_manager.contracts.sign_list', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }

    function notActive(Request $request)
    {
        $user_id = auth()->user()->id;

        $project_id = $request->project;

        $project_error_info = Project_error::query()
            ->where('project_id', $project_id)
            ->first();

        $project_info = Project::query()
            ->where('id', $project_id)
            ->first();

        $user_info = User::query()
            ->where('id', $project_info->user_id)
            ->first();

        if ($user_info->type == 0) {
            $real_legal_name = $user_info->name . ' ' . $user_info->family;
        } elseif ($user_info->type == 1) {
            $real_legal_name = $user_info->co_name;
        }

        sms_otp($user_info->mobile, 'userRejectContract', ['param1' => $real_legal_name, 'param2' => 'طرح و برنامه']);

        if ($project_error_info) {
            $project_error_info->update([
                'message' => $request->message,
                'sender' => $user_id,
                'read_message' => 0,
            ]);
            $project_info->update([
                'status' => 2,
            ]);
            return response()->json([
                'error' => false,
                'errorMsg' => 'عدم تایید قرارداد اعمال شد'
            ]);

        } else {
            Project_error::create([
                'message' => $request->message,
                'project_id' => $project_id,
                'sender' => $user_id
            ]);
            $project_info->update([
                'status' => 2,
            ]);

            return response()->json([
                'error' => false,
                'errorMsg' => 'عدم تایید قرارداد اعمال شد'
            ]);
        }
    }

    function Accept_contract(Request $request)
    {
        $project_id = $request->project;

        $project = Project::query()
            ->where('id', $project_id)
            ->first();
        if ($project->status == 10) {
            $project->update([
                'status' => 8
            ]);
        }

        return response()->json('done');
    }

    function signed_minot_mali(Project $project)
    {

        $user = User::query()->where('id', $project->user_id)->first();

        $account = Account::query()
            ->select('*', 'accounts.id as a_id', 'banks.id as b_id', 'users.id as u_id', 'accounts.user_id as a_user_id')
            ->join('users', 'users.id', 'accounts.user_id')
            ->join('banks', 'banks.id', 'accounts.bank')
            ->where('accounts.user_id', $project->user_id)
            ->first();


        $user_co_reg_date = verta($user->co_reg_date)->format('d/%B/Y');

        $role = 6;

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

        if ($supervisor_info != null) {
            $supervisor_name = $supervisor_info->name . ' ' . $supervisor_info->family;
            $supervisor_Signature_img = $supervisor_info->Signature_img;
        } else {
            $supervisor_name = '';
            $supervisor_Signature_img = '';
        }


        return view('share_minot.print_by_sign', ['role' => $role, 'user_stamp_img' => $user_stamp_img, 'user_co_reg_date' => $user_co_reg_date, 'user_type' => $user_type, 'phases' => $phases, 'user_Signature_img' => $user_Signature_img, 'supervisor_Signature_img' => $supervisor_Signature_img, 'employer_Signature_img' => $employer_Signature_img, 'user_name' => $user_name, 'supervisor_name' => $supervisor_name, 'employer_name' => $employer_name, 'project' => $project, 'user' => $user, 'account' => $account, 'project_start_date' => $project_start_date, 'project_end_date' => $project_end_date]);
    }

    function search_accept_mali(Request $request)
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
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'phases.status as f_status', 'projects.status as status', 'phases.status_css as status_css')
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
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', $status);
                })
                ->get();
        }

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

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

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('mali_manager.contracts.index', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function gantt_chart($employer_id = 0)
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

        return view('mali_manager.contracts.gantt_chart', compact('employers', 'employer_info', 'employer_id', 'projects'));
    }

    function check_project_chart_4($project_id)
    {
        $phases = Phase::query()
            ->where('project_id', $project_id)
            ->get();

        return $phases;
    }
}
