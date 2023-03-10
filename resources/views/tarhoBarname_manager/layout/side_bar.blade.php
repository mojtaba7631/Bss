@php
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Project;
    use App\Models\Phase;
    use App\Models\Ticket;
    use App\Models\LetterContacts;
    use App\Models\PaymentPeriod;

    $user_id = auth()->user()->id;

    $users = User::query()
        ->where('id',$user_id)
        ->firstOrFail();

    $contract_count = Project::query()
            ->where('status',5)
            ->count();

    $roles = Role::query()
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_id', $user_id)
            ->get();

    $contract_financial = Phase::query()
            ->select('*','projects.id as project_id','phases.id as p_id','phases.status as p_status','projects.status as p_status')
            ->join('projects', 'projects.id', '=', 'phases.project_id')
            ->where('phases.status',5)
            ->where('projects.status',8)
            ->count();

    $ticket_count = Ticket::query()
        ->where('to',$user_id)
        ->where('seen',0)
        ->count();

    $letter_count = LetterContacts::query()
        ->select('letters.sent, letter_contacts.*')
        ->join('letters', 'letter_contacts.letter_id','=','letters.id')
        ->where('letter_contacts.user_id', $user_id)
        ->where('sent', 1)
        ->where('seen', 0)
        ->count();

    $payable_count_seen = PaymentPeriod::query()
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
        <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right">
            <i class="lnr lnr-menu icon-close"></i>
        </button>
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
                <strong>مدیر طرح و برنامه</strong>
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
                                    {{--                                    <a href="{{route($role['route_title'])}}">--}}
                                    <a href="{{route('employer_index')}}">
                                        {{$role['title']}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                <li>
                    <a href="{{route('tarho_Barname_manager_index')}}">
                        <i class="icon-home"></i>
                        <span>صفحه من</span>
                    </a>
                </li>

                <li class="">
                    <a href="{{route('tarhoBarname_profile')}}">
                        <i class="icon-user"></i>
                        <span>پروفایل من</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="has-arrow">
                        <i class="icon-layers"></i>
                        <span>قرارداد ها</span>
                        @if($contract_count == 0)
                            <span></span>
                        @else
                            <span class="fa fa-bell"
                                  style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center;font-size:11px;padding: 5px">
                                </span>

                        @endif
                    </a>

                    <ul>
                        <li>
                            <a href="{{route('tarhoBarname_contract_index')}}">
                                کارتابل قراردادها
                                @if($contract_count == 0)
                                    <span></span>
                                @else
                                    <span
                                        style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">{{$contract_count}}</span>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a href="{{route('tarhoBarname_full_contract')}}">
                                لیست تمام قراردادها
                            </a>
                        </li>

                        <li>
                            <a href="{{route('tarhoBarname_gantt_chart')}}">
                                گانت چارت
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="has-arrow">
                        <i class=" icon-credit-card"></i>
                        <span>امور مالی</span>
                        @if($payable_count_seen > 0)
                            <span class="fa fa-bell"
                                  style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center;font-size:11px;padding: 5px">
                            </span>
                        @endif
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('tarhoBarname_payable_index')}}">
                                کارتابل واریز
                                @if($payable_count_seen > 0)
                                    <span
                                        style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">
                                        {{$payable_count_seen}}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{route('tarhoBarname_financial_force_index')}}">
                                پرداخت فوری
                            </a>
                        </li>
                        <li>
                            <a href="{{route('tarhoBarname_final_payments')}}">
                                پرداخت شده ها
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="">
                    <a href="{{route('tarhoBarname_ticket_index')}}">
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

                <li class="">
                    <a href="{{route('tarhoBarname_get_debts')}}">
                        <i class="fa fa-money"></i>
                        <span style="position: relative">
                            بدهی ها
                        </span>
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
                            <a href="{{route('tarhobarname_letter_new')}}">جدید</a>
                        </li>
                        <li>
                            <a href="{{route('tarhobarname_letter_sent_index')}}">ارسالی</a>
                        </li>
                        <li>
                            <a href="{{route('tarhobarname_letter_delivered_index')}}">دریافتی</a>
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
