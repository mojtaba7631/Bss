<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Delay;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class reportsController extends Controller
{
    function index()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'reports.id as report_id', 'projects.title as p_title', 'phases.status as f_status', 'phases_status.title as s_title')
            ->join('reports', 'reports.project_id', '=', 'projects.id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->where('phases.status', 2)
            ->where('projects.supervisor_id', $user_id)
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

        return view('supervisor.reports.index', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function search_index(Request $request)
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

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }

        $projects = Project::query()
            ->select('*', 'reports.id as report_id', 'projects.title as p_title', 'projects.status as status')
            ->join('reports', 'reports.project_id', '=', 'projects.id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->when($code != 0, function ($q) use ($code) {
                $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
            })
            ->when($title != '', function ($q) use ($title) {
                $q->where('projects.title', 'like', '%' . $title . '%');
            })
            ->when($employer != 0, function ($q) use ($employer) {
                $q->where('employer_id', $employer);
            })
            ->where('phases.status', 2)
            ->where('projects.supervisor_id', $user_id)
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

        return view('supervisor.reports.index', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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

    function report_detail(Project $project)
    {
        $reports = Report::query()
            ->where('project_id', $project->id)
            ->get();

        return view('supervisor.reports.details', ['project' => $project, 'reports' => $reports]);
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

    function report_accept(Request $request)
    {
        $phase_id = $request->phase_id;

        $report_info = Report::query()
            ->where('phases_id', $phase_id)
            ->first();

        $phases = Phase::query()
            ->where('id', $phase_id)
            ->first();

        $today = \Carbon\Carbon::now();

        $day_count = $this->calculate_delayed($today, $report_info->created_at);

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
                    'day_count' => $day_count - 3,
                    'role' => 4
                ]);
            } else {
                //update
                $delay_info->update([
                    'day_count' => $delay_info['day_count'] + ($day_count - 3),
                ]);
            }
        }


        $phases->update([
            'status' => 3
        ]);

        alert()->success('این فاز تایید شد و به کارفرما ارجاع داده شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('supervisor_report_index');
    }

    function report_reject(Request $request)
    {
        $input = $request->all();

        $phase_id = $request->phase_id;

        $validation = Validator::make($input, [
            'description' => "required|string|max:5000"
        ]);

        if ($validation->fails()) {
            alert()->error($validation->errors()->first(), 'خطا')->autoclose(9000);
            return back();
        }

        Report::query()
            ->where('phases_id', $phase_id)
            ->delete();

        $phase = Phase::query()
            ->where('id', $phase_id)
            ->first();

        $phase->update([
            'status' => 1
        ]);

        Project_error::create([
            'project_id' => $phase['project_id'],
            'message' => $input['description'],
            'sender' => auth()->id(),
        ]);

        alert()->success('این فاز رد شد و به مجری ارجاع داده شد', 'با تشکر')->autoclose(9000);
        return redirect()->route('supervisor_report_index');
    }

    function download_file($report)
    {
        $report_info = Report::query()
            ->where('id', $report)
            ->firstOrFail();

        return response()->download($report_info->file_src);
    }

    function Accept_report()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'reports.id as report_id', 'projects.title as p_title', 'phases.status as f_status', 'phases_status.title as s_title')
            ->join('reports', 'reports.project_id', '=', 'projects.id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->where('phases.status', 3)
            ->where('projects.supervisor_id', $user_id)
            ->paginate(10);

        foreach ($projects as $project) {
            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        return view('supervisor.reports.AcceptReports', ['projects' => $projects]);
    }

    function fullReport()
    {
        $user_id = auth()->user()->id;

        $projects = Project::query()
            ->select('*', 'reports.id as report_id', 'projects.title as p_title', 'phases.status as f_status', 'project_status.title as s_title')
            ->join('reports', 'reports.project_id', '=', 'projects.id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('projects.supervisor_id', $user_id)
            ->latest('projects.created_at')
            ->paginate(15);

        foreach ($projects as $project) {
            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $searched = false;
        return view('supervisor.reports.fullReports', ['projects' => $projects, 'searched' => $searched]);
    }

    function searchFullReport(Request $request)
    {
        $user_id = auth()->user()->id;

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

        $projects = Project::query()
            ->select('*', 'reports.id as report_id', 'projects.title as p_title', 'phases.status as f_status', 'project_status.title as s_title')
            ->join('reports', 'reports.project_id', '=', 'projects.id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->when($code != 0, function ($q) use ($code) {
                $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
            })
            ->when($title != '', function ($q) use ($title) {
                $q->where('projects.title', 'like', '%' . $title . '%');
            })
            ->where('projects.supervisor_id', $user_id)
            ->latest('projects.created_at')
            ->paginate(15);

        foreach ($projects as $project) {
            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $searched = true;
        return view('supervisor.reports.fullReports', ['projects' => $projects, 'searched' => $searched]);
    }

    function get_phase_reportable($project_id)
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
}
