@php
    use App\Models\User;
    use App\Models\Role;
    $user_id = auth()->user()->id;
    $users = User::query()
        ->where('id',$user_id)
        ->firstOrFail();

    $roles = Role::query()
        ->where('user_id',$user_id)
        ->get();

@endphp

<div id="left-sidebar" class="sidebar">
    <div class="navbar-brand">
        <a href="index-2.html">
            <img src="{{asset('placeholder/placeholder.png')}}" alt="سامانه مدیریت پروژه نما" class="img-fluid logo ">
            <span>نما</span>
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
            <div class="dropdown">
                @if($users->roles == 2)
                    <strong>داشبورد مدیریت</strong>
                @elseif($users->roles == 1)
                    <strong>داشبورد ادمین</strong>
                @elseif($users->roles == 0)
                    <strong>داشبورد مجری</strong>
                @elseif($users->roles == 4)
                    <strong>داشبورد ناظر</strong>
                @elseif($users->roles == 3)
                    <strong>داشبورد مدیریت</strong>
                @endif
                @if($users->type == 0)
                    <a href="javascript:void(0);" class=" user-name"
                       data-toggle="dropdown"><span>{{$users->name . ' ' . $users->family}}</span></a>
                @elseif($users->type == 1)
                    <a href="javascript:void(0);" class=" user-name"
                       data-toggle="dropdown"><span> شرکت {{$users->co_name}}</span></a>
                @endif
            </div>
        </div>
        <nav id="left-sidebar-nav" class="sidebar-nav">
            <ul id="main-menu" class="metismenu">
                <li class="active"><a href="index2.html"><i class="icon-home"></i><span>صفحه من</span></a></li>
                <li class=""><a href="index2.html"><i class="icon-user"></i><span>پروفایل من</span></a></li>
                @if(auth()->user()->roles == 1)
                    <li class=""><a href="{{route('admin_user_index')}}"><i class="icon-users"></i><span>کاربران</span></a>
                    </li>
                @endif
                @if(auth()->user()->roles == 1 || auth()->user()->roles == 0 || auth()->user()->roles == 4)
                    <li>
                        <a href="#myPage" class="has-arrow"><i class="icon-layers"></i><span>پروژه ها</span></a>
                        <ul>
                            <li><a href="{{route('admin_project_add')}}">تعریف پروژه جدید</a></li>
                            <li><a href="index4.html">لیست پروژه ها</a></li>
                            <li><a href="index5.html">پروژه های تکمیل شده</a></li>
                            <li><a href="index6.html">پروژه های در حال اجرا</a></li>
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->roles == 1 || auth()->user()->roles == 2 || auth()->user()->roles == 0 || auth()->user()->roles == 3)
                    <li>
                        <a href="#myPage" class="has-arrow"><i class="icon-docs"></i><span>قرارداد ها</span></a>
                        <ul>
                            <li><a href="index-2.html">داشبورد من</a></li>
                            <li><a href="index4.html">تجزیه و تحلیل وب</a></li>
                            <li><a href="index5.html">نظارت بر رویداد</a></li>
                            <li><a href="index6.html">عملکرد مالی</a></li>
                            <li><a href="index7.html">نظارت بر فروش</a></li>
                            <li><a href="index8.html">مدیریت بیمارستان</a></li>
                            <li><a href="index9.html">نظارت بر کمپین</a></li>
                            <li><a href="index10.html">تجزیه و تحلیل دانشگاه</a></li>
                            <li><a href="index11.html">تجزیه و تحلیل فروشگاه</a></li>
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->roles == 1|| auth()->user()->roles == 0)
                    <li>
                        <a href="#myPage" class="has-arrow"><i class="icon-pencil"></i><span>گزارش ها</span></a>
                        <ul>
                            <li><a href="index-2.html">داشبورد من</a></li>
                            <li><a href="index4.html">تجزیه و تحلیل وب</a></li>
                            <li><a href="index5.html">نظارت بر رویداد</a></li>
                            <li><a href="index6.html">عملکرد مالی</a></li>
                            <li><a href="index7.html">نظارت بر فروش</a></li>
                            <li><a href="index8.html">مدیریت بیمارستان</a></li>
                            <li><a href="index9.html">نظارت بر کمپین</a></li>
                            <li><a href="index10.html">تجزیه و تحلیل دانشگاه</a></li>
                            <li><a href="index11.html">تجزیه و تحلیل فروشگاه</a></li>
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->roles == 1 || auth()->user()->roles == 0)
                    <li class=""><a href="index2.html"><i class="icon-user"></i><span>صورت جلسه</span></a></li>
                @endif
                @if(auth()->user()->roles == 1 || auth()->user()->roles == 2 || auth()->user()->roles == 3 || auth()->user()->roles == 0)
                    <li class=""><a href="index2.html"><i class=" icon-credit-card"></i><span>امور مالی</span></a></li>
                @endif
                <li class=""><a href="index2.html"><i class="icon-note"></i><span>تیکت</span></a></li>
                <li class=""><a href="index2.html"><i class="icon-envelope"></i><span>نامه</span></a></li>

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
