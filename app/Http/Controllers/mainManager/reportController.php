<?php

namespace App\Http\Controllers\mainManager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class reportController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->select('*', 'reports.id as report_id', 'projects.title as p_title', 'phases.status as f_status', 'phases_status.title as s_title')
            ->join('reports', 'reports.project_id', '=', 'projects.id')
            ->join('phases', 'reports.phases_id', '=', 'phases.id')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->where('phases.status', 4)
            ->paginate(10);

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

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
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

        return view('main_manager.reports.index', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function search_index(Request $request)
    {
        $input = $request->all();
        $user_id = auth()->user()->id;

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


        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.status as status')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->join('phases', 'projects.id', '=', 'phases.project_id')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
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
            ->get();

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

        return view('main_manager.reports.index', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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

    public function report_detail(Project $project)
    {
//        dd($project->id);

        $reports = Report::query()
            ->where('project_id', $project->id)
            ->get();

        return view('main_manager.reports.details', ['project' => $project, 'reports' => $reports]);

    }

    public function report_update(Request $request)
    {

        $phase_id = $request->accept_phases_id;
        $phases = Phase::query()
            ->where('id', $phase_id)
            ->first();

        $phases->update([
            'status' => 5
        ]);


        alert()->success('این فاز تایید شد و به طرح و برنامه ارجاع داده شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('mainManager_report_index');
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

    public function download_file($report)
    {
        $report_info = Report::query()
            ->where('id', $report)
            ->first();

        return response()->download($report_info->file_src);
    }
}
