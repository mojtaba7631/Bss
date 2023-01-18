<?php

namespace App\Http\Controllers\maliManager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\project_status;
use App\Models\Report;
use App\Models\SaveTime;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class financialController extends Controller
{
    function pay_detail($project)
    {
        $payments = Payment::query()
            ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status',
                'projects.status as p_status', 'payments.status as pa_status')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.is_force', 0)
            ->where('payments.status', 0)
            ->where('payments.project_id', $project)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($payments as $payment) {

            $payment['jalali_end_date'] = $this->convertDateToJalali($payment['p_end_date']);

            $employer = User::query()
                ->select('*')
                ->where('id', $payment['employer_id'])
                ->first();

            $payment['employer'] = $employer;

            $supervisor = User::query()
                ->select('*')
                ->where('id', $payment->supervisor_id)
                ->first();

            $payment['supervisor'] = $supervisor;
            $payment['id'] = $payment->payment_id;

            if ($payment->status == 8) {
                $result = $this->check_project_phase($payment->project_id);
                $payment['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 3)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', 3)
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('mali_manager.financial.pay_detail', ['payments' => $payments, 'users' => $users, 'supervisors' => $supervisors, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function index()
    {

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id',
                'phases.status as f_status', 'projects.status as p_status', 'reports.phases_id as ph_id', 'reports.id as report_id')
            ->join('phases', 'phases.project_id', '=', 'projects.id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->join('reports', 'reports.phases_id', '=', 'phases.id')
            ->join('payments', 'payments.project_id', '=', 'projects.id')
            ->join('payment_period_detail', 'payment_period_detail.phase_id', '=', 'phases.id')
            ->where('payments.is_force', 0)
            ->where('payments.status', 0)
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

        return view('mali_manager.financial.index', ['projectsGroupByProject' => $projectsGroupByProject, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function search_index_financial(Request $request)
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

        if ($request->has('user_id') and $input['user_id'] != '') {
            $user_id = $input['user_id'];
        } else {
            $user_id = 0;
        }

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }

        if ($request->has('supervisor') and $input['supervisor'] != '') {
            $supervisor = $input['supervisor'];
        } else {
            $supervisor = 0;
        }

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
            ->when($user_id != 0, function ($q) use ($user_id) {
                $q->where('projects.user_id', $user_id);
            })
            ->when($employer != 0, function ($q) use ($employer) {
                $q->where('employer_id', $employer);
            })
            ->when($supervisor != 0, function ($q) use ($supervisor) {
                $q->where('supervisor_id', $supervisor);
            })
            ->where('payments.status', 0)
            ->where('payments.is_force', 0)
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

        return view('mali_manager.financial.index', ['projectsGroupByProject' => $projectsGroupByProject, 'users' => $users, 'projects' => $projects, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function calculate_total_payed_reminding($project_id, $total, $is_force)
    {
        $payed = Payment::query()
            ->where('project_id', $project_id)
            ->where('status', 1)
            ->where('is_force', $is_force)
            ->sum('price');

        $reminding = floatval($total) - $payed;

        return [$payed, $total, $reminding];
    }

    function fullPayments()
    {
        $payments = Payment::query()
            ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status',
                'projects.status as p_status', 'payments.status as pa_status')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.status', 1)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($payments as $payment) {

            $payment['jalali_end_date'] = $this->convertDateToJalali($payment['p_end_date']);

            $employer = User::query()
                ->select('*')
                ->where('id', $payment['employer_id'])
                ->first();

            $payment['employer'] = $employer;

            $supervisor = User::query()
                ->select('*')
                ->where('id', $payment->supervisor_id)
                ->first();

            $payment['supervisor'] = $supervisor;
            $payment['id'] = $payment->id;

            if ($payment->status == 8) {
                $result = $this->check_project_phase($payment->project_id);
                $payment['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
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
            ->where('id', 3)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', 3)
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('mali_manager.financial.fullPayments', ['employers' => $employers, 'supervisors' => $supervisors, 'payments' => $payments, 'users' => $users, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function full_search_index(Request $request)
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

        if ($request->has('user_id') and $input['user_id'] != '') {
            $user_id = $input['user_id'];
        } else {
            $user_id = 0;
        }

        if ($request->has('employer') and $input['employer'] != '') {
            $employer = $input['employer'];
        } else {
            $employer = 0;
        }
        if ($request->has('supervisor') and $input['supervisor'] != '') {
            $supervisor = $input['supervisor'];
        } else {
            $supervisor = 0;
        }

        if ($request->has('status') and $input['status'] != '') {
            $status = intval($input['status']);
        } else {
            $status = 0;
        }

        $payments = Payment::query()
            ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status',
                'projects.status as p_status', 'payments.status as pa_status')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->when($code != 0, function ($q) use ($code) {
                $q->where('projects.project_unique_code_search', 'like', '%' . $code . '%');
            })
            ->when($title != '', function ($q) use ($title) {
                $q->where('projects.title', 'like', '%' . $title . '%');
            })
            ->when($user_id != 0, function ($q) use ($user_id) {
                $q->where('projects.user_id', $user_id);
            })
            ->when($employer != 0, function ($q) use ($employer) {
                $q->where('employer_id', $employer);
            })
            ->when($supervisor != 0, function ($q) use ($supervisor) {
                $q->where('supervisor_id', $supervisor);
            })
            ->when($status != 0, function ($q) use ($status) {
                $q->where('projects.status', 3);
                $q->where('phases.status', ($status - 100));
            })
            ->where('payments.status', 1)
            ->get();

        foreach ($payments as $payment) {

            $payment['jalali_end_date'] = $this->convertDateToJalali($payment['p_end_date']);

            $employer = User::query()
                ->select('*')
                ->where('id', $payment['employer_id'])
                ->first();

            $payment['employer'] = $employer;

            $supervisor = User::query()
                ->select('*')
                ->where('id', $payment->supervisor_id)
                ->first();

            $payment['supervisor'] = $supervisor;
            $payment['id'] = $payment->id;

            if ($payment->status == 8) {
                $result = $this->check_project_phase($payment->project_id);
                $payment['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 3)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', 3)
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('mali_manager.financial.fullPayments', ['users' => $users, 'payments' => $payments, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function report_detail(Project $project)
    {
        $reports = Report::query()
            ->select('*', 'phases.end_date as p_end_date')
            ->join('phases', 'phases.id', '=', 'reports.phases_id')
            ->join('projects', 'projects.id', '=', 'reports.project_id')
            ->where('reports.project_id', $project->id)
            ->get();

        foreach ($reports as $report) {
            $report['jalali_end_date'] = $this->convertDateToJalali($report['p_end_date']);
        }
        return view('mali_manager.financial.details', ['project' => $project, 'reports' => $reports]);

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

    function Payments(Request $request)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
//            'sayyad_code_val' => 'numeric|digits:16',
//            'check_code_val' => 'numeric',
        ]);

        if ($validation->fails()) {
            alert()->error($validation->errors()->first(), 'خطا')->autoclose(9000);
            return back();
        }

        $payment_id = $request->payment_id;

        $payment_info = Payment::query()
            ->where('id', $payment_id)
            ->firstOrFail();

        if ($input['start_date_inp'] == null) {
            $main_date = '2022/01/01';
        } else {
            $main_date = $input['start_date_inp'];
        }

        $date_payment = $this->convertDateToGregorian($main_date);

        $payment_info->update([
            'status' => 1,
            'type' => $request->type,
            'following_code' => $request->following_code_val,
            'sayyad_code' => $request->sayyad_code_val,
            'check_code' => $request->check_code_val,
            'check_date' => $date_payment,
        ]);

        $phase_not_paid = Payment::query()
            ->where('phase_id', $payment_info['phase_id'])
            ->where('status', 0)
            ->count();

        if ($phase_not_paid == 0) {
            $phase_info = Phase::query()
                ->where('id', $payment_info['phase_id'])
                ->firstOrFail();

            $phase_info->update([
                'status' => 7
            ]);

            SaveTime::query()->create([
                'project_id' => $payment_info['project_id'],
                'phase_id' => $payment_info['phase_id'],
                'user_id' => auth()->id(),
                'role_id' => 6,
                'level' => 8,
                'comments' => 'مدیرمالی (' . auth()->user()->name . ' ' . auth()->user()->family . ') مبلغ فاز را واریز کرد',
            ]);

            $found_phase_not_paid_in_project = Phase::query()
                ->where('project_id', $phase_info['project_id'])
                ->where('status', '<', 7)
                ->count();

            if ($found_phase_not_paid_in_project == 0) {
                $project_info = Project::query()
                    ->where('id', $phase_info['project_id'])
                    ->firstOrFail();

                $project_info->update([
                    'status' => 9
                ]);
            }
        }

        $project_info = Project::query()
            ->where('id', $payment_info['project_id'])
            ->first();

        $user_info = User::query()
            ->where('id', $project_info['user_id'])
            ->first();

        if ($user_info['type'] == 1) {
            $user_name = $user_info['co_name'];
        } else {
            $user_name = $user_info['name'] . ' ' . $user_info['family'];
        }

        if ($request->type > 0) {
            sms_otp($user_info['mobile'], 'payedOne', ['param1' => $user_name, 'param2' => $project_info['project_unique_code']]);
        } else {
            sms_otp($user_info['mobile'], 'payedTwo', ['param1' => $user_name, 'param2' => @number_format($payment_info['price']), 'param3' => $project_info['project_unique_code']]);
        }

        alert()->success('پرداخت انجام شد سند مالی ثبت شد', 'با تشکر')->autoclose(9000);
        return redirect()->route('maliManager_financial_index');
    }

    function add(Report $report)
    {

        $report_id = $report->id;

        return view('mali_manager.financial.add', ['report_id' => $report_id]);
    }

    function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
    }

    function financial_doc(Request $request)
    {
        $input = $request->all();

        $report_id = $request->report_id;

        $project_ids = Report::query()
            ->select('project_id')
            ->where('id', $report_id)
            ->first();

        $project_id = $project_ids->project_id;


        $project = Project::query()
            ->where('id', $project_id)
            ->firstOrFail();

        $project->update([
            'status' => 15
        ]);

        if ($request->has('payment_file')) {
            $validation = Validator::make($input, [
                'payment_file' => 'required|mimes:jpg,jpeg,png,pdf',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'error' => true,
                    'errorMsg' => $validation->errors(),
                ]);
            }

            $report = Report::query()
                ->where('id', $report_id)
                ->firstOrFail();

            $file_name = time() . $request->file('payment_file')->getClientOriginalName();
            $report->update([
                'payment_file' => $request->file('payment_file')->move('files/financial_doc', $file_name),
            ]);


            alert()->success('ثبت سند مالی این پرداخت انجام شد', 'با تشکر')->autoclose(9000);
            return redirect()->route('maliManager_financial_index');
        }

    }

    function get_data(Request $request)
    {
        $payment_id = $request->payment_id;

        $payment = Payment::query()
            ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status', 'projects.status as p_status', 'payments.status as pa_status')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.status', 0)
            ->where('payments.id', $payment_id)
            ->first();

        if (!$payment) {
            return response()->json([
                'error' => true
            ]);
        }

        if ($payment['phase_number'] == 0) {
            $payment['phase_number'] = 'پیش پرداخت';
        }

        $payment['price'] = @number_format($payment['price']);

        return response()->json([
            'error' => false,
            'payment' => $payment
        ]);
    }

    function download_file($peyment)
    {

        $payment_info = Payment::query()
            ->where('id', $peyment)
            ->firstOrFail();

        return response()->download($payment_info->payment_file);
    }

    function check_list()
    {
        $payments = Payment::query()
            ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status',
                'projects.status as p_status', 'payments.status as pa_status')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.is_force', 0)
            ->where('payments.status', 1)
            ->where('payments.took_receipt', 0)
            ->where('payments.type', 1)
            ->orderByDesc('projects.created_at')
            ->paginate(10);

        foreach ($payments as $payment) {

            $payment['jalali_end_date'] = $this->convertDateToJalali($payment['p_end_date']);

            $employer = User::query()
                ->select('*')
                ->where('id', $payment['employer_id'])
                ->first();

            $payment['employer'] = $employer;

            $supervisor = User::query()
                ->select('*')
                ->where('id', $payment->supervisor_id)
                ->first();

            $payment['supervisor'] = $supervisor;
            $payment['id'] = $payment->id;

            if ($payment->status == 8) {
                $result = $this->check_project_phase($payment->project_id);
                $payment['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
            }
        }

        $users = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $supervisors = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 4)
            ->get();

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $projects_status = project_status::query()
            ->where('id', '!=', 8)
            ->where('id', '!=', 11)
            ->where('id', '!=', 12)
            ->where('id', 3)
            ->get();

        $phases_status = phases_status::query()
            ->where('id', 3)
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;

        return view('mali_manager.financial.check_list', ['payments' => $payments, 'users' => $users, 'supervisors' => $supervisors, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }

    function took_reciept(Request $request)
    {
        $payment_id = $request->payment;

        $payment_info = Payment::query()
            ->where('id', $payment_id)
            ->firstOrFail();

        $payment_info->update([
            'took_receipt' => 1,
            'date_receipt' => $this->convertDateToGregorian($request->date_receipt)
        ]);

        alert()->success('سند مالی تحویل داده شد', 'باتشکر')->autoclose(9000);

        return back();
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

    function convertDateToGregorian($date)
    {
        $date = explode('/', $date);
        $date = Verta::getGregorian($this->convertDigitsToEnglish($date[0]), $this->convertDigitsToEnglish($date[1]), $this->convertDigitsToEnglish($date[2]));
        return join('-', $date);
    }
}
