<?php

namespace App\Helpers;

use App\Models\Leave;

class GetCountLeave
{
    static function getCountLeave($user_id){
        $leave_count = Leave::query()
            ->where('parent',$user_id)
            ->where('main_manager_approval',0)
            ->where('confirmation',0)
            ->where('finance_manager_approval',0)
            ->count();

        return $leave_count;
    }

    static function getCountEdariLeave(){
        $leave_count = Leave::query()
            ->where('main_manager_approval',1)
            ->where('confirmation',0)
            ->where('finance_manager_approval',0)
            ->count();

        return $leave_count;
    }
}
