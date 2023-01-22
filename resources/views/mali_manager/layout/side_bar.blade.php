@php
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Project;
    use App\Models\Ticket;
    use App\Models\Payment;
    use App\Models\Phase;

    $user_id = auth()->user()->id;
    $users = User::query()
        ->where('id',$user_id)
        ->firstOrFail();
       $contract_count = Project::query()
            ->where('status',4)
            ->orWhere('status',10)
            ->count();
        $roles = Role::query()
            ->join('role', 'role.id', '=', 'user_role.roles')
            ->where('user_id', $user_id)
            ->get();
        $ticket_count = Ticket::query()
        ->where('to',$user_id)
        ->where('seen',0)
        ->count();
        $phase_count = Payment::query()
         ->join('phases', 'phases.id','=','payments.phase_id')
        ->where('payments.status',0)
        ->where('is_force',0)
        ->where('sent_to_tarh',2)
        ->count();
        $payable_count = Phase::query()
            ->select('*', 'phases.id as phase_id', 'projects.id as project_id', 'phases.cost as phase_cost')
            ->join('projects', 'projects.id', '=', 'phases.project_id')
            ->where('phases.status', 5)
             ->where('phases.sent_to_tarh', 0)
            ->where('projects.supervisor_id', '!=', null)
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
                    <span class="span_panel_name">
                        {{$users->name . ' ' . $users->family}}
                    </span>
                </a>
                @if($user_id == 13)
                    <strong>مدیر مالی</strong>
                @elseif($user_id == 57)
                    <strong>کارشناس مالی</strong>
                @endif
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
                    <a href="{{route('maliManager_index')}}">
                        <i class="icon-home"></i>
                        <span>صفحه من</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{route('maliManager_profile')}}">
                        <i class="icon-user"></i>
                        <span>پروفایل من</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="has-arrow">
                        <i class="icon-docs"></i>
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
                            <a href="{{route('maliManager_contract_index')}}">
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
                            <a href="{{route('maliManager_sign_list')}}">
                                لیست کل قراردادها
                            </a>
                        </li>
                        <li>
                            <a href="{{route('maliManager_gantt_chart')}}">
                                نمودار گانت
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="has-arrow">
                        <i class=" icon-credit-card"></i>
                        <span>امور مالی</span>
                        @if($phase_count == 0)
                            <span></span>
                        @else
                            <span class="fa fa-bell"
                                  style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center;font-size:11px;padding: 5px">
                                </span>
                        @endif
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('maliManager_payable_index')}}">
                                جدول قابل پرداخت
                                <span
                                    style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">{{$payable_count}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('maliManager_financial_index')}}">
                                جدول واریز
                                <span
                                    style="position: absolute;color:white;background: red;width: 20px;height: 20px;border-radius: 50%;text-align: center">{{$phase_count}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('maliManager_fullPayments')}}">
                                واریز شده ها
                            </a>
                        </li>
                        {{--                        <li>--}}
                        {{--                            <a href="{{route('maliManager_checks')}}">--}}
                        {{--                                تحویل چک--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}
                    </ul>
                </li>
                <li class="">
                    <a href="{{route('maliManager_get_debts')}}">
                        <i class="fa fa-money"></i>
                        <span style="position: relative">
                            بدهی ها
                        </span>
                    </a>
                </li>
                <li>
                        <a href="" class="has-arrow">
                            <i class="icon-list"></i>
                            فرم درخواست مرخصی
                        </a>
                        <ul>
                            <li>
                                <a href="{{route('maliManager_leave_confirmation')}}">
                                    <span>تایید مرخصی ها</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('maliManager_leave_index')}}">
                                    <span>لیست مرخصی ها</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('maliManager_leave_create')}}">
                                    <span>درخواست مرخصی</span>
                                </a>
                            </li>
                        </ul>
                </li>
                <li class="">
                    <a href="{{route('maliManager_ticket_index')}}">
                        <i class="icon-note"></i>
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
                            <a href="{{route('maliManager_letter_new')}}">جدید</a>
                        </li>
                        <li>
                            <a href="{{route('maliManager_letter_sent_index')}}">ارسالی</a>
                        </li>
                        <li>
                            <a href="{{route('maliManager_letter_delivered_index')}}">دریافتی</a>
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
