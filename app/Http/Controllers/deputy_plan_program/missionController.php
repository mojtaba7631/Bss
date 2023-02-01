<?php

namespace App\Http\Controllers\deputy_plan_program;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\MissionStatus;
use App\Models\User;
use Illuminate\Http\Request;

class missionController extends Controller
{
    public function index(){
        $user_id = auth()->user()->id;

        $user_info = User::query()
            ->where('id', $user_id)
            ->first();

        $missions = Mission::query()
            ->where('user_id', $user_id)
            ->paginate(10);

        foreach ($missions as $mission) {
            $mission['start_day'] = verta($mission->start_day)->format('d/%B/Y');
            $mission['end_day'] = verta($mission->end_day)->format('d/%B/Y');
            $mission['status'] = MissionStatus::query()
                ->where('id',$mission->status)
                ->first();
        }

        $user_img = $user_info->image;
        $searched = false;
        return view('deputy_plan_program.mission.index', compact('user_img', 'user_info', 'missions', 'searched'));

    }
}
