<?php

namespace App\Http\Controllers\relations_manager;

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

        return view('relations_manager.dashboard.index',compact('user_img'));
    }
}
