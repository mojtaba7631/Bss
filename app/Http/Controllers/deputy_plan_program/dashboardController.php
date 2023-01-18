<?php

namespace App\Http\Controllers\deputy_plan_program;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index(){
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $user_img = $user_info->image;

        return view('deputy_plan_program.dashboard.index',compact('user_img'));
    }
}
