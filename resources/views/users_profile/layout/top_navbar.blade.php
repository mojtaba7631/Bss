@php
    use App\Models\User;
    $user_deactive_count = User::query()->where('active',0)->count();
    $users_deactive = User::query()->where('active',0)->get();
@endphp

<nav class="navbar top-navbar">
    <div class="container-fluid">

        <div class="navbar-left">
            <div class="navbar-btn">
                <a href="index-2.html"><img src="../assets/images/icon.svg" alt="Oculux Logo"
                                            class="img-fluid logo"></a>
                <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
            </div>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                        <i class="icon-envelope"></i>
                        <span class="notification-dot bg-green">4</span>
                    </a>
                    <ul class="dropdown-menu right_chat email vivify fadeIn">
                        <li class="header green">شما 4 ایمیل جدید دارید</li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <div class="avtar-pic w35 bg-red"><span>FC</span></div>
                                    <div class="media-body">
                                        <span class="name">آرش خادملو <small
                                                class="float-right text-muted">همین حالا</small></span>
                                        <span class="message">بروزرسانی گیتهاب</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <div class="avtar-pic w35 bg-indigo"><span>FC</span></div>
                                    <div class="media-body">
                                        <span class="name">آرش خادملو <small
                                                class="float-right text-muted">12 دقیقه پیش</small></span>
                                        <span class="message">پیام های جدید</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="../assets/images/xs/avatar5.jpg" alt="">
                                    <div class="media-body">
                                        <span class="name">آرش خادملو <small
                                                class="float-right text-muted">38 دقیقه پیش</small></span>
                                        <span class="message">رفع اشکال طراحی</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="media mb-0">
                                    <img class="media-object " src="../assets/images/xs/avatar2.jpg" alt="">
                                    <div class="media-body">
                                        <span class="name">آرش خادملو <small
                                                class="float-right text-muted">12 دقیقه پیش</small></span>
                                        <span class="message">رفع اشکال</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                        <i class="icon-bell"></i>
                        <span class="notification-dot bg-azura">{{$user_deactive_count}}</span>
                    </a>
                    <ul class="dropdown-menu feeds_widget vivify fadeIn">
                        <li class="header blue"> شما{{$user_deactive_count}} اطلاعیه جدید دارید</li>
                        @foreach($users_deactive as $user)
                            @if($user->type === 0 && $user->active === 0)
                            <li>
                                <a href="#">
                                    <div class="feeds-left bg-red"><i class="fa fa-check"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title text-danger">{{ $user->name .' '. $user->family}}</h4>
                                        <small>این کاربر ثبت نام کرده و فعال نشده است.</small>
                                    </div>
                                </a>
                            </li>
                            @elseif($user->type === 1 && $user->active === 0)
                                <li>
                                    <a href="#">
                                        <div class="feeds-left bg-red"><i class="fa fa-check"></i></div>
                                        <div class="feeds-body">
                                            <h4 class="title text-danger">شرکت {{ $user->co_name }}</h4>
                                            <small>این کاربر ثبت نام کرده و فعال نشده است.</small>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>

        <div class="navbar-right">
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    <li><a href="javascript:void(0);" class="search_toggle icon-menu" title="نتیجه جستجو"><i
                                class="icon-magnifier"></i></a></li>
                    <li><a href="javascript:void(0);" class="right_toggle icon-menu" title="منوی راست"><i
                                class="icon-bubbles"></i><span class="notification-dot bg-pink">2</span></a></li>
                    <li><a href="page-login.html" class="icon-menu"><i class="icon-power"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
    </div>
</nav>
