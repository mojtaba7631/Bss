<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\project_status;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class financialController extends Controller
{
    public function index()
    {
        $payments = Payment::query()
            ->select('*', 'payments.id as payment_id', 'phases.end_date as f_end_date', 'phases.status as f_status',
                'projects.status as p_status', 'payments.status as pa_status','projects.title as p_title')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.is_force', 0)
            ->where('payments.status', 0)
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

        return view('admin.financial.index', ['payments' => $payments, 'users' => $users, 'supervisors' => $supervisors, 'employers' => $employers, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }
    public function search_index_financial(Request $request)
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
                'projects.status as p_status', 'payments.status as pa_status','projects.title as p_title')
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
            ->where('payments.status', 0)
            ->where('payments.is_force', 0)

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

        return view('admin.financial.index', ['users' => $users, 'payments' => $payments, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }
    public function convertDateToJalali($date)
    {
        $jalali_date = verta($date)->format('j/%B/Y');
        return $jalali_date;
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
                $phase_number = '?????? ????????????';
            } else {
                $phase_number = '?????? ' . $phase_number;
            }
        } else {
            $status_info = phases_status::query()
                ->where('id', 7)
                ->first();

            $phase_number = '?????? ??????';
        }

        $s_title = $status_info->title;
        return [$s_title, $phase_number];
    }
    public function force_index()
    {
        $payments = Payment::query()
            ->select('*', 'payments.id as payment_id', 'projects.title as p_title', 'phases.end_date as f_end_date', 'phases.status as f_status', 'projects.status as p_status', 'payments.status as pa_status')
            ->join('phases', 'phases.id', '=', 'payments.phase_id')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->where('is_force', 1)
            ->where('payments.status', 0)
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
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = false;


        return view('admin.financial.force_index', ['payments' => $payments,'employers' => $employers,'supervisors' => $supervisors,'users' => $users,'projects_status' => $projects_status,'searched' => $searched]);
    }
    public  function force_search_index(Request $request){
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
            $payments = Payment::query()
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
            $payments = Payment::query()
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
        }

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
            ->get();

        $phases_status = phases_status::query()
            ->get();

        foreach ($phases_status as $phase_status) {
            $phase_status['id'] += 100;
        }

        $searched = true;

        return view('admin.financial.force_index', ['users'=>$users,'payments' => $payments, 'employers' => $employers, 'supervisors' => $supervisors, 'projects_status' => $projects_status, 'phases_status' => $phases_status, 'searched' => $searched]);
    }


}
