@extends('mali_manager.layout.mali_layout')
@section('title',"نامه های ارسال شده")
@section('css')
    <style>
        .table tr td, .table tr th {
            border: none !important;
        }

        .table-custom th {
            background: #07383e !important;
            color: #fff !important;
        }

        .light_version .table.table-custom tbody tr:nth-child(odd) {
            background: #edfcff !important;
        }
    </style>
@endsection

@section('js')
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>نامه های ارسال شده</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">نامه های دریافت شده</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('maliManager_letter_new')}}" class="add_btn btn btn-sm btn-primary" title=""><i
                                class="fa fa-edit mr-2"></i>
                            نوشتن نامه جدید
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(!empty($letters->all()))
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing5">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان</th>
                                            <th>تاریخ ارسال</th>
                                            <th>وضعیت</th>
                                            <th>مشاهده</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @php $row = (($letters->currentPage() - 1) * $letters->perPage() ) + 1; @endphp
                                        @foreach($letters as $letter)
                                            <tr>
                                                <td>{{$row}}</td>
                                                <td>{{$letter['title']}}</td>
                                                <td>{{$letter['jalali_date']}}</td>
                                                <td>
                                                    @if($letter['sent'] == 0)
                                                        <span class="badge badge-danger">در انتظار تایید نهایی</span>
                                                    @else
                                                        <span class="badge badge-success">ارسال شده</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{route('maliManager_letter_preview', ['letter_id' => $letter['id']])}}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php $row++ @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12">
                                        <p class="alert alert-danger">نامه ای یافت نشد</p>
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
