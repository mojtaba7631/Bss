<?php

namespace App\Http\Controllers\maliManager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;

class proceedingController extends Controller
{
    function index($payment_id)
    {
        $payment_info = Payment::query()
            ->where('id', $payment_id)
            ->firstOrFail();

        $phase_info = Phase::query()
            ->where('id', $payment_info['phase_id'])
            ->firstOrFail();


        $project_info = Project::query()
            ->select('*', 'users.type as user_type')
            ->join('users', 'users.id', '=', 'projects.user_id')
            ->where('projects.id', $phase_info['project_id'])
            ->firstOrFail();

        $report_info = Report::query()
            ->where('phases_id', $payment_info['phase_id'])
            ->first();

        if (!$report_info) {
            $report_date = verta($project_info->start_date)->format('Y/m/d');
            $report_info['jalali_report_date'] = explode(' ', $report_date)[0];
        } else {
            $report_date = verta($report_info->created_at)->format('Y/m/d');
            $report_info['jalali_report_date'] = explode(' ', $report_date)[0];
        }


        $supervisor_info = User::query()
            ->where('id', $project_info['supervisor_id'])
            ->firstOrFail();

        $employer_info = User::query()
            ->where('id', $project_info['employer_id'])
            ->firstOrFail();

        $main_manager = User::query()
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->where('user_role.roles', 5)
            ->firstOrFail();

        if (intval($project_info['user_type']) == 1) {
            $user_name = $project_info['co_name'];
        } else {
            $user_name = $project_info['name'] . " " . $project_info['family'];
        }

        return view('mali_manager.proceeding.proceeding', compact('project_info', 'phase_info', 'report_info', 'supervisor_info', 'user_name', 'payment_info', 'employer_info', 'main_manager'));
    }
}
