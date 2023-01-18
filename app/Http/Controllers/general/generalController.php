<?php

namespace App\Http\Controllers\general;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SystemAlert;
use Carbon\Carbon;

class generalController extends Controller
{
    function checkProjectsAlert()
    {
        $projects_needs_alert = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->where('projects.signed_by_user', 1)
            ->where('phases.phase_number', '!=', 0)
            ->where('phases.status', 1)
            ->whereDate('phases.end_date', Carbon::today()->subDays(2))
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'user_id' => $item->user_id,
                'message' => 'دو روز مانده به ارسال گزارش',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }

        $projects_needs_alert_today = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->where('projects.signed_by_user', 1)
            ->where('phases.phase_number', '!=', 0)
            ->where('phases.status', 1)
            ->whereDate('phases.end_date', Carbon::today())
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert_today as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'user_id' => $item->user_id,
                'message' => 'امروز روز ارسال گزارش فاز ' . $item->phase_number . ' است',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }
    }

    function checkSupervisorAlert()
    {
        $projects_needs_alert = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->where('projects.signed_by_user', 1)
            ->where('phases.phase_number', '!=', 0)
            ->where('phases.status', 2)
            ->whereDate('reports.created_at', '=', Carbon::today()->subDays(2))
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'user_id' => $item->supervisor_id,
                'message' => 'دو روز فرصت دارید تا گزارش فاز ' . $item->phase_number . ' را تایید نمایید',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }

        $projects_needs_alert_today = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->where('projects.signed_by_user', 1)
            ->where('phases.phase_number', '!=', 0)
            ->where('phases.status', 2)
            ->whereDate('reports.created_at', Carbon::today())
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert_today as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'user_id' => $item->supervisor_id,
                'message' => 'امروز روز آخرین مهلت تایید گزارش فاز ' . $item->phase_number . ' می باشد.',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }
    }

    function checkEmployerAlert()
    {
        $projects_needs_alert = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->where('projects.signed_by_user', 1)
            ->where('phases.phase_number', '!=', 0)
            ->where('phases.status', 3)
            ->whereDate('phases.updated_at', Carbon::today()->addDays(2))
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'user_id' => $item->employer_id,
                'message' => 'دو روز فرصت دارید تا گزارش فاز ' . $item->phase_number . ' را تایید نمایید',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }

        $projects_needs_alert_today = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->where('projects.signed_by_user', 1)
            ->where('phases.phase_number', '!=', 0)
            ->where('phases.status', 3)
            ->whereDate('phases.updated_at', Carbon::today())
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert_today as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'user_id' => $item->employer_id,
                'message' => 'امروز روز آخرین مهلت تایید گزارش فاز ' . $item->phase_number . ' می باشد.',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }
    }

    function clearAlert()
    {
        $compare_date = date('Y-m-d', strtotime('-7 days')) . ' 00:00:00';

        SystemAlert::query()
            ->where('created_at', '=', $compare_date)
            ->where('seen', '=', 1)
            ->delete();

        return;
    }
}
