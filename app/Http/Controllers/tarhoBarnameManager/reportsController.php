<?php

namespace App\Http\Controllers\tarhoBarnameManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Report;
use Illuminate\Http\Request;

class reportsController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->where('status', 11)
            ->get();
        return view('tarhoBarname_manager.reports.index', ['projects' => $projects]);
    }

    public function report_detail(Project $project)
    {
        $reports = Report::query()
            ->select('*', 'phases.end_date as p_end_date')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->join('projects', 'projects.id', '=', 'reports.project_id')
            ->where('reports.project_id', $project->id)
            ->get();

        return view('tarhoBarname_manager.reports.details', ['project' => $project, 'reports' => $reports]);
    }

    public function report_update(Request $request, Report $report)
    {
        $report->verify = 1;
        $report->amount_payable = $request->remaining;
        $report->remaining = intval($report->cost) - intval($request->remaining);
        $report->save();
        dd(intval($request->remaining));

        $project = Project::query()
            ->where('id', $request->id)
            ->first();
        $project->status = 12;
        $project->save();

//        $report->remaining =


        alert()->success('این فاز تایید شد و به مدیرعامل ارجاع داده شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('tarhoBarname_report_index');
    }

    public function download_file($report)
    {
        $report_info = Report::query()
            ->where('id', $report)
            ->first();

        return response()->download($report_info->file_src);
    }

}
