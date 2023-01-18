@extends('legalUser.layout.legal_layout')
@section('title')
    پیام ها
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
        .persian_char_code {
            display: inline-block;
            border: 2px solid #36c3ff;
            padding: 7px 10px;
            text-align: center;
            border-radius: 5px;
            margin-top: 2px;
        }

        .pccp {
            width: fit-content;
            margin: 0 auto;
        }

        .pccp:after {
            content: "";
            display: block;
            clear: both;
        }

        .pcc {
            float: left;
            margin: 5px;
        }
    </style>
@endsection
@section('js')
    <script>
        var project_unique_code = $(".project_unique_code");
        $.each(project_unique_code, function (i, val) {
            var txt = project_unique_code.eq(i).text();
            txt = txt.trim();
            if (txt === "---") {
                project_unique_code.eq(i).html('<p style="color: orangered; font-weight: bold">کد اختصاص نشده است</p>');
            } else {
                var txt_arr = txt.split(" ");
                var final = "<p class='pccp'><span class='pcc mt-3'>" + txt_arr[0] + "</span><span class='pcc persian_char_code'>" + txt_arr[1] + "</span><span class='pcc mt-3'>" + txt_arr[2] + "</span></p>";
                project_unique_code.eq(i).html(final);
            }

        })
    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>پیام ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">پیام ها</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(!empty($alerts->all()))
                            <div class="table-responsive">
                                <table class="table table-hover table-custom spacing8">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>کد پروژه</th>
                                        <th>پروژه</th>
                                        <th>فاز</th>
                                        <th>تاریخ</th>
                                        <th>پیام هشدار</th>
                                    </tr>
                                    </thead>
                                    @php $row = (($alerts->currentPage() - 1) * $alerts->perPage() ) + 1; @endphp
                                    <tbody>
                                    @foreach($alerts as $alert)
                                        <tr>
                                            <td>
                                                {{convertToPersianNumber($row)}}
                                            </td>
                                            <td>
                                                    <span class="project_unique_code">
                                                        @if($alert->project_unique_code == null)
                                                            ---
                                                        @else
                                                            {{$alert->project_unique_code}}
                                                        @endif
                                                    </span>
                                            </td>
                                            <td>{{$alert->title}}</td>
                                            <td>{{$alert->phase_number}}</td>
                                            <td>{{$alert->jalali_date}}</td>
                                            <td>{{$alert->message}}</td>

                                        </tr>
                                        @php $row++ @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{$alerts->links()}}
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
@endsection
