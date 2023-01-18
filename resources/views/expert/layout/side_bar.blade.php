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

    $project_count = Project::query()
            ->where('status',1)
            ->count();
    $contract_count = Project::query()
            ->where('status',3)
            ->count();
    $report_count = Report::query()
            ->join('projects','projects.id','=','reports.project_id')
            ->join('phases','phases.id','=','reports.phases_id')
            ->where('projects.employer_id',$user_id)
            ->where('phases.status',3)
            ->count();
    $roles = Role::query()
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_id', $user_id)
            ->get();
    $ticket_count = Ticket::query()
        ->where('to',$user_id)
        ->where('seen',0)
        ->count();

    $message_count = \App\Models\SystemAlert::query()
        ->where('system_alerts.user_id',$user_id)
        ->where('seen',0)
        ->count();

        $letter_count = \App\Models\LetterContacts::query()
        ->where('user_id', $user_id)
        ->where('seen', 0)
        ->count()
@endphp

<div id="left-sidebar" class="sidebar">
    <div class="navbar-brand">
        <a href="#">
            <img src="{{asset('images/hamyaran.png')}}" alt="نرم افزار مدیریت قراردادها" class="img-fluid logo ">
            <span>سامانه نما</span>
            <span class="span_title_s">(نرم افزار مدیریت قراردادها)</span>
        </a>
        <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right"><i
                class="lnr lnr-menu icon-close"></i></button>
    </div>
    <div class="sidebar-scroll">
        <div class="user-account">
            <div class="user_div">
                <img src="{{asset($users->image ?? 'placeholder/user_placeholder.png')}}" class="img_round user-photo"
                     alt="عکس کارفرما">
            </div>
            <div class="drp dropdown">
                <a href="#" class=" user-name" data-toggle="dropdown">
                    <span class="span_panel_name">{{$users->name . ' ' . $users->family}}</span>
                </a>
                <strong>کارشناس</strong>

            </div>
        </div>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul id="main-menu" class="metismenu">
                @if(count($roles)>1)
                    <li>
                        <a href="#" class="has-arrow">
                            <i class="icon-key"></i>
                            <span>
                                پنل کاربری
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
                    <a href="{{route('expert_index')}}">
                        <i class="icon-home"></i>
                        <span>صفحه من</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="has-arrow">
                        <i class="icon-notebook"></i>
                        <span>نامه ها</span>
                        @if($letter_count == 0)
                            <span></span>
                        @else
                            <span
                                style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">
                                {{$letter_count}}
                            </span>
                        @endif
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('expert_letter_new')}}">جدید</a>
                        </li>
                        <li>
                            <a href="{{route('expert_letter_sent_index')}}">ارسالی</a>
                        </li>
                        <li>
                            <a href="{{route('expert_letter_delivered_index')}}">دریافتی</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('expert_contract_index')}}">
                        <i class="icon-docs"></i>
                        قراردادها
                    </a>
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
