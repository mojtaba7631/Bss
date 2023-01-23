@extends('adjustment_manager.layout.adjustment_manager_layout')
@section('title',"لیست تایید مرخصی ها")
@section('css')
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>لیست تایید مرخصی ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">نما</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    لیست تایید مرخصی ها
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
                                @if(!empty($leaves->all()))
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing8 text-center">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>نوع مرخصی</th>
                                                <th>نام کاربر</th>
                                                <th>از روز - ساعت</th>
                                                <th>تا روز - ساعت</th>
                                                <th>وضعیت مرخصی</th>
                                                <th>عملیات</th>
                                            </tr>
                                            </thead>
                                            @if(!$searched)
                                                @php $row = (($leaves->currentPage() - 1) * $leaves->perPage() ) + 1; @endphp
                                            @else
                                                @php $row = 1; @endphp
                                            @endif
                                            <tbody>
                                            @foreach($leaves as $leave)
                                                <tr>
                                                    <td>
                                                        {{convertToPersianNumber($row)}}
                                                    </td>
                                                    <td>
                                                        @if($leave->type == 1)
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
                                                        {{$leave['name'] . ' ' . $leave['family']}}
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{$leave['start_day'] .' ' . ' ساعت - ' . $leave->start_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{$leave['end_day'] .' ' . ' ساعت - ' . $leave->end_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <span class="{{$leave['status']['status_css']}}">
                                                        {{$leave['status']['title']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-success confirm_btn" title="تایید مرخصی"
                                                           id="confirm_btn"
                                                           data-leave="{{ $leave['leave_id'] }}">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                        <a class="btn btn-danger un_confirm_btn" title="عدم تایید مرخصی"
                                                           id="un_confirm_btn"
                                                           data-leave="{{ $leave['leave_id'] }}">
                                                            <i class="fa fa-close"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php $row++ @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @if(!$searched)
                                            {{$leaves->links()}}
                                        @endif
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="alert alert-danger mb-0">
                                                مرخصی ای یافت نشد
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
        <div class="modal-dialog">
            <form action="" enctype="multipart/form-data"
                  class="modal-content">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">تایید مرخصی</h4>
                    <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p>آیا با تایید مرخصی موافقید؟</p>
                    <input type="text" class="form-control" placeholder="دلیل عدم موافقت">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="submit_leave_btn" type="submit" class="btn btn-success mr-1 ml-1 submit_leave_btn">
                        بله
                    </button>
                    <button type="button" class="btn btn-danger" id="unsubmit_leave_btn" data-dismiss="modal">خیر
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection
@section('js')
    <script>

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
                url: "{{route('leave_adjustment_manager_agreement')}}",
                type: "post",
                // dataType: "json",
                data: {
                    'leave_id': leave_id,
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
                    window.location.href = "{{route('leave_adjustment_manager_confirmation')}}"
                }, error: function (err) {
                    //
                }
            });
        });


        jQuery(document).on('click', '.un_confirm_btn', function (e) {
            let leave_id = jQuery(this).data("leave");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('adjustment_manager_leave_disagreement')}}",
                type: "post",
                data: {
                    'leave_id': leave_id,
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
                    window.location.href = "{{route('leave_adjustment_manager_confirmation')}}"
                }, error: function (err) {
                    //
                }
            });
        });

    </script>
@endsection
