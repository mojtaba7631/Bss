@extends('special_expert.layout.special_expert_layout')
@section('title',"لیست تایید ماموریت ها")
@section('css')
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>لیست تایید ماموریت ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">نما</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    لیست تایید ماموریت ها
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if(!empty($missions->all()))
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing8 text-center">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>نوع ماموریت</th>
                                                <th>نام کاربر</th>
                                                <th>از روز - ساعت</th>
                                                <th>تا روز - ساعت</th>
                                                <th>وضعیت ماموریت</th>
                                                <th>عملیات</th>
                                            </tr>
                                            </thead>
                                            @if(!$searched)
                                                @php $row = (($missions->currentPage() - 1) * $missions->perPage() ) + 1; @endphp
                                            @else
                                                @php $row = 1; @endphp
                                            @endif
                                            <tbody>
                                            @foreach($missions as $mission)
                                                <tr>
                                                    <td>
                                                        {{convertToPersianNumber($row)}}
                                                    </td>
                                                    <td>
                                                        @if($mission->type == 1)
                                                            <p class="badge badge-success">
                                                                ساعتی
                                                            </p>
                                                        @else
                                                            <p class="badge badge-danger">
                                                                روزانه
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$mission['name'] . ' ' . $mission['family']}}
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{$mission['start_day'] .' ' . ' ساعت - ' . $mission->start_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{$mission['end_day'] .' ' . ' ساعت - ' . $mission->end_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <span class="{{$mission['status']['status_css']}}">
                                                        {{$mission['status']['title']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-success confirm_btn" title="تایید ماموریت"
                                                           id="confirm_btn"
                                                           data-leave="{{ $mission['leave_id'] }}">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                        <a class="btn btn-danger un_confirm_btn" title="عدم تایید ماموریت"
                                                           id="un_confirm_btn"
                                                           data-disleave="{{ $mission['leave_id'] }}">
                                                            <i class="fa fa-close"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $row++ @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @if(!$searched)
                                            {{$missions->links()}}
                                        @endif
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="alert alert-danger mb-0">
                                                ماموریتی یافت نشد
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
    <div class="modal" id="my_modal">
        <div class="modal-dialog modal-content">
                @csrf
                <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">توضیحات</h4>
                <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <p>لطفا در صورت امکان دلیل عدم تایید را ذکر نمایید</p>
                <input type="text" class="form-control disapproval" placeholder="دلیل عدم موافقت"
                       name="disapproval">
                <input type="hidden" id="leave_id_modal" class="leave_id_modal">
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button id="submit_leave_btn" class="btn btn-danger mr-1 ml-1 submit_leave_btn">
                    عدم تایید ماموریت
                </button>
            </div>

        </div>
    </div>

@endsection
@section('js')
    <script>
        var un_confirm_btn = $('.un_confirm_btn');

        un_confirm_btn.on('click', function () {

            let leave_id = jQuery(this).data("disleave");
            $('#leave_id_modal').val(leave_id);
            var my_modal = $('#my_modal');
            my_modal.show();
        })

        var close_modal = $('.close_modal');

        close_modal.click(function () {
            var my_modal = $('#my_modal');
            my_modal.hide();
        })

        jQuery(document).on('click', '.confirm_btn', function (e) {
            let leave_id = jQuery(this).data("leave");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('special_expert_leave_agreement')}}",
                type: "post",
                // dataType: "json",
                data: {
                    'leave_id': leave_id
                },
                success: function (res) {
                    // alert(JSON.stringify(res.responseJSON));
                    if (res.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                        })
                        // window.setInterval('refresh("not")', 3000);
                    }
                    window.location.href = "{{route('leave_special_expert_confirmation')}}"
                }, error: function (err) {
                    //
                }
            });
        });

        jQuery(document).on('click', '.submit_leave_btn', function (e) {

            let disleave_id = $('#my_modal .leave_id_modal').val();

            // let leave_id = jQuery(this).data("disleave");
            let disapproval = $('.disapproval').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('special_expert_leave_disagreement')}}",
                type: "post",
                data: {
                    'leave_id': disleave_id,
                    'disapproval': disapproval,
                },
                success: function (res) {

                    if (res.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                        })
                        // window.setInterval('refresh("not")', 3000);
                    }
                    window.location.href = "{{route('leave_special_expert_confirmation')}}"
                }, error: function (err) {
                    //
                }
            });
        });

    </script>
@endsection
