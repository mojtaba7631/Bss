@extends('head_discourse.layout.head_discourse_layout')
@section('title',"لیست درخواست های ماموریت")
@section('css')
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>لیست درخواست های ماموریت</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">نما</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    لیست درخواست های ماموریت
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
                                @if(!empty($missions->all()))
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing8 text-center">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>نوع ماموریت</th>
                                                <th>از روز - ساعت</th>
                                                <th>تا روز - ساعت</th>
                                                <th>وضعیت ماموریت</th>
                                                <th>جواب ماموریت</th>
                                            </tr>
                                            </thead>
                                            @if(!$searched)
                                                @php $row = (($missions->currentPage() - 1) * $missions->perPage() ) + 1; @endphp
                                            @else
                                                @php $row = 1; @endphp
                                            @endif
                                            <tbody>
                                            @foreach($missions as $mission)
                                                <tr>
                                                    <td>
                                                        {{convertToPersianNumber($row)}}
                                                    </td>
                                                    <td>
                                                        @if($mission->type == 1)
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
                                                        <p>
                                                            {{$mission->start_day .' ' . ' ساعت - ' . $mission->start_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{$mission->end_day .' ' . ' ساعت - ' . $mission->end_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <span class="{{$mission['status']['status_css']}}">
                                                        {{$mission['status']['title']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{$mission->disapproval_reason}}
                                                    </td>
                                                </tr>
                                                @php $row++ @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @if(!$searched)
                                            {{$missions->links()}}
                                        @endif
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="alert alert-danger mb-0">
                                                ماموریت ای یافت نشد
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
@endsection
@section('js')
@endsection
