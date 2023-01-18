<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class contractController extends Controller
{
    public function Accept_contract()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->paginate(10);

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        return view('admin.contracts.full_contract', ['projects' => $projects]);
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
    public function signed_minot_admin(Project $project)
    {
        $user = User::query()->where('id', $project->user_id)->first();

        $account = Account::query()
            ->select('*', 'accounts.id as a_id', 'banks.id as b_id', 'users.id as u_id')
            ->join('users', 'users.id', 'accounts.user_id')
            ->join('banks', 'banks.id', 'accounts.bank')
            ->first();

        $user_co_reg_date = verta($user->co_reg_date)->format('d/%B/Y');


        $project_start_date = verta($project->start_date)->format('d/%B/Y');

        $project_end_date = verta($project->end_date)->format('d/%B/Y');

        $user_type = User::query()
            ->where('id', $project->user_id)
            ->first();

        $user_type = $user_type->type;

        $phases = Phase::query()
            ->where('project_id', $project->id)
            ->get();

        foreach ($phases as $phase) {
            $phase['start_date_jalali'] = verta($phase->start_date)->format('d/%B/Y');
            $phase['end_date_jalali'] = verta($phase->end_date)->format('d/%B/Y');
        }

        $employer_info = User::query()
            ->where('id', $project->employer_id)
            ->first();

        $supervisor_info = User::query()
            ->where('id', $project->supervisor_id)
            ->first();

        $user_info = User::query()
            ->where('id', $project->user_id)
            ->first();
        if ($user_info->type = 0) {
            $user_name = $user_info->name . ' ' . $user_info->family;
            $user_Signature_img = $user_info->Signature_img;
        } elseif ($user_info->type = 1) {
            $user_name = $user_info->ceo_name . ' ' . $user_info->ceo_family;
            $user_Signature_img = $user_info->Signature_img;
        }
        $employer_name = $employer_info->name . ' ' . $employer_info->family;
        $employer_Signature_img = $employer_info->Signature_img;

        $supervisor_name = $supervisor_info->name . ' ' . $supervisor_info->family;
        $supervisor_Signature_img = $supervisor_info->Signature_img;

        return view('share_minot.print_by_sign', ['user_co_reg_date' => $user_co_reg_date, 'user_type' => $user_type, 'phases' => $phases, 'user_Signature_img' => $user_Signature_img, 'supervisor_Signature_img' => $supervisor_Signature_img, 'employer_Signature_img' => $employer_Signature_img, 'user_name' => $user_name, 'supervisor_name' => $supervisor_name, 'employer_name' => $employer_name, 'project' => $project, 'user' => $user, 'account' => $account, 'project_start_date' => $project_start_date, 'project_end_date' => $project_end_date]);
    }
}
