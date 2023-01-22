@php
    use App\Models\User;
    use App\Models\Project;
    use App\Models\Report;
    use App\Models\Role;
    use App\Models\Ticket;

    $user_id = auth()->user()->id;

    $users = User::query()
        ->where('id',$user_id)
        ->firstOrFail();

//    $project_count = Project::query()
//            ->where('status',1)
//            ->count();
//    $contract_count = Project::query()
//            ->where('status',3)
//            ->count();
//    $report_count = Report::query()
//            ->join('projects','projects.id','=','reports.project_id')
//            ->join('phases','phases.id','=','reports.phases_id')
//            ->where('projects.employer_id',$user_id)
//            ->where('phases.status',3)
//            ->count();
    $roles = Role::query()
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_id', $user_id)
            ->get();
//    $ticket_count = Ticket::query()
//        ->where('to',$user_id)
//        ->where('seen',0)
//        ->count();
//
//    $message_count = \App\Models\SystemAlert::query()
//        ->where('system_alerts.user_id',$user_id)
//        ->where('seen',0)
//        ->count();
//
//        $letter_count = \App\Models\LetterContacts::query()
//        ->where('user_id', $user_id)
//        ->where('seen', 0)
//        ->count()
@endphp

<div id="left-sidebar" class="sidebar">
    <div class="navbar-brand">
        <a href="#">
            <img src="{{asset('images/hamyaran.png')}}" alt="نرم افزار مدیریت قراردادها" class="img-fluid logo ">
            <span>سامانه نما</span>
            <span class="span_title_s">(نرم افزار مدیریت قراردادها)</span>
        </a>
        <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right"><i
                class="lnr lnr-menu icon-close"></i>
        </button>
    </div>
    <div class="sidebar-scroll">
        <div class="user-account">
            <div class="user_div">
                <img src="{{asset($users->image ?? 'placeholder/user_placeholder.png')}}" class="img_round user-photo"
                     alt="عکس کارفرما">
            </div>
            <div class="drp dropdown">
                <a href="#" class=" user-name" data-toggle="dropdown">
                    <span class="span_panel_name">
                        {{$users->name . ' ' . $users->family}}
                    </span>
                </a>
                <strong>کارشناس مرکز نظام سازی</strong>
            </div>
        </div>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul id="main-menu" class="metismenu">
                @if(count($roles)>1)
                    <li>
                        <a href="#" class="has-arrow">
                            <i class="icon-key"></i>
                            <span>
                                پنل کارشناس مرکز نظام سازی
                        </span>
                        </a>
                        <ul>
                            @foreach($roles as $role)
                                <li>
                                    <a href="{{route($role['route_title'])}}">
                                        {{$role['title']}}
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </li>
                @endif
                <li>
                    <a href="{{route('deputy_plan_program_index')}}">
                        <i class="icon-home"></i>
                        <span>صفحه من</span>
                    </a>
                </li>
                <li>
                    <a href="" class="has-arrow">
                        <i class="icon-list"></i>
                        فرم درخواست مرخصی
                    </a>
                    <ul>
                        <li>
                            <a href="#">
                                <span>تایید مرخصی ها</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('leave_adjustment_expert_index')}}">
                                <span>لیست مرخصی ها</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('leave_adjustment_expert_create')}}">
                                <span>تعریف درخواست مرخصی</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="" class="has-arrow">
                        <i class="icon-bag"></i>
                        اعزام به ماموریت
                    </a>
                    <ul>
                        <li>
                            <a href="#">
                                <span>لیست ماموریت ها</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span>فرم اعزام به ماموریت</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="{{route('logout')}}" style="color: red !important;">
                        <i class="icon-power" style="color: red !important;"></i>
                        <span>خروج</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</div>
