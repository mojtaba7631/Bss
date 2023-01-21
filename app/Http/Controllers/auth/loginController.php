<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class loginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'error' => true,
            ]);
        }

        $user_info = User::query()
            ->select('id')
            ->where('username', $request->username)
            ->first();


        if (!$user_info) {

            return response()->json([
                'status' => false,
                'message' => 'نام کاربری یا رمز عبور اشتباه است',
                'error' => true,
            ]);
        }
        $user_role = Role::query()
            ->where('user_id', $user_info->id)
            ->get();
        $roles = [];

        foreach ($user_role as $role) {
            array_push($roles, $role->roles);
        }

        $user_selected_role = 0;
        if (count($roles) > 1) {

            foreach ($user_role as $role) {
                if ($role['is_default'] == 1) {
                    $user_selected_role = $role->roles;
                    break;
                }
            }
        } else {

            $user_selected_role = $roles[0];
        }

        if ($user_selected_role == 0) {
            return response()->json([
                'status' => true,
                'message' => 'شما نقش پیشفرضی ندارید.',
                'error' => true,
            ]);
        }

        if ($user_selected_role == 8) {
            $redirect = route('realUser_index');
        } elseif ($user_selected_role == 1) {
            $redirect = route('admin_index');
        } elseif ($user_selected_role == 2) {
            $redirect = route('tarho_Barname_manager_index');
        } elseif ($user_selected_role == 3) {
            $redirect = route('employer_index');
        } elseif ($user_selected_role == 4) {
            $redirect = route('Supervisor_index');
        } elseif ($user_selected_role == 5) {
            $redirect = route('main_manager_index');
        } elseif ($user_selected_role == 6) {
            $redirect = route('maliManager_index');
        } elseif ($user_selected_role == 7) {
            $redirect = route('legalUser_index');
        } elseif ($user_selected_role == 9) {
            $redirect = route('expert_index');
        } elseif ($user_selected_role == 10) {
            $redirect = route('personnel_index');
        } elseif ($user_selected_role == 23) {
            $redirect = route('deputy_plan_program_index');
        } elseif ($user_selected_role == 16) {
            $redirect = route('support_manager_index');
        } elseif ($user_selected_role == 18) {
            $redirect = route('relations_manager_index');
        } elseif ($user_selected_role == 17) {
            $redirect = route('support_expert_index');
        } elseif ($user_selected_role == 19) {
            $redirect = route('special_expert_index');
        } elseif ($user_selected_role == 20) {
            $redirect = route('discourse_expert_index');
        } elseif ($user_selected_role == 21) {
            $redirect = route('innovation_expert_index');
        } elseif ($user_selected_role == 24) {
            $redirect = route('adjustment_expert_index');
        } else {
            return response()->json([
                'status' => true,
                'message' => 'شما سطح دسترسی لازم جهت ورود را ندارید.',
                'error' => true,
            ]);
        }

        $expired_date = time() + 60 * 60 * 5;
        setcookie('user_route', $redirect, $expired_date, '/');

        if (auth()->attempt([
            'username' => $request->username,
            'password' => $request->password
        ],
            $request->remember)) {
            return response()->json([
                'status' => true,
                'message' => 'وارد شد',
                'redirect' => $redirect,
                'error' => false,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => true,
                'message' => 'نام کاربری یا رمز عبور اشتباه است',
            ]);
        }
    }
}
