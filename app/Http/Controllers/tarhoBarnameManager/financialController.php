<?php

namespace App\Http\Controllers\tarhoBarnameManager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\project_status;
use App\Models\User;
use Illuminate\Http\Request;

class financialController extends Controller
{
//    public function index_detail($user_id)
//    {
//        $projects = Project::query()
//            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
//                'phases.status as f_status', 'projects.status as p_status', 'reports.phases_id as ph_id', 'reports.id as report_id')
//            ->join('phases', 'phases.project_id', '=', 'projects.id')
//            ->join('project_status', 'project_status.id', '=', 'projects.status')
//            ->join('reports', 'reports.phases_id', '=', 'phases.id')
//            ->where('phases.status', 5)
//            ->where('projects.status', 8)
//            ->where('projects.user_id', $user_id)
//            ->get();
//
//        $projectsGroupByProject = [];
//        foreach ($projects as $project) {
//            $project['created_at'] = verta($project->created_at)->format('Y/m/d');
//            $project['created_at_jalali'] = explode(' ', $project['created_at'])[0];
//
//            if ($project->p_status == 8) {
//                $result = $this->check_project_phase($project->project_id);
//                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
//
//            }
//
//            $project['user_info_name'] = User::query()
//                ->where('id', $project->user_id)
//                ->first();
//
//            if (!isset($projectsGroupByProject[$project['project_id']])) {
//                $projectsGroupByProject[$project['project_id']] = [];
//                $projectsGroupByProject[$project['project_id']]['total_price'] = intval($project['contract_cost']);
//                $projectsGroupByProject[$project['project_id']]['user_id'] = $project['user_id'];
//                $projectsGroupByProject[$project['project_id']]['s_title'] = $project['s_title'];
//                $projectsGroupByProject[$project['project_id']]['p_title'] = $project['p_title'];
//                $projectsGroupByProject[$project['project_id']]['status_css'] = $project['status_css'];
//                $projectsGroupByProject[$project['project_id']]['project_id'] = $project['project_id'];
//                $projectsGroupByProject[$project['project_id']]['subject'] = $project['subject'];
//                $projectsGroupByProject[$project['project_id']]['project_unique_code'] = $project['project_unique_code'];
//                $projectsGroupByProject[$project['project_id']]['supervisor'] = $project->supervisor->name . ' ' . $project->supervisor->family;
//                if($project['user_info_name']->type == 1){
//                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->co_name;
//                } else {
//                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->name . ' ' . $project['user_info_name']->family;
//                }
//            } else {
//                $projectsGroupByProject[$project['project_id']]['total_price'] += intval($project['contract_cost']);
//            }
//        }
//
//        $employers = User::query()
//            ->join('user_role', 'user_role.user_id', 'users.id')
//            ->where('user_role.roles', 3)
//            ->get();
//
//        $supervisors = User::query()
//            ->join('user_role', 'user_role.user_id', 'users.id')
//            ->where('user_role.roles', 4)
//            ->get();
//
//        $users = User::query()
//            ->join('user_role', 'user_role.user_id', 'users.id')
//            ->where('user_role.roles', 7)
//            ->orWhere('user_role.roles', 8)
//            ->get();
//
//        $projects_status = project_status::query()
//            ->where('id', '!=', 8)
//            ->where('id', '!=', 11)
//            ->where('id', '!=', 12)
//            ->get();
//
//        $phases_status = phases_status::query()
//            ->get();
//
//        foreach ($phases_status as $phase_status) {
//            $phase_status['id'] += 100;
//        }
//
//        $searched = false;
//
//        return view('tarhoBarname_manager.financial.index', ['projectsGroupByProject' => $projectsGroupByProject,'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
//    }
    public function index_detail()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
                'phases.status as f_status', 'projects.status as p_status', 'reports.phases_id as ph_id', 'reports.id as report_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->where('phases.status', 5)
            ->where('projects.status', 8)
            ->get();

        $projectsGroupByProject = [];
        foreach ($projects as $project) {
            $project['created_at'] = verta($project->created_at)->format('Y/m/d');
            $project['created_at_jalali'] = explode(' ', $project['created_at'])[0];

            if ($project->p_status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];

            }

            $project['user_info_name'] = User::query()
                ->where('id', $project->user_id)
                ->first();

            if (!isset($projectsGroupByProject[$project['project_id']])) {

                $projectsGroupByProject[$project['project_id']] = [];
                $financial_res = $this->calculate_total_payed_reminding($project['project_id'], $project['contract_cost'], 0);
                $projectsGroupByProject[$project['project_id']]['reminding'] = $financial_res[2];
                $projectsGroupByProject[$project['project_id']]['has_force'] = $this->check_is_force($project['project_id']);
                $projectsGroupByProject[$project['project_id']]['total_price'] = floatval($project['contract_cost']);
                $projectsGroupByProject[$project['project_id']]['payed'] = $financial_res[0];
                $projectsGroupByProject[$project['project_id']]['user_id'] = $project['user_id'];
                $projectsGroupByProject[$project['project_id']]['s_title'] = $project['s_title'];
                $projectsGroupByProject[$project['project_id']]['p_title'] = $project['p_title'];
                $projectsGroupByProject[$project['project_id']]['status_css'] = $project['status_css'];
                $projectsGroupByProject[$project['project_id']]['project_id'] = $project['project_id'];
                $projectsGroupByProject[$project['project_id']]['subject'] = $project['subject'];
                $projectsGroupByProject[$project['project_id']]['project_unique_code'] = $project['project_unique_code'];
                $projectsGroupByProject[$project['project_id']]['supervisor'] = $project->supervisor->name . ' ' . $project->supervisor->family;
                if ($project['user_info_name']->type == 1) {
                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->co_name;
                } else {
                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->name . ' ' . $project['user_info_name']->family;
                }
            }
            // else {
            //     $projectsGroupByProject[$project['project_id']]['total_price'] += intval($project['contract_cost']);
            // }
        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('tarhoBarname_manager.financial.index', ['projectsGroupByProject' => $projectsGroupByProject, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function calculate_total_payed_reminding($project_id, $total, $is_force)
    {
        $payed = Payment::query()
            ->where('project_id', $project_id)
            ->where('status', 1)
            // ->where('is_force', $is_force)
            ->sum('price');

        $reminding = floatval($total) - $payed;

        return [$payed, $total, $reminding];
    }

    public function financial_index()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
                'phases.status as f_status', 'projects.status as p_status', 'reports.phases_id as ph_id', 'reports.id as report_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->where('phases.status', 5)
            ->where('projects.status', 8)
            ->get();

        $projectsGroupByUser = [];
        foreach ($projects as $project) {
            $project['created_at'] = verta($project->created_at)->format('Y/m/d');
            $project['created_at_jalali'] = explode(' ', $project['created_at'])[0];

            if ($project->p_status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];

            }

            $project['user_info_name'] = User::query()
                ->where('id', $project->user_id)
                ->first();

            if (!isset($projectsGroupByUser[$project['user_id']])) {
                $projectsGroupByUser[$project['user_id']] = [];
                $projectsGroupByUser[$project['user_id']]['total_price'] = intval($project['contract_cost']);
                $projectsGroupByUser[$project['user_id']]['user_id'] = $project['user_id'];
                $projectsGroupByUser[$project['user_id']]['s_title'] = $project['s_title'];
                $projectsGroupByUser[$project['user_id']]['p_title'] = $project['p_title'];
                $projectsGroupByUser[$project['user_id']]['status_css'] = $project['status_css'];
                $projectsGroupByUser[$project['user_id']]['project_id'] = $project['project_id'];
                $projectsGroupByUser[$project['user_id']]['subject'] = $project['subject'];
                $projectsGroupByUser[$project['user_id']]['supervisor'] = $project->supervisor->name . ' ' . $project->supervisor->family;
                if ($project['user_info_name']->type == 1) {
                    $projectsGroupByUser[$project['user_id']]['user_info_name'] = $project['user_info_name']->co_name;
                } else {
                    $projectsGroupByUser[$project['user_id']]['user_info_name'] = $project['user_info_name']->name . ' ' . $project['user_info_name']->family;
                }
            } else {
                $projectsGroupByUser[$project['user_id']]['total_price'] += intval($project['contract_cost']);
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

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('tarhoBarname_manager.financial.financial_first', ['projectsGroupByUser' => $projectsGroupByUser, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function search_index(Request $request)
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

        if ($request->has('supervisor') and $input['supervisor'] != '') {
            $supervisor = $input['supervisor'];
        } else {
            $supervisor = 0;
        }

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }

        if ($request->has('user_id') and $input['user_id'] != '') {
            $user_id = $input['user_id'];
        } else {
            $user_id = 0;
        }

        if ($request->has('status') and $input['status'] != '') {
            $status = intval($input['status']);
        } else {
            $status = 0;
        }

        if ($status > 100) {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'phases.status as f_status', 'projects.status as p_status')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 8);
                    $q->where('phases.status', ($status - 100));
                })
                ->where('phases.status', 5)
                ->where('projects.status', 8)
                ->get();
        } else {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'phases.status as f_status', 'projects.status as p_status')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', $status);
                })
                ->where('phases.status', 5)
                ->where('projects.status', 8)
                ->get();
        }

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');

            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
            $project['user_info_name'] = User::query()
                ->where('id', $project->user_id)
                ->first();
        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('tarhoBarname_manager.financial.index', ['users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);

    }

    public function force_index()
    {
        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
                'phases.status as f_status', 'projects.status as p_status', 'reports.phases_id as ph_id', 'reports.id as report_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->join('payments', 'payments.project_id', '=', 'projects.id')
            ->where('payments.is_force', 1)
            ->where('projects.status', 8)
            ->get();

        $projectsGroupByProject = [];
        foreach ($projects as $project) {
            $project['created_at'] = verta($project->created_at)->format('Y/m/d');
            $project['created_at_jalali'] = explode(' ', $project['created_at'])[0];

            if ($project->p_status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];

            }

            $project['user_info_name'] = User::query()
                ->where('id', $project->user_id)
                ->first();

            if (!isset($projectsGroupByProject[$project['project_id']])) {

                $projectsGroupByProject[$project['project_id']] = [];
                $financial_res = $this->calculate_total_payed_reminding($project['project_id'], $project['contract_cost'], 1);
                $projectsGroupByProject[$project['project_id']]['reminding'] = $financial_res[2];
                $projectsGroupByProject[$project['project_id']]['total_price'] = floatval($project['contract_cost']);
                $projectsGroupByProject[$project['project_id']]['payed'] = $financial_res[0];
                $projectsGroupByProject[$project['project_id']]['user_id'] = $project['user_id'];
                $projectsGroupByProject[$project['project_id']]['s_title'] = $project['s_title'];
                $projectsGroupByProject[$project['project_id']]['p_title'] = $project['p_title'];
                $projectsGroupByProject[$project['project_id']]['status_css'] = $project['status_css'];
                $projectsGroupByProject[$project['project_id']]['project_id'] = $project['project_id'];
                $projectsGroupByProject[$project['project_id']]['subject'] = $project['subject'];
                $projectsGroupByProject[$project['project_id']]['project_unique_code'] = $project['project_unique_code'];
                $projectsGroupByProject[$project['project_id']]['supervisor'] = $project->supervisor->name . ' ' . $project->supervisor->family;
                if ($project['user_info_name']->type == 1) {
                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->co_name;
                } else {
                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->name . ' ' . $project['user_info_name']->family;
                }
            }
            // else {
            //     $projectsGroupByProject[$project['project_id']]['total_price'] += intval($project['contract_cost']);
            // }
        }

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('tarhoBarname_manager.financial.force_index', ['projectsGroupByProject' => $projectsGroupByProject, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

        public function force_search_index(Request $request)
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

        if ($request->has('supervisor') and $input['supervisor'] != '') {
            $supervisor = $input['supervisor'];
        } else {
            $supervisor = 0;
        }

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }

        if ($request->has('user_id') and $input['user_id'] != '') {
            $user_id = $input['user_id'];
        } else {
            $user_id = 0;
        }

        if ($request->has('status') and $input['status'] != '') {
            $status = intval($input['status']);
        } else {
            $status = 0;
        }

        if ($status > 100) {
            $projects = Payment::query()
                ->select('*', 'payments.id as payment_id', 'projects.title as p_title', 'phases.end_date as f_end_date', 'phases.status as f_status', 'projects.status as p_status', 'payments.status as pa_status')
                ->join('phases', 'phases.id', '=', 'payments.phase_id')
                ->join('projects', 'projects.id', '=', 'payments.project_id')
                ->join('phases_status', 'phases_status.id', '=', 'phases.status')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 8);
                    $q->where('phases.status', ($status - 100));
                })
                ->where('is_force', 1)
                ->where('payments.status', 0)
                ->get();
        } else {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
                    'phases.status as f_status', 'projects.status as p_status', 'reports.phases_id as ph_id', 'reports.id as report_id')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->join('reports', 'reports.phases_id', '=', 'phases.id')
                ->join('payments', 'payments.project_id', '=', 'projects.id')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('phases.status', ($status - 100));
                })
                ->where('projects.status', 8)
                ->where('payments.is_force', 1)
                ->where('payments.status', 0)
                ->get();
        }

        $projectsGroupByProject = [];
        foreach ($projects as $project) {
            $project['created_at'] = verta($project->created_at)->format('Y/m/d');
            $project['created_at_jalali'] = explode(' ', $project['created_at'])[0];

            if ($project->p_status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];

            }

            $project['user_info_name'] = User::query()
                ->where('id', $project->user_id)
                ->first();

            if (!isset($projectsGroupByProject[$project['project_id']])) {

                $projectsGroupByProject[$project['project_id']] = [];
                $financial_res = $this->calculate_total_payed_reminding($project['project_id'], $project['contract_cost'], 1);
                $projectsGroupByProject[$project['project_id']]['reminding'] = $financial_res[2];
                $projectsGroupByProject[$project['project_id']]['total_price'] = floatval($project['contract_cost']);
                $projectsGroupByProject[$project['project_id']]['payed'] = $financial_res[0];
                $projectsGroupByProject[$project['project_id']]['user_id'] = $project['user_id'];
                $projectsGroupByProject[$project['project_id']]['s_title'] = $project['s_title'];
                $projectsGroupByProject[$project['project_id']]['p_title'] = $project['p_title'];
                $projectsGroupByProject[$project['project_id']]['status_css'] = $project['status_css'];
                $projectsGroupByProject[$project['project_id']]['project_id'] = $project['project_id'];
                $projectsGroupByProject[$project['project_id']]['subject'] = $project['subject'];
                $projectsGroupByProject[$project['project_id']]['project_unique_code'] = $project['project_unique_code'];
                $projectsGroupByProject[$project['project_id']]['supervisor'] = $project->supervisor->name . ' ' . $project->supervisor->family;
                if ($project['user_info_name']->type == 1) {
                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->co_name;
                } else {
                    $projectsGroupByProject[$project['project_id']]['user_info_name'] = $project['user_info_name']->name . ' ' . $project['user_info_name']->family;
                }
            }
            // else {
            //     $projectsGroupByProject[$project['project_id']]['total_price'] += intval($project['contract_cost']);
            // }
        }


        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('tarhoBarname_manager.financial.force_index', ['projectsGroupByProject' => $projectsGroupByProject, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
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

    public function report_detail($project_id)
    {
        $project_info = Project::query()
            ->where('id', $project_id)
            ->firstOrFail();

        $phases = Phase::query()
            ->where('project_id', $project_id)
            ->where('status', 5)
            ->get();

        foreach ($phases as $phase) {
            $phase['end_date'] = verta($phase->end_date)->format('Y/m/d');
            $phase['jalali_end_date'] = explode(' ', $phase['end_date'])[0];
        }

        return view('tarhoBarname_manager.financial.details', compact('project_id', 'project_info', 'phases'));
    }

    public function force_detail($project)
    {
        $project_info = Project::query()
            ->where('id', $project)
            ->firstOrFail();

        $phases = Phase::query()
            ->select('*', 'payments.id as payment_id')
            ->join('payments', 'payments.phase_id', '=', 'phases.id')
            ->where('phases.project_id', $project)
            ->where('payments.status', 0)
            ->where('payments.is_force', 1)
            ->get();


        return view('tarhoBarname_manager.financial.force_detail', compact('project_info', 'phases'));
    }

    public function payment_order(Request $request)
    {
        $phase_id = $request->phase_id;

        $m_amount_payable = intval($request->m_amount_payable);

        $phase_info = Phase::query()
            ->where('id', $phase_id)
            ->firstOrFail();

        $phase_price = intval($phase_info->cost);

        if ($phase_price <= $m_amount_payable) {
            Payment::create([
                'price' => $phase_price,
                'project_id' => $phase_info->project_id,
                'phase_id' => $phase_id,
                'is_force' => 0,
                'status' => 0,
            ]);
        } else {

            $remaining = $phase_price - $m_amount_payable;

            Payment::create([
                'price' => $m_amount_payable,
                'project_id' => $phase_info->project_id,
                'phase_id' => $phase_id,
                'is_force' => 0,
                'status' => 0,
            ]);

            if ($remaining > 0) {
                Payment::create([
                    'price' => $remaining,
                    'project_id' => $phase_info->project_id,
                    'phase_id' => $phase_id,
                    'is_force' => 1,
                    'status' => 0,
                ]);
            }

        }

        $phase_info->update([
            'status' => 6
        ]);

        alert()->success('دستور واریز صادر و به مدیرمالی ارجاع داده شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('tarhoBarname_financial_index');
    }

    public function second_force_payment(Request $request)
    {
        $payment_id = $request->payment_id;
        $amount_payable = intval($this->convertDigitsToEnglish($request->m_amount_payable));

        $payment_info = Payment::query()
            ->where('id', $payment_id)
            ->first();

        $full_cost = intval($payment_info['price']);
        $remaining = $full_cost - $amount_payable;

        $project_id = $payment_info->project_id;
        $phase_id = $payment_info->phase_id;

        $payment_info->update([
            'price' => $amount_payable,
            'is_force' => 0
        ]);

        if ($remaining > 0) {
            Payment::create([
                'price' => $remaining,
                'project_id' => $project_id,
                'phase_id' => $phase_id,
                'is_force' => 1,
                'status' => 0,
            ]);
        }

        alert()->success('دستور واریز صادر و به مدیرمالی ارجاع داده شد', 'با تشکر')->autoclose(9000);
        return redirect()->route('tarhoBarname_financial_index');

    }

    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }

    public function download_file($payment)
    {

        $payment_info = Payment::query()
            ->where('id', $payment)
            ->firstOrFail();

        return response()->download($payment_info->payment_file);
    }

    public function force_payment(Request $request)
    {
        $payment_id = $request->payment_id;

        $payment_info = Payment::query()
            ->where('id', $payment_id)
            ->firstOrFail();

        $payment_info->update([
            'is_force' => 0
        ]);

        alert()->success('دستور واریز صادر و به مدیرمالی ارجاع داده شد', 'با تشکر')->autoclose(9000);

        return redirect()->route('tarhoBarname_financial_index');
    }

    public function final_payments()
    {
        $projects = Payment::query()
            ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status',
                'projects.status as p_status', 'payments.status as pa_status', 'projects.title as p_title')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.status', 1)
            ->orderByDesc('projects.created_at')
            ->paginate(10);


        foreach ($projects as $project) {
            $project['created_at'] = verta($project->created_at)->format('Y/m/d');
            $project['created_at_jalali'] = explode(' ', $project['created_at'])[0];

            if ($project->p_status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
            $employer = User::query()
                ->select('*')
                ->where('id', $project['employer_id'])
                ->first();

            $project['employer'] = $employer;

            $supervisor = User::query()
                ->select('*')
                ->where('id', $project->supervisor_id)
                ->first();

            $project['supervisor'] = $supervisor;
        }
        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;
        return view('tarhoBarname_manager.financial.final_payments', ['projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'users' => $users, 'projects_status' => $projects_status, 'searched' => $searched]);
    }

    public function search_final_payments(Request $request)
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

        if ($request->has('supervisor') and $input['supervisor'] != '') {
            $supervisor = $input['supervisor'];
        } else {
            $supervisor = 0;
        }

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }

        if ($request->has('user_id') and $input['user_id'] != '') {
            $user_id = $input['user_id'];
        } else {
            $user_id = 0;
        }

        if ($request->has('status') and $input['status'] != '') {
            $status = intval($input['status']);
        } else {
            $status = 0;
        }

        if ($status > 100) {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title',
                    'projects.id as project_id', 'phases.status as f_status', 'projects.status as p_status')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->join('payments', 'payments.project_id', '=', 'projects.id')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 8);
                    $q->where('phases.status', ($status - 100));
                })
                ->where('phases.status', 7)
                ->where('payments.status', 1)
                ->get();
        } else {
            $projects = Project::query()
                ->select('*', 'project_status.title as s_title', 'projects.title as p_title',
                    'projects.id as project_id', 'phases.status as f_status', 'projects.status as p_status')
                ->join('phases', 'phases.project_id', '=', 'projects.id')
                ->join('project_status', 'project_status.id', '=', 'projects.status')
                ->join('payments', 'payments.project_id', '=', 'projects.id')
                ->when($code != 0, function ($q) use ($code) {
                    $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
                })
                ->when($title != '', function ($q) use ($title) {
                    $q->where('projects.title', 'like', '%' . $title . '%');
                })
                ->when($supervisor != 0, function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                })
                ->when($employer != 0, function ($q) use ($employer) {
                    $q->where('employer_id', $employer);
                })
                ->when($user_id != 0, function ($q) use ($user_id) {
                    $q->where('projects.user_id', $user_id);
                })
                ->when($status != 0, function ($q) use ($status) {
                    $q->where('projects.status', 8);
                    $q->where('phases.status', ($status - 100));
                })
                ->where('phases.status', 7)
                ->where('payments.status', 1)
                ->get();
        }

        foreach ($projects as $project) {
            $project['jalali_end_date'] = $this->convertDateToJalali($project['p_end_date']);

            $employer = User::query()
                ->select('*')
                ->where('id', $project['employer_id'])
                ->first();

            $project['employer'] = $employer;

            $supervisor = User::query()
                ->select('*')
                ->where('id', $project->supervisor_id)
                ->first();

            $project['supervisor'] = $supervisor;

            $payment['id'] = $project->id;

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

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('tarhoBarname_manager.financial.final_payments', ['users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    public function check_is_force($project)
    {
        $force_info = Payment::query()
            ->where('project_id', $project)
            ->where('is_force', 1)
            ->count();

        if ($force_info > 0) {
            return true;
        } else {
            return false;
        }
    }

    function convertDigitsToEnglish($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}
