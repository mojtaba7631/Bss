<?php

namespace App\Http\Controllers\maliManager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\Project_error;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class dashboardController extends Controller
{
    function index()
    {
        $user_id = auth()->id();

        $user_info = User::query()
            ->select("*", "role.title as role_title")
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('users.id', $user_id)
            ->firstOrFail();

        $total_opened_project_count = Project::query()
            ->where('status', '!=', 9)
            ->where('status', '!=', 12)
            ->where('status', '>', 7)
            ->count();

        //کل قراردادهای جاری
        $total_opened_project_sum = Project::query()
            ->where('status', '!=', 9)
            ->where('status', '!=', 12)
            ->where('status', '>', 7)
            ->sum('contract_cost');

        //تعهدات
        $total_promise_sum = Phase::query()
            ->where('status', 6)
            ->sum('cost');

        //بدهی ها
        $total_have_to_pay_sum = Payment::query()
            ->where('status', 0)
            ->sum('price');

        //تسویه شده ها
        $total_all_payed_sum = Payment::query()
            ->where('status', 1)
            ->sum('price');

        $employers = User::query()
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->where('user_role.roles', 3)
            ->get();

        $employer_projects = Project::query()
            ->select('*', 'projects.id as project_id', 'projects.status as p_status')
            ->join('users', 'users.id', '=', 'projects.employer_id')
            ->where('projects.status', '>', 6)
            ->where('projects.status', '!=', 9)
            ->where('projects.status', '!=', 12)
            ->latest('projects.employer_id')
            ->paginate(10);

        foreach ($employer_projects as $p) {
            $res = $this->calculateProjectPrices($p['project_id'], intval($p['contract_cost']));
            $p['payed'] = $res[0];
            $p['reminding'] = $res[1];
        }

        //color chart
        $color_chart_data = $this->getColorChartData();

        //weekly table
        $now = Carbon::now();
        $sat = $now->startOfWeek()->subDays(2)->format('Y-m-d');
        $sun = $now->startOfWeek()->subDays(1)->format('Y-m-d');
        $mon = $now->startOfWeek()->format('Y-m-d');
        $tue = $now->startOfWeek()->addDays(1)->format('Y-m-d');
        $wed = $now->startOfWeek()->addDays(2)->format('Y-m-d');
        $thu = $now->startOfWeek()->addDays(3)->format('Y-m-d');
        $fri = $now->endOfWeek()->addDays(5)->format('Y-m-d');

        $sat_projects = Phase::query()
            ->select('*', 'phases.status as f_status', 'phases_status.status_css as status_css')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->whereDate('end_date', $sat)
            ->get();

        foreach ($sat_projects as $project) {
            $result = $this->check_project_phase($project->project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        }

        $sun_projects = Phase::query()
            ->select('*', 'phases.status as f_status', 'phases_status.status_css as status_css')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->whereDate('end_date', $sun)
            ->get();

        foreach ($sun_projects as $project) {
            $result = $this->check_project_phase($project->project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        }

        $mon_projects = Phase::query()
            ->select('*', 'phases.status as f_status', 'phases_status.status_css as status_css')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->whereDate('end_date', $mon)
            ->get();

        foreach ($mon_projects as $project) {
            $result = $this->check_project_phase($project->project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        }

        $tue_projects = Phase::query()
            ->select('*', 'phases.status as f_status', 'phases_status.status_css as status_css')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->whereDate('end_date', $tue)
            ->get();

        foreach ($tue_projects as $project) {
            $result = $this->check_project_phase($project->project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        }

        $wed_projects = Phase::query()
            ->select('*', 'phases.status as f_status', 'phases_status.status_css as status_css')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->whereDate('end_date', $wed)
            ->get();

        foreach ($wed_projects as $project) {
            $result = $this->check_project_phase($project->project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        }

        $thu_projects = Phase::query()
            ->select('*', 'phases.status as f_status', 'phases_status.status_css as status_css')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->whereDate('end_date', $thu)
            ->get();

        foreach ($thu_projects as $project) {
            $result = $this->check_project_phase($project->project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        }

        $fri_projects = Phase::query()
            ->select('*', 'phases.status as f_status', 'phases_status.status_css as status_css')
            ->join('phases_status', 'phases_status.id', '=', 'phases.status')
            ->whereDate('end_date', $fri)
            ->get();

        foreach ($fri_projects as $project) {
            $result = $this->check_project_phase($project->project_id);
            $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
        }

        $weekly = [
            $sat_projects,
            $sun_projects,
            $mon_projects,
            $tue_projects,
            $wed_projects,
            $thu_projects,
            $fri_projects,
        ];

        $chart_employers = User::query()
            ->select('*', 'users.id as employer_id')
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->join('projects', 'projects.user_id', '=', 'users.id')
            ->where('user_role.roles', 7)
            ->where('projects.status', '!=', 9)
            ->where('projects.project_unique_code', '!=', null)
            ->groupBy('projects.user_id')
            ->orderBy('users.co_name')
            ->get();

        return view('mali_manager.dashboard.index', compact("chart_employers", "weekly", "color_chart_data", "user_info", "employer_projects", "employers", "total_opened_project_count", "total_opened_project_sum", "total_promise_sum", "total_have_to_pay_sum", "total_all_payed_sum", "color_chart_data"));
    }

    function getBarChart2Data(Request $request)
    {
        $employer_id = $request->employer_id;

        $projects = Project::query()
            ->select('*', 'projects.id as project_id')
            ->where('user_id', $employer_id)
            ->where('projects.project_unique_code', '!=', null)
            ->where('projects.status', '!=', 9)
            ->get();

        $final = [
            [],
            [],
            [],
        ];
        $labels = [];

        $total_value = 0;
        $total_count = 0;
        $total_payed = 0;
        $total_remind = 0;

        foreach ($projects as $project) {
            array_push($labels, $project['project_unique_code']);
            $res = $this->calculateProjectPrices($project['project_id'], $project['contract_cost']);

            array_push($final[0], ['value' => $project['contract_cost'] / 1000000, 'meta' => 'تعهد']);
            array_push($final[1], ['value' => intval($res[0] / 1000000), 'meta' => 'پرداخت شده']);
            array_push($final[2], ['value' => intval($res[1] / 1000000), 'meta' => 'بدهی']);

            $total_value += intval($project['contract_cost']);
            $total_count++;
            $total_payed += intval($res[0]);
            $total_remind += intval($res[1]);
        }

        $employer_info = User::query()->where('id', $employer_id)->first();
        $employer_info['img'] = asset($employer_info['image']);
        $employer_info['total_value'] = $total_value;
        $employer_info['total_count'] = $total_count;
        $employer_info['total_payed'] = $total_payed;
        $employer_info['total_remind'] = $total_remind;

        return response()->json([
            'error' => false,
            'errorMsg' => '',
            'labels' => $labels,
            'values' => $final,
            'employer_info' => $employer_info,
        ]);
    }

    function getColorChartData()
    {
        $color_chart_projects = Project::query()
            ->where('status', '=', 8)
            ->get();

        $red = 0;
        $yellow = 0;
        $green = 0;

        foreach ($color_chart_projects as $project) {
            $color = $this->checkProjectColor($project->id);

            if ($color == 'red') {
                $red++;
            } elseif ($color == 'yellow') {
                $yellow++;
            } else {
                $green++;
            }
        }

        return [
            'labels' => [
                'بدون دیرکرد',
                'کمتر از 2 هفته',
                'بیش از 2 هفته',
            ],
            'data' => [
                $green,
                $yellow,
                $red,
            ],
            'class' => [
                'stroke-green',
                'stroke-yellow',
                'stroke-red',
            ],
        ];
    }

    function checkProjectColor($project_id)
    {
        $phases = Phase::query()
            ->where('project_id', $project_id)
            ->whereIn('status', [1, 2])
            ->get();

        if (!$phases) {
            return 'green';
        }

        foreach ($phases as $phase) {
            if ($phase['status'] == 1) {
                $datetime1 = new DateTime(date('Y-m-d', strtotime($phase['end_date'])));
                $datetime2 = new DateTime(date('Y-m-d', strtotime(Carbon::today())));
                $difference = $datetime1->diff($datetime2)->days;
                if ($difference > 14) {
                    return 'red';
                } elseif ($difference > 0) {
                    return 'yellow';
                } else {
                    return 'green';
                }
            }

            if ($phase['status'] == 2) {
                $report = Report::query()
                    ->where('phases_id', $phase->id)
                    ->first();

//                if (!$report) {
//                    return 'red';
//                }

                $datetime1 = new DateTime(date('Y-m-d', strtotime($phase['end_date'])));
                $datetime2 = new DateTime(date('Y-m-d', strtotime($report['created_at'])));
                $difference = $datetime1->diff($datetime2)->days;
                if ($difference > 14) {
                    return 'red';
                } elseif ($difference > 0) {
                    return 'yellow';
                }
            }

        }

        return 'green';
    }

    function getProjectCount($user_id)
    {
        $project_total_count = Project::query()
            ->where('user_id', $user_id)
            ->count("contract_cost");

        return $project_total_count;
    }

    function calculateProjectPrices($project_id, $contract_cost)
    {
        $project_payed = Payment::query()
            ->where('project_id', $project_id)
            ->where('status', 1)
            ->sum('price');

        $project_reminding = $contract_cost - $project_payed;

        return [$project_payed, $project_reminding];
    }

    function colorDonutChartDetail($color_param = '')
    {
        if ($color_param != 'red' and $color_param != 'yellow' and $color_param != 'green') {
            abort(404);
        }

        $color_chart_projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('projects.status', 8)
            ->get();

        $projects = [];
        foreach ($color_chart_projects as $project) {
            $color = $this->checkProjectColor($project->project_id);

            if ($color == $color_param) {
                array_push($projects, $project);
            }
        }

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->end_date)->format('d/%B/Y');
            $project['project_error'] = Project_error::query()
                ->where('project_id', $project->project_id)
                ->get();
            if ($project->status == 8) {
                $result = $this->check_project_phase($project->project_id);
                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];
                $project['reportable'] = $this->get_phase_reportable($project['project_id']);
            } else {
                $project['reportable'] = false;
            }
        }

        return view('tarhoBarname_manager.dashboard.color_chart', compact('projects', 'color_param'));
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

    function get_phase_reportable($project_id)
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

    function profile()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->firstOrFail();

        $user_info['start_date'] = verta($user_info->co_reg_date)->format('d/%B/Y');

        return view('mali_manager.profile.index', compact('user_info'));
    }

    function change_pass(Request $request) {
        $input = $request->all();

        $validation = Validator::make($input, [
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
            'new_password_confirmation' => 'required|string|min:8',
        ]);

        if ($validation->fails()) {
            alert()->error($validation->errors()->first(), 'خطا !');
            return back()->withErrors($validation->errors());
        }

        $user_pass = auth()->user()->getAuthPassword();

        $old = $input['old_password'];
        $new = $input['new_password'];
        $confirm = $input['new_password_confirmation'];

        if (!password_verify($old, $user_pass)) {
            alert()->error('رمز عبور فعلی صحیح نیست!', 'خطا !');
            return back();
        }

        if ($new != $confirm) {
            alert()->error('کلمه عبور و تکرار آن تطابق ندارند!', 'خطا !');
            return back();
        }

        $user = auth()->user();

        $user->update([
            'password' => password_hash($new, PASSWORD_DEFAULT),
            'p_without_hash' => $new,
        ]);

        alert()->success('کلمه عبور با موفقیت تغییر کرد.', 'با تشکر !');
        return back();
    }

    function update_profile(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'nullable|email|string|max:255',
            'address' => 'nullable|string|max:255',
            'mobile' => 'nullable|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'phone' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            alert()->error($validator->errors()->first());
            return back()->withInput()->withErrors($validator->errors());
        }

        $user_info = User::query()
            ->where('id', auth()->user()->id)
            ->firstOrFail();

        $user_info->update([
            'mobile' => $request->mobile,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        if ($request->has('image')) {
            $old = $user_info['image'];
            if (file_exists($old) and !is_dir($old)) {
                unlink($old);
            }

            $file = $request->file('image');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = 'img_' . time() . '.' . $file_ext;
            $image = $this->repair_file_src($file->move('images', $file_name));

            $user_info->update([
                'image' => $image,
            ]);
        }

        alert()->success('پروفایل شما ویرایش گردید', 'با تشکر')->autoclose(9000);

        return back();
    }

    function repair_file_src($src)
    {
        return str_replace('\\', '/', $src);
    }
}
