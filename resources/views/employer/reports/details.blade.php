@extends('employer.layout.employer_layout')
@section('title')
    جزئیات گزارش
@endsection
@section('css')
    <style>
        .light_version .table tr td, .light_version .table tr th {
            background: none;
        }

        .light_version .table.table-custom tbody tr:nth-child(odd) {
            background: #e1f3ff !important;
        }

       .verify_a{
           color: green;
       }
       .verify_a i{
           font-size: 30px !important;
       }
        .not_verify_a{
            color: red;
        }
        .not_verify_a i{
            font-size: 30px !important;
        }
    </style>
@endsection
@section('js')
    <script>

    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>جزئیات گزارش</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">جزئیات
                                    گزارش {{$project->title}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="tab-content mt-0">
                            <div class="tab-pane show active" id="Users">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>فاز</th>
                                            <th>توضیحات</th>
                                            <th>دانلود فایل</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reports as $report)
                                            <tr>
                                                <td>
                                                    {{$report->phase_number}}
                                                </td>
                                                <td>
                                                    {{$report->comments}}
                                                </td>
                                                <td>
                                                    <a class="btn btn-warning" href="{{url($report->file_src)}}">دانلود
                                                        فایل گزارش</a>
                                                </td>
                                                <td>

                                                    <a href="{{route('employer_report_update',['report'=>$report->id])}}"
                                                       class="verify_a" title="تایید گزارش">
                                                        <i class="fa fa-thumbs-up"></i>
                                                    </a>
                                                    <a href="{{route('employer_report_update',['report'=>$report->id])}}"
                                                       class="not_verify_a" title="عدم تایید گزارش">
                                                        <i class="fa fa-thumbs-down"></i>
                                                    </a>

                                                </td>
                                            </tr>
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
