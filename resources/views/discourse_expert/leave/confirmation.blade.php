@extends('discourse_expert.layout.discourse_expert_layout')
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
                                                        @if($leave->confirmation == 1)
                                                            <p class="badge badge-success">
                                                                تایید شده
                                                            </p>
                                                        @else
                                                            <p class="badge badge-danger">
                                                                تایید نشده
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-primary" title="تایید مرخصی" id="confirm_btn">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                        <input type="hidden" value="{{$leave['leave_id']}}" id="leave_id">
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
                    <h4 class="modal-title">تایید مرخصی</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p>آیا با تایید مرخصی موافقید؟</p>
                    <input type="text" class="form-control" placeholder="دلیل عدم موافقت">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="submit_leave_btn" type="submit" class="btn btn-success mr-1 ml-1">
                        بله
                    </button>
                    <button type="button" class="btn btn-danger" id="unsubmit_leave_btn" data-dismiss="modal">خیر</button>
                </div>
            </form>

        </div>
    </div>

@endsection
@section('js')
    <script>

        var confirm_btn = $('#confirm_btn');

        confirm_btn.click(function (){
            var my_modal = $('#my_modal');
            my_modal.show();
        });

        var submit_leave_btn = $('#submit_leave_btn');
        submit_leave_btn.click(function (){
            var my_modal = $('#my_modal');
            my_modal.hide();

            var leave_id = $('#leave_id').val();
            $.ajax({
                url : "{{ route('discourse_expert_leave_agreement') }}",
                type:"post",
                dataType : "json",
                data : {
                    _token: '{{csrf_token()}}',
                    'leave_id' : leave_id,
                },
                success:function (res)
                {
                    if(res.status == true){
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                        })

                    }
                    window.location.href = "{{route('leave_discourse_expert_index')}}"

                },error : function (err){

                }
            });






        });

        var unsubmit_leave_btn = $('#unsubmit_leave_btn');
        unsubmit_leave_btn.click(function (){
            var leave_id = $('#leave_id').val();
            alert('leave_id');
            my_modal.hide();
        });

    </script>
@endsection
