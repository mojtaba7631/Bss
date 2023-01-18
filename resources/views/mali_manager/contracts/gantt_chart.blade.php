@extends('mali_manager.layout.mali_layout')

@section('title')
    گانت چارت
@endsection

@section('css')
    <style>

        .gantt_ul li {
            float: left;
            width: 150px;
            text-align: left;
            list-style: none;
            margin-top: 25px;
        }

        .gantt_ul li label {
            height: 25px;
            font-size: 8pt;
            width: 100%;
            text-align: right;
            padding: 0 5px;
            line-height: 20px;
            margin-bottom: 0;
        }

        .gantt_ul li:nth-child(even) label {
            font-weight: bold;
        }

        .gantt_ul li span {
            display: block;
            width: 30px;
            height: 30px;
            border: 5px solid #e8e8e8;
            margin: 0;
            border-radius: 50%;
            position: relative;
            background: #d7d7d7;
        }

        .gantt_ul li span:after {
            content: "";
            position: absolute;
            width: 125px;
            left: -125px;
            height: 5px;
            background: #e8e8e8;
            top: 7px;
        }

        .gantt_ul li span.passed {
            background: #019533;
            border: 5px solid #0BDA51;
        }

        .gantt_ul li span.passed:after {
            background: #0BDA51;
            border: none;
            height: 5px;
            top: 7px;
        }

        .my_pr_title {
            font-size: 10pt;
            font-weight: bold;
        }

        .employer_img {
            width: auto;
            height: 62px;
        }
    </style>
@endsection

@section('js')
    <script>
        var employer_form = $("#employer_form");

        function submit_form(tag) {
            var employer_id = $(tag).find('option:selected').val();

            var loader = '<p class="text-center mt-4 font-weight-bold" role="status">لطفا منتظر بمانید...</p>';

            $(tag).parents('.col-12').html(loader);

            window.location.href = '/maliManager-access/dashboard/contracts/gantt_chart/' + employer_id;
        }
    </script>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>گانت چارت</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">گانت چارت</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row">
                                <form id="employer_form" class="col-12" method="post">
                                    @csrf
                                    <label>مجری پروژه</label>
                                    <select onchange="submit_form(this)" class="form-control">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($employers as $employer)
                                            <option @if($employer_id == $employer['employer_id']) selected="selected"
                                                    @endif value="{{$employer['employer_id']}}">
                                                {{$employer['co_name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-9">
                    <div class="card mb-2">
                        <div class="card-body">
                            @if($employer_id == 0)
                                <div class="row">
                                    <div class="col-12">
                                        <p class="alert alert-warning text-center mb-0">
                                            لطفا ابتدا مجری را انتخاب نمایید
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($employer_id > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <img class="employer_img" src="{{asset($employer_info['image'])}}">

                                        <h6 class="d-inline-block ml-2">{{$employer_info['co_name']}}</h6>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($employer_id > 0)
                @php $row = 1; @endphp
                @foreach($projects as $project)
                    <div class="row clearfix">
                        <div class="col-12">
                            <div class="card mb-1">
                                <div class="card-body">
                                    <p class="my_pr_title mb-0">
                                        <span class="text-danger">{{$row}} - </span>
                                        {{$project['title']}}
                                    </p>

                                    <ul class="gantt_ul clearfix">
                                        <li>
                                            <label>در انتظار تایید کارفرما</label>
                                            <span class="passed"></span>
                                        </li>

                                        <li>
                                            <label>در انتظار ثبت فرم قرارداد</label>
                                            <span @if($project['status'] > 2) class="passed" @endif></span>
                                        </li>

                                        <li>
                                            <label>در انتظار امضای دیجیتال</label>
                                            <span @if($project['status'] > 7) class="passed" @endif></span>
                                        </li>

                                        @foreach($project['phases'] as $phase)
                                            <li>
                                                <label>
                                                    @if($phase['phase_number'] == 0) پیش پرداخت @else
                                                        فاز {{$phase['phase_number']}} @endif
                                                </label>
                                                <span @if($phase['status'] == 7) class="passed" @endif></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $row++; @endphp
                @endforeach
            @endif
        </div>
    </div>




@endsection
