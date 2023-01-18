@php
    use App\Models\User;
    use App\Models\Project;
    use App\Models\Role;
    use App\Models\Ticket;

    $user_id = auth()->id();
    $users = User::query()
        ->where('id',$user_id)
        ->firstOrFail();
    $report_count = Project::query()
        ->join('phases','phases.project_id','projects.id')
            ->where('phases.status',2)
            ->where('projects.supervisor_id', $user_id)
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
        ->select('letters.sent, letter_contacts.*')
        ->join('letters', 'letter_contacts.letter_id','=','letters.id')
        ->where('letter_contacts.user_id', $user_id)
        ->where('sent', 1)
        ->where('seen', 0)
        ->count();
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
                     alt="عکس ادمین">
            </div>
            <div class="drp dropdown">
                <a href="#" class=" user-name" data-toggle="dropdown">
                    <span class="span_panel_name">{{$users->name . ' ' . $users->family}}</span>
                </a>
                <strong>ناظر</strong>
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
                    <a href="{{route('Supervisor_index')}}">
                        <i class="icon-home"></i>
                        <span>صفحه من</span>
                    </a>
                </li>

                <li class="">
                    <a href="{{route('supervisor_profile')}}">
                        <i class="icon-user"></i>
                        <span>پروفایل من</span>
                    </a>
                </li>

                <li class="">
                    <a href="{{route('supervisor_allProjects')}}">
                        <i class="icon-grid"></i>
                        <span>پروژه های من</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="has-arrow">
                        <i class="icon-notebook"></i>
                        <span>گزارش ها</span>
                        @if($report_count == 0)
                            <span></span>
                        @else
                            <span
                                style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">
                                {{$report_count}}
                            </span>
                        @endif
                    </a>
                    <ul>
                        <li><a href="{{route('supervisor_report_index')}}">جدید</a></li>
                        <li><a href="{{route('supervisor_report_Accept')}}">تایید شده</a></li>
                        <li><a href="{{route('supervisor_fullReport')}}">لیست کل گزارشات</a></li>
                    </ul>
                </li>
                <li class="">
                    <a href="{{route('super_ticket_index')}}">
                        <i class="icon-bubble"></i>
                        <span style="position: relative">
                            تیکت
                        </span>
                        @if($ticket_count == 0)
                            <span></span>
                        @else
                            <span
                                style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">
                                {{$ticket_count}}
                            </span>
                        @endif
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
                            <a href="{{route('supervisor_letter_new')}}">جدید</a>
                        </li>
                        <li>
                            <a href="{{route('supervisor_letter_sent_index')}}">ارسالی</a>
                        </li>
                        <li>
                            <a href="{{route('supervisor_letter_delivered_index')}}">دریافتی</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="{{route('supervisor_alert')}}">
                        <i class="icon-envelope"></i>
                        <span>پیام</span>
                        @if($message_count == 0)
                            <span></span>
                        @else
                            <span
                                style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">
                                {{$message_count}}
                            </span>
                        @endif
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
