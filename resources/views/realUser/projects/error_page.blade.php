@extends('legalUser.layout.legal_layout')
@section('title')
    نمایش پروژه
@endsection
@section('css')
    <style>
        .card_header {
            background: #17C2D7;
            padding: 20px;
        }

        .col_12_card_body {
            background: #ffffff;
            padding: 10px;
        }
    </style>
@endsection
@section('js')
    <script>
        var myModal = $('#myModal');

        function make_not_active() {
            let message = $("#pro_err").val();
            let _token = "{{ csrf_token() }}";
            myModal.modal('hide');
            $.post("{{route('employer_project_notActive',['project'=>$project->id])}}",
                {
                    message,
                    _token
                },
                function (result) {
                    if (!result['error']) {

                        swal(result['errorMsg']);

                        setTimeout(function () {
                            window.location.href = '{{route("employer_project_index")}}';
                        }, 2000)
                    }
                }
            );
        }

        function open_modal(project_id) {

            myModal.find('#project_id').val(project_id);
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
                        <h1>پیغامهای عدم تایید</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">پروژه ی {{$project->title}}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('real_project_in_process')}}" class="btn btn-sm btn-primary" title="">
                            <i class="fa fa-arrow-right mr-4"></i>
                            بازگشت به پروژه ها
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="tab-content mt-0">
                            <div class="tab-pane show active" id="Users">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>متن پیغام</th>
                                            <th>از طرف</th>
                                            <th>تاریخ ایجاد شده</th>
{{--                                            <th class="w100">عملیات</th>--}}
                                        </tr>
                                        </thead>
                                        @php $row = (($error_messages->currentPage() - 1) * $error_messages->perPage() ) + 1; @endphp
                                        <tbody>
                                        @foreach($error_messages as $error_message)
                                            <tr>
                                                <td>
                                                    {{convertToPersianNumber($row)}}
                                                </td>
                                                <td>
                                                    {{$error_message->message}}
                                                </td>
                                                <td>
                                                    {{$error_message->name . ' ' . $error_message->family}}
                                                </td>
                                                <td>
                                                    {{convertToPersianNumber($error_message->created_at_jalali)}}
                                                </td>
{{--                                                <td>--}}
{{--                                                    <a href="{{route('employer_project_view', ['project' => $project->id])}}"--}}
{{--                                                       class="my_a " title="مشاهده پروژه">--}}
{{--                                                        <i class="fa fa-file-text-o"></i>--}}
{{--                                                    </a>--}}

{{--                                                </td>--}}
                                            </tr>
                                            @php $row++ @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
