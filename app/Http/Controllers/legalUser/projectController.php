<?php

namespace App\Http\Controllers\legalUser;

use App\Http\Controllers\Controller;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class projectController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.created_at as p_created_at')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('projects.user_id', $user_id)
            ->where('projects.status', '!=', 9)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $project['read_message_count'] = Project_error::query()
                ->where('project_id', $project->project_id)
                ->where('read_message', 0)
                ->count();
            $project['employer'] = User::query()
                ->where('id', $project->employer_id)
                ->first();

            $supervisor = User::query()
                ->where('id', $project->supervisor_id)
                ->first();

            if ($supervisor) {
                $project['supervisor'] = $supervisor['name'] . ' ' . $supervisor['family'];
            } else {
                $project['supervisor'] = '-';
            }

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
                $project['reportable'] = $this->get_phase_reportable($project['project_id']);
            } else {
                $project['reportable'] = false;
            }
        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 5)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        $searched = false;

        return view('legalUser.projects.index', ['projects' => $projects, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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
            ->where('projects.user_id', auth()->user()->id)
            ->where('projects.status', '!=', 9)
            ->orderByDesc('projects.created_at')
            ->get();

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $project['read_message_count'] = Project_error::query()
                ->where('project_id', $project->project_id)
                ->where('read_message', 0)
                ->count();
            $project['employer'] = User::query()
                ->where('id', $project->employer_id)
                ->first();

            $supervisor = User::query()
                ->where('id', $project->supervisor_id)
                ->first();

            if ($supervisor) {
                $project['supervisor'] = $supervisor['name'] . ' ' . $supervisor['family'];
            } else {
                $project['supervisor'] = '-';
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

        return view('legalUser.projects.index', ['projects' => $projects, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);

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

    public function add()
    {
        $employers = User::query()
            ->select('*', 'users.id as user_id')
            ->join("user_role", "user_role.user_id", "=", "users.id")
            ->where('roles', 3)
            ->get();

        return view('legalUser.projects.add', compact("employers"));
    }

    public function edit(Project $project)
    {
        $employer_users = User::query()
            ->select('*', 'users.id as user_id')
            ->join("user_role", "user_role.user_id", "=", "users.id")
            ->where('roles', 3)
            ->get();

        $user_info = User::query()
            ->where('id', $project->employer_id)
            ->first();

        return view('legalUser.projects.edit', compact("project", "employer_users"));
    }

    public function update($project, Request $request)
    {
        $input = $request->all();

        $project_info = Project::query()
            ->where('id', $project)
            ->first();

        $user_id = $project_info->user_id;

        $employer_id = $request->employer;

        $validator = Validator::make($input, [
            'title' => 'required',
            'subject' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,zip,rar',
        ]);

        if ($validator->fails()) {
            alert()->error($validator->errors()->first());
            return back()->withInput()->withErrors($validator->errors());
        }

        $file = $request->file('file');
        $file_ext = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_ext;
        $files = $file->move('files', $file_name);

        $project_info->update([
            'title' => $request->title,
            'subject' => $request->subject,
            'remaining' => 0,
            'user_id' => $user_id,
            'comment' => $request->comment,
            'file' => $files,
            'status' => 1
        ]);

        alert()->success('ویرایش پروژه انجام شد و به کارفرما ارجاع داده شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('legal_project_in_process');
    }

    public function create(Request $request)
    {
        $user_id = auth()->user()->id;

        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'employer' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,doc,docx,zip,rar,xlsx,xls',
        ]);

        if ($validator->fails()) {
            alert()->error($validator->errors()->first());
            return back()->withInput()->withErrors($validator->errors());
        }

        $employer_info = User::query()
            ->where('id', $request->employer)
            ->first();

        $employ_name = $employer_info->name . ' ' . $employer_info->family;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        if ($user_info->type == 0) {
            $real_legal_name = $user_info->name . ' ' . $user_info->family;
        } elseif ($user_info->type == 1) {
            $real_legal_name = $user_info->co_name;
        }

        $file = $request->file('file');
        $file_ext = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_ext;
        $files = $file->move('files', $file_name);

        $project = new Project();
        $project->title = $request->title;
        $project->comment = $request->comment;
        $project->employer_id = $request->employer;
        $project->remaining = 0;
        $project->user_id = $user_id;
        $project->file = $files;
        $project->save();

        sms_otp($employer_info->mobile, 'employerTwo', ['param1' => $employ_name, 'param2' => $request->title, 'param3' => $real_legal_name]);

        return redirect()->route('legal_project_in_process');
    }

    public function error_message(Project $project)
    {
        $error_messages = Project_error::query()
            ->select('*', 'users.created_at as u_created_at', 'project_errors.created_at as p_created_at')
            ->join('users', 'project_errors.sender', '=', 'users.id')
            ->where('project_errors.project_id', $project->id)
            ->paginate(10);

        $error_project = Project_error::query()
            ->where('project_id', $project->id)
            ->first();

        $error_project->update([
            'read_message' => 1
        ]);

        foreach ($error_messages as $error_message) {
            $error_message['created_at'] = verta($error_message->p_created_at)->format('d/%B/Y');
            $error_message['created_at_jalali'] = explode(' ', $error_message['created_at'])[0];
        }

        return View('legalUser.projects.error_page', ['project' => $project, 'error_messages' => $error_messages]);
    }

    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }

    public function completed_projects()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('user_id', $user_id)
            ->where('projects.status', 9)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            $project['project_error'] = Project_error::query()
                ->where('project_id', $project->id)
                ->where('not_active_code', 1)
                ->get();

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();


        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 5)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        $searched = false;


        return view('legalUser.projects.completed_projects', ['projects' => $projects, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function project_completed_Search(Request $request)
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

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
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
            ->where('projects.user_id', auth()->user()->id)
            ->where('projects.status', 9)
            ->orderByDesc('projects.created_at')
            ->get();

        foreach ($projects as $project) {
            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
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

        return view('legalUser.projects.completed_projects', ['projects' => $projects, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);

    }

    public function delete_project(Request $request)
    {
        $project_info = Project::query()
            ->where('id', $request->delete_project_id)
            ->first();


        Project::query()
            ->where('id', $request->delete_project_id)
            ->delete();

        alert()->success('پروژه مورد نظر حذف گردید', 'با تشکر')->autoclose(9000);
        return back();
    }

    public function download_propusal($project)
    {
        $project_info = Project::query()
            ->where('id', $project)
            ->firstOrFail();
        return response()->download($project_info->file);
    }
}
