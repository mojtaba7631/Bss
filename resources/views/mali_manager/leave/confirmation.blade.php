@extends('mali_manager.layout.mali_layout')
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
                                                <th>جواب مرخصی</th>
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
                                                        @if ( $leave['leave_user_info']['type'] == 0 )
                                                            {{$leave['leave_user_info']['name'] . ' ' . $leave['leave_user_info']['family']}}
                                                        @else
                                                            {{$leave['leave_user_info']['ceo_name'] . ' ' . $leave['leave_user_info']['ceo_family']}}
                                                        @endif
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
                                                        {{$leave->disapproval_reason}}
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-success confirm_btn" title="تایید مرخصی"
                                                           id="confirm_btn"
                                                           data-leave="{{ $leave['id'] }}">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                        <a class="btn btn-danger un_confirm_btn" title="عدم تایید مرخصی"
                                                           id="un_confirm_btn"
                                                           data-disleave="{{ $leave['id'] }}">
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
            <form action="#" enctype="multipart/form-data"
                  class="modal-content">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">توضیحات</h4>
                    <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p>لطفا در صورت امکان دلیل عدم تایید را ذکر نمایید</p>
                    <input type="text" class="form-control disapproval" placeholder="دلیل عدم موافقت" name="disapproval">
                    <input type="hidden" id="leave_id_modal" class="leave_id_modal">
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="submit_leave_btn" type="submit" class="btn btn-danger mr-1 ml-1 submit_leave_btn">
                        عدم تایید مرخصی
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('js')
    <script>
        var close_modal = $('.close_modal');

        var un_confirm_btn = $('.un_confirm_btn');

        un_confirm_btn.on('click' , function () {
            let leave_id = jQuery(this).data("disleave");
            $('#leave_id_modal').val(leave_id);
            var my_modal = $('#my_modal');
            my_modal.show();

        })


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
                url: "{{route('maliManager_leave_agreement')}}",
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
                    window.location.href = "{{route('maliManager_leave_confirmation')}}"
                }, error: function (err) {
                    //
                }
            });
        });

        jQuery(document).on('click', '.submit_leave_btn', function (e) {
            // function getCookie(cname) {
            //     let name = cname + "=";
            //     let decodedCookie = decodeURIComponent(document.cookie);
            //     let ca = decodedCookie.split(';');
            //     for(let i = 0; i <ca.length; i++) {
            //         let c = ca[i];
            //         while (c.charAt(0) == ' ') {
            //             c = c.substring(1);
            //         }
            //         if (c.indexOf(name) == 0) {
            //             return c.substring(name.length, c.length);
            //         }
            //     }
            //     return "";
            // }

            // var disleave_id = getCookie('leave_id');

            let disleave_id = $('#my_modal .leave_id_modal').val();

            let disapproval = $('.disapproval').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('maliManager_leave_disagreement')}}",
                type: "post",
                data: {
                    'disleave_id': disleave_id,
                    'disapproval': disapproval,
                },
                success: function (res) {
                    // alert(JSON.stringify(res.responseJSON));
                    // alert(res.status);

                    if (res.status == true) {
                        // alert('bia too');
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                        })
                        // window.setInterval('refresh("not")', 3000);
                    }
                    window.location.href = "{{route('maliManager_leave_confirmation')}}"
                }, error: function (err) {
                    alert('kjjhk');
                }
            });
        });

    </script>
@endsection
