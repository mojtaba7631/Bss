<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project_error;
use App\Models\project_status;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class reportController extends Controller
{
    public  function index($project)
    {
        $project_id = $project;

//        $reports = Report::query()
//            ->select('*', 'projects.title as p_title', 'phases.phase_number as f_number', 'reports.id as report_id', 'projects.status as p_status')
//            ->join('projects', 'projects.id', 'reports.project_id')
//            ->join('phases', 'phases.id', '=', 'reports.phases_id')
//            ->where('reports.project_id', $project_id)
//            ->where('phases.phase_number', '!=', 0)
//            ->orderByDesc('projects.created_at')
//            ->orderByDesc('reports.project_id')
//            ->paginate(10);

        $reports = Report::query()
            ->select('*', 'projects.title as p_title', 'phases.phase_number as f_number','reports.id as report_id', 'projects.status as p_status')
            ->join('projects','projects.id','=','reports.project_id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->where('phases.phase_number', '!=', 0)
            ->where('reports.project_id', $project_id)
            ->paginate(10);

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($reports as $report) {
            $report['end_date_jalali'] = verta($report->end_date)->format('d/%B/Y');

            $report['project_error'] = Project_error::query()
                ->where('project_id', $report->id)
                ->get();

            if ($report->p_status == 8) {
                $result = $this->check_project_phase($report->project_id);
                $report['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
                $report['reportable'] = $this->get_phase_reportable($report['project_id']);
            } else {
                $report['reportable'] = false;
            }
            $report['documents'] = $this->get_financial_document($report->phases_id);
        }


        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('expert.reports.index', ['reports' => $reports, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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
    public function get_financial_document($phase_id)
    {
        $documents = Payment::query()
            ->where('phase_id', $phase_id)
            ->get();

        return $documents;
    }

    public function download_file($report)
    {
        $report_info = Report::query()
            ->where('phases_id', $report)
            ->firstOrFail();

        return response()->download($report_info->file_src);
    }
}
