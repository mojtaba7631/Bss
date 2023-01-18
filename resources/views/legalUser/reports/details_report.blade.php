@extends('legalUser.layout.legal_layout')
@section('title')
    تحویل گزارش فاز
@endsection
@section('css')
    <style>
        .light_version .table tr td, .light_version .table tr th {
            background: none;
        }

        .light_version .table.table-custom tbody tr:nth-child(odd) {
            background: #e1f3ff !important;
        }

        a:hover {
            color: #007bff !important;
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
                        <h1>تحویل گزارش فاز</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">تحویل گزارش فاز</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            تحویل گزارش فاز پروژه {{ $projects->title }}
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('legal_reports_upload')}}" id="frm_report"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <input type="hidden" value="{{$projects->id}}" id="project" name="project">
                                        <label>انتخاب فاز پروژه</label>
                                        <label for="phase"></label>
                                        <select required name="phase" id="phase" class="form-control mb-5">
                                            @foreach($phases as $phase)
                                                <option value="{{$phase->f_id}}">
                                                    {{$phase->phase_number}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label>بارگذاری فایل گزارش</label>
                                        <div class="card card_bottom mt-5">
                                            <div class="body">
                                                <input id="file_src" type="file" class="dropify"
                                                       name="file_src">
                                            </div>
                                        </div>
                                        <label class="text-danger text-center">حداکثر حجم فایل ارسالی 100 مگا بایت می باشد</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <label>متن ارائه گزارش</label>
                                        <label for="comments"></label>
                                        <textarea id="comments" name="comments" class="summernote"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button id="btn_report" class="btn btn-success w-25 mt-5">ثبت گزارش</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
