<?php

namespace App\Http\Controllers\mainManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DebtsContorller extends Controller
{
    public function index()
    {
        $user_info = User::query()
            ->select("*", "role.title as role_title", "users.id as user_id")
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->orderBy('user_role.roles')
            ->paginate(10);

        foreach ($user_info as $user) {
            $user['pro_count'] = Project::query()
                ->where('user_id', $user->user_id)
                ->count();

            $user['current_projects'] = Project::query()
                ->where('user_id', $user->user_id)
                ->where('status', 8)
                ->count();

            $user['settled_projects'] = Project::query()
                ->where('user_id', $user->user_id)
                ->where('status', 9)
                ->count();

            $user['payment_total'] = $this->get_debt_projects($user->user_id);
            $user['total_price'] = $this->get_project_total_sum($user->user_id);
        }

        $searched = false;
        return view('main_manager.debts.index', ['user_info' => $user_info, 'searched' => $searched]);
    }

    public function get_debt_projects($user_id)
    {
        $project_total_sum = Project::query()
            ->where('user_id', $user_id)
            ->sum("contract_cost");

        $project_paid_sum = Project::query()
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('payments', 'payments.phase_id', '=', 'phases.id')
            ->where('user_id', $user_id)
            ->where('payments.status', 1)
            ->sum("payments.price");

        $remaining = $project_total_sum - $project_paid_sum;

        return $remaining;
    }

    public function get_project_total_sum($user_id)
    {
        $project_total_sum = Project::query()
            ->where('user_id', $user_id)
            ->sum("contract_cost");
        return $project_total_sum;
    }
}
