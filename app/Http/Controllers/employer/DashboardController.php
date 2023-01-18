<?php

namespace App\Http\Controllers\employer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();

        $user_info = User::query()
            ->select("*", "role.title as role_title")
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('users.id', $user_id)
            ->firstOrFail();

        $contractors = User::query()
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->where('user_role.roles', 7)
            ->orWhere('user_role.roles', 8)
            ->get();

        $total_opened_project_count = Project::query()
            ->where('status', '!=', 9)
            ->where('status', '!=', 12)
            ->where('status', '>', 6)
            ->count();

        $total_opened_project_sum = Project::query()
            ->where('status', '!=', 9)
            ->where('status', '!=', 12)
            ->where('status', '>', 6)
            ->sum('contract_cost');

        $total_closed_project_count = Project::query()
            ->where('status', '==', 9)
            ->count();

        $total_closed_project_sum = Project::query()
            ->where('status', '==', 9)
            ->sum('contract_cost');

        $getDataForChart1 = [
            [], [], []
        ];

        //donut
        $getDataForChart2 = [
            'labels' => [],
            'data' => [],
        ];

        foreach ($contractors as $contractor) {
            if ($contractor['type'] == 1) {
                $contractor['project_count'] = $this->getProjectCount($contractor['user_id']);

                $res = $this->getDataForChart1($contractor['user_id']);

                $contractor['project_total_sum'] = $res[0] * 1000000;

                array_push($getDataForChart1[0], $res[0]);
                array_push($getDataForChart1[1], $res[1]);
                array_push($getDataForChart1[2], $res[2]);

                //donut
                array_push($getDataForChart2['labels'], $contractor['co_name']);
                array_push($getDataForChart2['data'], $res[0]);
            }
        }

        return view('employer.dashboard.index', compact("user_info", "contractors", "getDataForChart1", "getDataForChart2", "total_opened_project_count", "total_opened_project_sum", "total_closed_project_count", "total_closed_project_sum"));
    }

    function getDataForChart1($user_id)
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

        return [$project_total_sum / 1000000, $project_paid_sum / 1000000, $remaining / 1000000];
    }

    function getProjectCount($user_id)
    {
        $project_total_count = Project::query()
            ->where('user_id', $user_id)
            ->count("contract_cost");

        return $project_total_count;
    }

    function profile()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->firstOrFail();

        $user_info['start_date'] = verta($user_info->co_reg_date)->format('d/%B/Y');

        return view('employer.profile.index', compact('user_info'));
    }

    public function update_profile(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'email',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            alert()->error($validator->errors()->first());
            return back()->withInput()->withErrors($validator->errors());
        }

        $user_info = User::query()
            ->where('id',auth()->user()->id)
            ->firstOrFail();

        try {
            $user_info->update([
                'mobile' => $request->mobile,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $input['address'],
            ]);
        } catch (\Exception $e) {
            die($e);
        }


        alert()->success('پروفایل شما ویرایش گردید', 'با تشکر')->autoclose(9000);

        return redirect()->route('employer_profile');
    }
}
