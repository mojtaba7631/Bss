<?php

namespace App\Http\Controllers\legalUser;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Phase;
use App\Models\phases_status;
use App\Models\Project;
use App\Models\SystemAlert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

;

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
            ->where('status', '>', 6)
            ->where('user_id', auth()->id())
            ->count();

        $total_opened_project_sum = Project::query()
            ->where('status', '!=', 9)
            ->where('status', '!=', 12)
            ->where('status', '>', 6)
            ->where('user_id', auth()->id())
            ->sum('contract_cost');

        $total_closed_project_count = Project::query()
            ->where('status', '==', 9)
            ->where('user_id', auth()->id())
            ->count();

        $total_closed_project_sum = Project::query()
            ->where('status', '==', 9)
            ->where('user_id', auth()->id())
            ->sum('contract_cost');

        //donut
        $getDataForChart = [
            'labels' => [],
            'data' => [],
        ];

        $circleChart = [];

        $projects = Project::query()
            ->select('*', 'project_status.title as s_title', 'projects.title as p_title', 'projects.id as project_id', 'projects.created_at as p_created_at', 'projects.status as p_status')
            ->join('project_status', 'project_status.id', '=', 'projects.status')
            ->where('user_id', auth()->id())
            ->get();

        foreach ($projects as $project) {
            $project['end_date_jalali'] = verta($project->start_date)->format('d/%B/Y');
            $result = $this->check_project_phase($project->project_id);
            if ($project->status == 8) {

                $project['s_title'] = '<b class="mr-3px">' . $result[1] . ': </b>' . $result[0];

                if ($result[2] == 1) {
                    if (isset($circleChart[$result[0]])) {
                        $circleChart[$result[0]]++;
                    } else {
                        $circleChart[$result[0]] = 1;
                    }
                }
            } else {
                if ($project->status == 8 || $project->status == 9 || $project->status == 10 || $project->status == 2) {
                    if (isset($circleChart[$result[0]])) {
                        $circleChart[$project['s_title']]++;
                    } else {
                        $circleChart[$project['s_title']] = 1;
                    }
                }
            }
        }

        foreach ($circleChart as $key => $item) {
            array_push($getDataForChart['labels'], $key);
            array_push($getDataForChart['data'], $item);
        }

        $monthly_price_chart = $this->get_monthly_price_chart();

        return view('legalUser.dashboard.index', compact("monthly_price_chart", "user_info", "projects", "getDataForChart", "total_opened_project_count", "total_opened_project_sum", "total_closed_project_count", "total_closed_project_sum"));
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
        $s_id = $status_info->id;
        return [$s_title, $phase_number, $s_id];
    }

    function get_monthly_price_chart()
    {
        $year = verta()->format('Y');

        $date = [];
        for ($i = 1; $i < 13; $i++) {
            if ($i < 10) {
                $m = '0' . $i;
            } else {
                $m = $i;
            }

            if ($m < 7) {
                $last_day = '31';
            } else {
                $last_day = '30';
            }

            if ($m == 1) {
                $monthName = 'فروردین';
            } elseif ($m == 2) {
                $monthName = 'اردیبهشت';
            } elseif ($m == 3) {
                $monthName = 'خرداد';
            } elseif ($m == 4) {
                $monthName = 'تیر';
            } elseif ($m == 5) {
                $monthName = 'مرداد';
            } elseif ($m == 6) {
                $monthName = 'شهریور';
            } elseif ($m == 7) {
                $monthName = 'مهر';
            } elseif ($m == 8) {
                $monthName = 'آبان';
            } elseif ($m == 9) {
                $monthName = 'آذر';
            } elseif ($m == 10) {
                $monthName = 'دی';
            } elseif ($m == 11) {
                $monthName = 'بهمن';
            } elseif ($m == 12) {
                $monthName = 'اسفند';
            } else {
                $monthName = 'ناشناس';
            }

            $startDate = $year . "/" . $m . '/' . '01';
            $endDate = $year . "/" . $m . '/' . $last_day;

            $date[intval($m)] = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'startDateEng' => $this->convertDateToGregorian($startDate),
                'endDateEng' => $this->convertDateToGregorian($endDate),
                'monthName' => $monthName,
                'payed' => 0,
                'waiting_for_pay' => 0,
            ];
        }

        for ($j = 1; $j <= count($date); $j++) {
            $date[$j]['payed'] = intval(Payment::query()
                ->join('projects', 'payments.project_id', '=', 'projects.id')
                ->where('projects.user_id', auth()->id())
                ->where('payments.status', 1)
                ->whereBetween('payments.created_at', [$date[$j]['startDateEng'], $date[$j]['endDateEng']])
                ->sum('price'));

            $date[$j]['waiting_for_pay'] = intval(Payment::query()
                ->join('projects', 'payments.project_id', '=', 'projects.id')
                ->where('projects.user_id', auth()->id())
                ->where('payments.status', 0)
                ->whereBetween('payments.created_at', [$date[$j]['startDateEng'], $date[$j]['endDateEng']])
                ->sum('price'));
        }

        //        $labels = [
        //            'فروردین',
        //            'اردیبهشت',
        //            'خرداد',
        //            'تیر',
        //            'مرداد',
        //            'شهریور',
        //            'مهر',
        //            'آبان',
        //            'آذر',
        //            'دی',
        //            'بهمن',
        //            'اسفند',
        //        ];

        return $date;
    }

    function convertDateToGregorian($date)
    {
        $date = explode('/', $date);
        $gregorian_array = verta()->getGregorian($date[0], $date[1], $date[2]);
        return join('-', $gregorian_array);
    }

    function profile()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->firstOrFail();

        $user_info['start_date'] = verta($user_info->co_reg_date)->format('d/%B/Y');

        return view('legalUser.profile.index', compact('user_info'));
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

        if ($request->has('co_statute_image')) {
            $old = $user_info['co_statute_image'];
            if (file_exists($old) and !is_dir($old)) {
                unlink($old);
            }

            $file = $request->file('co_statute_image');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = 'img_' . time() . '.' . $file_ext;
            $co_statute_image = $this->repair_file_src($file->move('co_statute_image', $file_name));

            $user_info->update([
                'co_statute_image' => $co_statute_image,
            ]);
        }

        if ($request->has('Signature_img')) {
            $old = $user_info['Signature_img'];
            if (file_exists($old) and !is_dir($old)) {
                unlink($old);
            }

            $file = $request->file('Signature_img');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = 'img_' . time() . '.' . $file_ext;
            $co_statute_image = $this->repair_file_src($file->move('images/digital_sign', $file_name));

            $user_info->update([
                'Signature_img' => $co_statute_image,
            ]);
        }

        if ($request->has('stamp_img')) {
            $old = $user_info['stamp_img'];
            if (file_exists($old) and !is_dir($old)) {
                unlink($old);
            }

            $file = $request->file('stamp_img');
            $file_ext = $file->getClientOriginalExtension();
            $file_name = 'img_' . time() . '.' . $file_ext;
            $co_statute_image = $this->repair_file_src($file->move('images/stamp', $file_name));

            $user_info->update([
                'stamp_img' => $co_statute_image,
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
