<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class dashboardController extends Controller
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
            ->count();

        $total_opened_project_sum = Project::query()
            ->where('status', '!=', 9)
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

        return view('supervisor.dashboard.index', compact("user_info", "contractors", "getDataForChart1", "getDataForChart2", "total_opened_project_count", "total_opened_project_sum", "total_closed_project_count", "total_closed_project_sum"));
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

        return view('supervisor.profile.index', compact('user_info'));
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
