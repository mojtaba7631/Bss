@php
    use App\Models\User;
    $user_deactive_count = User::query()->where('active',0)->count();
    $users_deactive = User::query()->where('active',0)->get();
@endphp

<nav class="navbar top-navbar">
    <div class="container-fluid">

        <div class="navbar-left">
            <div class="navbar-btn">
                <a href="#"><img src="{{asset('images/hamyaran.png')}}" alt="نرم افزار مدیریت قراردادها" class="img-fluid logo"></a>
                <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
            </div>
        </div>

        <div class="navbar-right">
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    <li><a href="{{route('logout')}}" class="icon-menu"><i class="icon-power"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
    </div>
</nav>
