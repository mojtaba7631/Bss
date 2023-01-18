<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\SystemAlert;

class alertController extends Controller
{
    public function index()
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 0;
        }
        $perPage = 30;
        $offset = ($page * $perPage) - $perPage;
        SystemAlert::query()
            ->select('*', 'system_alerts.created_at as sa_created_at')
            ->where('system_alerts.user_id', auth()->id())
            ->latest('system_alerts.created_at')
            ->offset($offset)
            ->limit($perPage)
            ->update([
                'seen' => 1
            ]);

        $alerts = SystemAlert::query()
            ->select('*', 'system_alerts.created_at as sa_created_at')
            ->join('projects', 'projects.id', '=', 'system_alerts.project_id')
            ->join('phases', 'phases.id', '=', 'system_alerts.phase_id')
            ->where('system_alerts.user_id', auth()->id())
            ->latest('system_alerts.created_at')
            ->paginate(10);

        foreach ($alerts as $alert) {
            $alert['jalali_date'] = $this->convertDateToJalali($alert['sa_created_at']);
        }

        return view('supervisor.alerts.index', compact('alerts'));
    }

    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }
}
