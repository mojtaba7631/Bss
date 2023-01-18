<?php


function has_roles($user, $roles) {
    return \App\Models\Role::where('user_id', $user->id)->whereIn('roles', $roles)->count() > 0;
}
