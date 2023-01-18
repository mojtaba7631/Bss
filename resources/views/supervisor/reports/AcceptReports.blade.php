@extends('supervisor.layout.supervisor_layout')
@section('title')
    گزارش های تایید شده
@endsection
@section('css')
    <style>
        .my_a {
            cursor: pointer;
            color: #948f8f !important;
        }

        a:hover {
            color: #007bff !important;
        }

        i {
            font-size: 20px !important;
        }

    </style>
@endsection

@section('js')
    <script>
        function reject_report(phase_id) {
            var reject_report_modal = $('#reject_report_modal');
            reject_report_modal.find('#phase_id_reject_modal').val(phase_id);
            reject_report_modal.modal('show');
        }
    </script>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>گزارش ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">گزارش های تایید شده</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                @if(!empty($projects->all()))
                    <div class="col-12">
                        <div class="card">
                            <div class="body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="عنوان پروژه">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="وضعیت">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="کارفرما">
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
                            @if(!empty($projects->all()))
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8 text-center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان پروژه</th>
                                            <th>کارفرما</th>
                                            <th>فاز</th>
                                            <th>فایل گزارش</th>
                                            <th>وضعیت</th>
                                        </tr>
                                        </thead>
                                        @php $row = (($projects->currentPage() - 1) * $projects->perPage() ) + 1; @endphp
                                        <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>{{convertToPersianNumber($row)}}</td>
                                                <td>
                                                    {{$project->p_title}}
                                                </td>
                                                <td>
                                                    {{$project->employer->name}}
                                                    <br>
                                                    {{$project->employer->family}}
                                                </td>
                                                <td>
                                                    {{$project->phase_number}}
                                                </td>
                                                <td>
                                                    @if($project->file_src != '')
                                                        <a href="{{route('supervisor_report_download_file',['report' => $project->report_id])}}"

                                                           class="btn btn-warning">
                                                            <i class="fa fa-download mr-2"></i>
                                                            دانلود فایل گزارش
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                <span class="{{$project->status_css}}">
                                                    <?= $project->s_title ?>
                                                </span>
                                                </td>
                                            </tr>
                                            @php $row++ @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{$projects->links()}}
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12">
                                        <p class="alert alert-danger mb-0">
                                            پروژه ای یافت نشد
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

    <!-- The Modal -->
    <div class="modal" id="reject_report_modal">
        <div class="modal-dialog">
            <form action="{{route('supervisor_report_reject')}}" method="post"
                  class="modal-content">
            @csrf
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">رد گزارش</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>علت عدم تایید گزارش را ذکر نمایید:</label>
                            <input type="hidden" id="phase_id_reject_modal" name="phase_id">
                            <textarea required name="description" class="form-control" rows="8"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success mr-2">ثبت و ارجاع به مجری</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف</button>
                </div>
            </form>
        </div>
    </div>
@endsection
