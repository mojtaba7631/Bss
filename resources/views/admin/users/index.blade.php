@extends('admin.layout.admin_layout')
@section('title')
    کاربران
@endsection
@section('css')
    <style>
        .img_round {
            border-radius: 50% !important;
        }

        .my_a {
            cursor: pointer;
            color: #dddddd !important;
        }

        a:hover {
            color: #007bff !important;
        }

        .add_btn:hover {
            color: white !important;
        }
    </style>
@endsection
@section('js')
    <script>
        function delete_user(user_id) {
            var myModal = $('#myModal');
            myModal.find('#user_id').val(user_id);
            myModal.modal('show');
        }
    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>کاربران</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">کاربران</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('admin_user_add')}}" class="add_btn btn btn-sm btn-primary" title=""><i
                                class="fa fa-user-plus mr-3"></i>
                            ایجاد اعضا +
                        </a>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row clearfix">
                    @if(!empty($users->all()))
                        <div class="col-12">
                            <div class="card">
                                <div class="body">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="نام">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="وضعیت">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="نوع کاربر">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-block"
                                               title="">جستجو</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if(!empty($users->all()))
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing8">
                                            <thead>
                                            <tr>
                                                <th class="w60"></th>
                                                <th></th>
                                                <th>نام</th>
                                                <th>سمت</th>
                                                <th>تاریخ ایجاد شده</th>
                                                <th>نوع کاربر</th>
                                                <th>وضعیت</th>
                                                <th class="w100">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td class="width45">
                                                        <img
                                                            src="{{asset($user->image ?? 'placeholder/user_placeholder.png')}}"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="{{$user->family}}"
                                                            alt="Avatar" class="w35 h35 img_round">
                                                    </td>
                                                    <td>
                                                        @if($user->type === 0)
                                                            <span>{{$user->name}}</span>
                                                        @elseif($user->type === 1)
                                                            <span class="text-success">شرکت</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->type === 0)
                                                            <h6 class="mb-0">{{$user->family}}</h6>
                                                        @elseif($user->type === 1)
                                                            <h6 class="mb-0">{{$user->co_name}}</h6>
                                                        @endif
                                                    </td>
                                                    <td>
                                                       {{$user->title}}
                                                    </td>
                                                    <td>{{$user->created_at_jalali}}</td>
                                                    <td>{{ $user->type === 0 ? 'حقیقی' : 'حقوقی' }}</td>
                                                    @if($user->active === 0)
                                                        <td class="text-danger">غیرفعال</td>
                                                    @elseif($user->active === 1)
                                                        <td class="text-success">فعال</td>
                                                    @endif
                                                    <td>
                                                        @if($user->type === 0)
                                                            <a href="{{route('admin_user_view', ['user' => $user->user_id])}}"
                                                               class="my_a mr-2" title="نمایش"><i
                                                                    class="fa fa-edit"></i></a>
                                                        @elseif($user->type === 1)
                                                            <a href="{{route('admin_user_legal_view', ['user' => $user->user_id])}}"
                                                               class="my_a mr-2" title="نمایش">
                                                                <i class="fa fa-edit"></i></a>
                                                        @endif
                                                        {{--                                                    @if($user->post === 0)--}}
                                                        <a onclick="delete_user({{$user->user_id}})" type="button"
                                                           class="my_a js-sweetalert"
                                                           title="Delete" data-type="confirm"><i
                                                                class="fa fa-trash-o text-danger"></i></a>
                                                        {{--                                                    @elseif($user->post === 1)--}}
                                                        {{--                                                    @endif--}}

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{$users->links()}}
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="alert alert-danger mb-0">
                                                 کاربری یافت نشد
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">حذف کاربر</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    آیا از حذف کاربر مطمئن هستید؟
                </div>

                <!-- Modal footer -->
                <form action="{{route('admin_user_delete')}}" method="post" class="modal-footer">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">
                    <button type="submit" class="btn btn-success">بله مطمئنم</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
                </form>

            </div>
        </div>
    </div>
@endsection
