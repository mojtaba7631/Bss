<?php

namespace App\Http\Controllers\realUser;

use App\Http\Controllers\Controller;
use App\Models\Delay;
use App\Models\Payment;
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
    public function index()
    {
        $user_id = auth()->id();

        $reports = Report::query()
            ->select('*', 'projects.title as p_title', 'phases.phase_number as f_number', 'reports.id as report_id', 'projects.status as p_status')
            ->join('projects', 'projects.id', 'reports.project_id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->where('projects.user_id', $user_id)
            ->orderByDesc('projects.created_at')
            ->orderByDesc('reports.project_id')
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
                $project['reportable'] = false;
            }
        }

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;


        return view('realUser.reports.index', ['reports' => $reports, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function search_index_real(Request $request)
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

        $reports = Report::query()
            ->select('*', 'projects.title as p_title', 'phases.phase_number as f_number', 'reports.id as report_id', 'projects.status as p_status')
            ->join('projects', 'projects.id', 'reports.project_id')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
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
            ->orderByDesc('projects.created_at')
            ->orderByDesc('reports.project_id')
            ->get();

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
                ->where('project_id', $report->project_id)
                ->get();

            if ($report->p_status == 8) {
                $result = $this->check_project_phase($report->project_id);
                $report['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
                $report['reportable'] = $this->get_phase_reportable($report['project_id']);
            } else {
                $report['reportable'] = false;
            }
        }

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('realUser.reports.index', ['reports' => $reports, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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

    public function detail_report(Project $project)
    {

        $phases = Phase::query()
            ->select('*', 'phases.phase_number as f_phase_number', 'phases.id as f_id')
            ->leftJoin('reports', 'reports.phases_id', 'phases.id')
            ->where('phases.project_id', $project->id)
            ->where('phase_number', '!=', 0)
            ->where('reports.id', null)
            ->get();

        return view('realUser.reports.details_report', ['phases' => $phases, 'projects' => $project]);
    }

    public function download_file($report)
    {
        $report_info = Report::query()
            ->where('id', $report)
            ->first();
//
//
//        $payment_info = Payment::query()
//            ->where('project_id', $report_info->project_id)
//            ->firstOrFail();

        return response()->download($report_info->file_src);
    }

    public function download_finance_file($report)
    {
        $report_info = Report::query()
            ->where('id', $report)
            ->firstOrFail();

        return response()->download($report_info->file_src);
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

    public function upload_report(Request $request)
    {

        $today = \Carbon\Carbon::now();

        $input = $request->all();

        $validator = Validator::make($input, [
            'file_src' => 'required|mimes:pdf,doc,docx,zip',
            'phase' => 'required'
        ]);

        if ($validator->fails()) {
            alert()->error($validator->errors()->first());
            return back()->withInput()->withErrors($validator->errors());
        }

        $file = $request->file('file_src');
        $file_ext = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_ext;
        $files = $file->move('reports', $file_name);

        Report::create([
            'project_id' => $request->project,
            'phases_id' => $request->phase,
            'file_src' => $files,
            'comments' => $request->comments,
        ]);

        $phase_info = Phase::query()
            ->where('id', $request->phase)
            ->first();


        $day_count = $this->calculate_delayed($today, $phase_info->end_date);

        if ($day_count < 0) {
            //has delay
            $day_count = abs($day_count);

            $delay_info = Delay::query()
                ->where('delay.user_id', auth()->user()->id)
                ->first();


            if (!$delay_info) {
                //add
                Delay::create([
                    'user_id' => auth()->user()->id,
                    'day_count' => $day_count,
                    'role' => 8
                ]);
            } else {
                //update
                $delay_info->update([
                    'day_count' => $delay_info['day_count'] + $day_count,
                ]);
            }
        }


        $phase_info->update([
            'status' => 2
        ]);

        alert()->success('گزارش شما برای ناظر ارسال گردید', 'با تشکر')->autoclose(9000);

        return redirect()->route('real_reports_index');

    }

    function real_get_finance_docs($phase_id)
    {
        $documents = Payment::query()
            ->where('phase_id', $phase_id)
            ->where('status', 1)
            ->get();

        return response()->json([
            'error' => false,
            'documents' => $documents,
        ]);
    }

    public function download_finance_doc($payment_id)
    {
        $payment_info = Payment::query()
            ->where('id', $payment_id)
            ->first();

        if (file_exists($payment_info->payment_file) and !is_dir($payment_info->payment_file)) {
            return response()->download($payment_info->payment_file);
        } else {
            abort(404);
        }
    }
}
