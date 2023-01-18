@php
    use App\Models\User;
    use App\Models\Ticket;
    $user_id = auth()->user()->id;
    $users = User::query()
        ->where('id',$user_id)
        ->firstOrFail();
    $ticket_count = Ticket::query()
        ->where('to',$user_id)
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
                     alt="عکس ادمین">
            </div>
            <div class="drp dropdown">
                <strong class="span_panel_name">{{$users->name . ' ' . $users->family}}</strong>

            </div>
        </div>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul id="main-menu" class="metismenu">
                <li>
                    <a href="{{route('admin_index')}}">
                        <i class="icon-home"></i>
                        <span>صفحه من</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{route('admin_profile')}}">
                        <i class="icon-user"></i>
                        <span>پروفایل من</span>
                    </a>
                </li>
                    <li class="">
                        <a href="{{route('admin_user_index')}}">
                            <i class="icon-users"></i>
                            <span>کاربران</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="has-arrow">
                            <i class="icon-layers"></i>
                            <span>پروژه ها</span>
                        </a>
                        <ul>
                            <li><a href="{{route('admin_project_list_index')}}">لیست پروژه ها</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{route('admin_report_index')}}">
                            <i class="icon-pencil"></i>
                            <span>گزارش ها</span>
                        </a>
                    </li>
{{--                    <li class=""><a href="index2.html"><i class="icon-user"></i><span>صورت جلسه</span></a></li>--}}
                    <li>
                        <a class="has-arrow">
                            <i class=" icon-credit-card"></i>
                            <span>امور مالی</span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{route('admin_financial_index')}}">جدول پرداخت</a>
                            </li>
                            <li>
                                <a href="{{route('admin_financial_force_index')}}">جدول فوری</a>
                            </li>
                        </ul>
                    </li>
                <li>
                    <a href="{{route('admin_ticket_index')}}">
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
                            <a href="{{route('admin_letter_new')}}">جدید</a>
                        </li>
                        <li>
                            <a href="{{route('admin_letter_sent_index')}}">ارسالی</a>
                        </li>
                        <li>
                            <a href="{{route('admin_letter_delivered_index')}}">دریافتی</a>
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
