@extends('tarhoBarname_manager.layout.tarhoBarname_layout')
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

        .verify_a {
            color: green;
        }

        .verify_a i {
            font-size: 30px !important;
        }

        .not_verify_a {
            color: red;
        }

        .not_verify_a i {
            font-size: 30px !important;
        }
        .remaining{
            border: 1px solid #000000;
            text-align: center;
        }
        .btn_ok{
            border: none;
            background: none;
            cursor: pointer;
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
                                    <form id="frm_verify" method="post" action="{{route('tarhoBarname_report_update')}}" enctype="multipart/form-data">
                                        @csrf

                                    <table class="table table-hover table-custom spacing8">
                                        <thead>
                                        <tr>
                                            <th>فاز</th>
                                            <th>توضیحات</th>
                                            <th>مبلغ</th>
                                            <th>مبلغ قابل پرداخت</th>
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
                                                    {{$report->cost}}
                                                </td>
                                                <td>
                                                    <input type="number" value="{{$report->cost}}"
                                                           name="remaining" class="form-control w-50">
                                                </td>
                                                <td>
                                                    <input name="report_id" type="hidden" value="{{$report->id}}">
                                                    <button type="submit"
                                                       class="verify_a btn_ok" title="تایید گزارش">
                                                        <i class="fa fa-thumbs-up"></i>
                                                    </button>
                                                    <a href="{{route('supervisor_report_update',['report'=>$report->id])}}"
                                                       class="not_verify_a" title="عدم تایید گزارش">
                                                        <i class="fa fa-thumbs-down"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
