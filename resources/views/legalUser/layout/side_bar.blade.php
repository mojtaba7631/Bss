@php
    use App\Models\User;
    use App\Models\SystemAlert;
    use App\Models\Role;
    use App\Models\Ticket;
    use App\Models\Project;
    use App\Models\Project_error;
    $user_id = auth()->id();

    $users = User::query()
        ->where('id',$user_id)
        ->firstOrFail();

    $alert_count = SystemAlert::query()
            ->where('user_id', $user_id)
            ->where('seen',0)
            ->count();

    $projects_info = Project::query()
    ->where('user_id',$user_id)
    ->get();

    $contract_save_count = Project::query()
     ->where('user_id', $user_id)
    ->whereIn('status',[2,12,7])
    ->count();

    $error_count = 0;
    foreach ($projects_info as $project_info){
        $error_count += Project_error::query()
                ->where('project_id',$project_info->id)
                ->where('read_message',0)
                ->count();
    }

    $roles = Role::query()
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_id', $user_id)
            ->get();

    $ticket_count = Ticket::query()
            ->where('to',$user_id)
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
                <img src="{{asset($users->image ?? 'placeholder/user_placeholder.png')}}"
                     class="img_round user-photo-legal"
                     alt="عکس ادمین">
            </div>
            <div class="drp dropdown">

                <a href="#" class=" user-name" data-toggle="dropdown">
                    <span style="display: block">شرکت</span>
                    <span style="font-size: 10pt">{{$users->co_name}}</span>
                </a>
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
                    <a href="{{route('legalUser_index')}}">
                        <i class="icon-home"></i>
                        <span>صفحه ی من</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('legal_profile')}}">
                        <i class="icon-user"></i>
                        <span>پروفایل من</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="has-arrow">
                        <i class="icon-grid"></i>
                        <span style="position: relative">پروژه ها</span>
                        @if($contract_save_count == 0)
                            <span></span>
                        @else
                            <span class="fa fa-bell"
                                  style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center;font-size:11px;padding: 5px">
                                </span>
                        @endif
                    </a>
                    <ul>
                        <li><a href="{{route('legal_project_add')}}">تعریف پروژه</a></li>
                        <li><a href="{{route('legal_project_in_process')}}">
                                کارتابل پروژه ها
                                @if($contract_save_count == 0)
                                    <span></span>
                                @else
                                    <span
                                        style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">
                                {{$contract_save_count}}
                            </span>
                                @endif
                            </a>
                        </li>
                        <li><a href="{{route('legal_project_completed')}}">تکمیل شده</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('legal_reports_index')}}">
                        <i class="icon-notebook"></i>
                        <span>گزارش های ارسال شده</span>
                    </a>
                </li>

                <li class="">
                    <a href="{{route('legal_ticket_index')}}">
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
                            <a href="{{route('legalUser_letter_new')}}">جدید</a>
                        </li>
                        <li>
                            <a href="{{route('legalUser_letter_sent_index')}}">ارسالی</a>
                        </li>
                        <li>
                            <a href="{{route('legalUser_letter_delivered_index')}}">دریافتی</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="{{route('legal_alerts_index')}}">
                        <i class="icon-info"></i>
                        <span style="position: relative">پیام ها</span>
                        @if($alert_count == 0)
                            <span></span>
                        @else
                            <span
                                style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">{{$alert_count}}
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
