<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\SystemAlert;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class checkProjectsAlert
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = auth()->id();

        $compare_date = date('Y-m-d', strtotime('+2 days')) . ' 00:00:00';

        $projects_needs_alert = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->where('projects.user_id', $user_id)
            ->where('projects.signed_by_user', 1)
            ->where('phases.phase_number', '!=', 0)
            ->where('phases.end_date', '<=', $compare_date)
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'message' => 'دو روز مانده به تحویل فاز',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }

        $compare_date_today = date('Y-m-d') . ' 00:00:00';

        $projects_needs_alert_today = Project::query()
            ->select('*', 'phases.id as phase_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->where('projects.user_id', $user_id)
            ->where('projects.signed_by_user', 1)
            ->where('phases.end_date', '<=', $compare_date_today)
            ->orderBy('phases.end_date')
            ->get();

        foreach ($projects_needs_alert_today as $item) {
            $array = [
                'project_id' => $item->project_id,
                'phase_id' => $item->phase_id,
                'message' => 'امروز روز تحویل فاز است',
            ];
            $res = SystemAlert::query()->firstOrNew($array);
            $res->save();
        }

        return $next($request);
    }
}
