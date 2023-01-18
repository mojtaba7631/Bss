@extends('employer.layout.employer_layout')
@section('title')
    نمایش پروژه
@endsection
@section('css')
    <style>
        .my_title {
            color: #17a2b8;
            margin-bottom: 15px;
            font-size: 14pt;
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
                        <h1>نمایش پروژه</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">پروژه ی {{$project->title}}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('employer_project_index')}}" class="btn btn-sm btn-primary" title="">
                            <i class="fa fa-arrow-right mr-4"></i>
                            بازگشت به پروژه ها
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-4 px-5">
                            <div class="row special_border mt-3">
                                <h5 class="h_title">عنوان پروژه</h5>
                                <div class="col-12">
                                    <p class="d-inline-block">{{$project->title}}</p>
                                </div>
                            </div>
                            @if($project->comment)
                                <div class="row special_border mt-5">
                                    <h5 class="h_title">توضیحات پروژه</h5>
                                    <div class="col-12">
                                        <p class="d-inline-block">{!! $project->comment !!}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="row special_border mt-5 mb-3">
                                <h5 class="h_title">فایل پروپوزال پروژه</h5>
                                <div class="col-12">
                                    <label>
                                        <a class="btn btn-info" href="{{asset($project->file)}}">
                                            <i class="fa fa-download mr-2"></i>
                                            دانلود فایل
                                        </a>
                                    </label>
                                </div>
                            </div>

                            @if($project->confirmed_by_employer == 0)
                                <div class="row mt-4 justify-content-end">
                                    <div class="col-12 col-md-4">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <form method="post" action="{{route('employer_project_confirmation')}}">
                                                    @csrf
                                                    <input type="hidden" name="project_id" value="{{$project->id}}">
                                                    <button type="submit" class="btn btn-success w-100">تایید پروژه
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <button onclick="open_modal({{$project->id}})" type="submit"
                                                        class="btn btn-danger w-100">
                                                    عدم تایید
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

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
                    <h4 class="modal-title">عدم تایید پروژه</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <textarea id="pro_err" name="pro_err" rows="4"
                              placeholder="لطفا دلیل عدم تایید را بنویسید"
                              class="form-control mt-2 w-100"></textarea>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" value="{{$project->id}}" name="project_id">
                    <button onclick="make_not_active()" type="button" class="btn btn-success">بله</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>

                </div>

            </div>
        </div>
    </div>

@endsection
