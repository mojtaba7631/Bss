<?php

namespace App\Http\Controllers\employer;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\SaveTime;
use App\Models\User;
use Illuminate\Http\Request;

class projectController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.created_at as p_created_at')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('employer_id', $user_id)
            ->where('projects.status', 1)
            ->orderByDesc('projects.created_at')
            ->paginate(10);


        foreach ($projects as $project) {
            $project['created_at_jalali'] = verta($project->created_at)->format('d/%B/Y');

            $project['project_error'] = Project_error::query()
                ->where('project_id', $project->project_id)
                ->get();

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }


        return view('employer.projects.index', ['projects' => $projects]);
    }

    public function in_process_projects()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.created_at as p_created_at')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('employer_id', $user_id)
            ->where('projects.status', '!=', 9)
            ->where('projects.status', '=', 1)
            ->orderByDesc('projects.created_at')
            ->paginate(10);


        foreach ($projects as $project) {
            $project['project_error'] = Project_error::query()
                ->where('project_id', $project->project_id)
                ->get();

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users_id = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
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

        $searched = false;

        return view('employer.projects.in_process_projects', ['projects' => $projects, 'users_id' => $users_id, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.created_at as p_created_at')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->where('employer_id', auth()->id())
                ->where('projects.status', '!=', 9)
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
                    $q->where('projects.user_id', $user_id);
                })
                ->where('employer_id', auth()->id())
                ->where('projects.status', '!=', 9)
                ->orderByDesc('projects.created_at')
                ->get();
        }

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users_id = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
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

        return view('employer.projects.in_process_projects', ['projects' => $projects, 'users_id' => $users_id, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);

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

    public function view(Project $project)
    {
        $project_created_at = verta($project->created_at)->format('Y/m/d');
        $project_created_at_jalali = explode(' ', $project_created_at)[0];

        return view('employer.projects.view', ['project' => $project, 'project_created_at_jalali' => $project_created_at_jalali]);
    }

    public function error_message(Project $project)
    {
        $error_messages = Project_error::query()
            ->where('project_id', $project->id)
            ->paginate(10);

        foreach ($error_messages as $error_message) {
            $error_message['created_at'] = verta($error_message->created_at)->format('Y/m/d');
            $error_message['created_at_jalali'] = explode(' ', $error_message['created_at'])[0];

            $error_message['sender'] = User::query()
                ->select('name', 'family')
                ->join('project_errors', 'project_errors.sender', '=', 'users.id')
                ->where('users.id', $error_message->sender)
                ->get();
        }

        return View('employer.projects.error_page', ['project' => $project, 'error_messages' => $error_messages]);
    }

    public function confirmation(Request $request)
    {
        $project = Project::query()
            ->where('id', $request->project_id)
            ->firstOrFail();

        $user_id = $project->user_id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $employer_name =  auth()->user()->name . ' ' . auth()->user()->family;
        $project_title = $project->title;

        if ($request->status == 0) {
            $project->status = 2;
            $project->save();
        }

        SaveTime::query()->create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'role_id' => 3,
            'level' => 1,
            'comments' => 'کارفرما (' . $employer_name . ') پروژه را تایید کرد',
        ]);

        if ($user_info->type == 0) {
            $user_name_info = $user_info->name . ' ' . $user_info->family;
        } else {
            $user_name_info =  $user_info->co_name;
        }

        sms_otp(auth()->user()->mobile, 'userOne', ['param1' => $user_name_info, 'param2' => $project_title, 'param3' => $employer_name . ' تایید']);

        alert()->success('پروژه مورد نظر توسط شما تایید شد.', 'با تشکر')->autoclose(9000);
        return redirect()->route('employer_project_index');
    }

    public function completed_projects()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('employer_id', $user_id)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($projects as $project) {

            $project['project_error'] = Project_error::query()
                ->where('project_id', $project->id)
                ->where('not_active_code', 1)
                ->get();

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }

            $project['user_person'] = User::query()
                ->where('id', $project->user_id)
                ->first();

            $type = $project['user_person']->type;

            if ($type == 1) {
                $project['user_name'] = $project['user_person']->co_name;
            } elseif ($type == 0) {
                $project['user_name'] = $project['user_person']->name . ' ' . $project['user_person']->family;
            }
            $project['reportable'] = $this->get_phase_reportable($project['project_id']);
        }

        $users_id = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
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

        $searched = false;


        return view('employer.projects.full_projects', ['projects' => $projects, 'users_id' => $users_id, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function full_search_index(Request $request)
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
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.created_at as p_created_at')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->where('employer_id', auth()->id())
                ->where('projects.status', 9)
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
                    $q->where('projects.user_id', $user_id);
                })
                ->where('employer_id', auth()->id())
                ->where('projects.status', 9)
                ->orderByDesc('projects.created_at')
                ->get();
        }

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users_id = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
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

        return view('employer.projects.completed_projects', ['projects' => $projects, 'users_id' => $users_id, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);

    }

    public function get_phase_reportable($project_id)
    {
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

        if (file_exists($project_info['file']) and !is_dir($project_info['file'])) {
            unset($project_info['file']);
        }

        $project_info->update([
            'file' => null
        ]);

        if ($project_error_info) {
            $project_error_info->update([
                'message' => $request->message,
                'sender' => $user_id,
                'not_active_code' => 1
            ]);

            $project_info->update([
                'status' => 12,
            ]);
        } else {
            Project_error::create([
                'message' => $request->message,
                'project_id' => $project_id,
                'sender' => $user_id,
                'not_active_code' => 1

            ]);

            $project_info->update([
                'status' => 12,
            ]);
        }

        $user_id = $project_info->user_id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        if ($user_info->type == 0) {
            $user_name_info = $user_info->name . ' ' . $user_info->family;
        } else {
            $user_name_info =  $user_info->co_name;
        }

        $employer_name =  auth()->user()->name . ' ' . auth()->user()->family;

        sms_otp(auth()->user()->mobile, 'userOne', ['param1' => $user_name_info, 'param2' => $project_info->title, 'param3' => $employer_name . ' رد']);

        return response()->json([
            'error' => false,
            'errorMsg' => 'عدم تایید پروژه اعمال شد'
        ]);
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
}
