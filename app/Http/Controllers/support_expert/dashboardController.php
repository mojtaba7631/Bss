<?php

namespace App\Http\Controllers\support_expert;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $user_img = $user_info->image;

        return view('support_expert.dashboard.index', compact('user_img'));
    }
}
