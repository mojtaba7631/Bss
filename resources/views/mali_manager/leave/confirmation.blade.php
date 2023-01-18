@extends('mali_manager.layout.mali_layout')
@section('title',"لیست تایید مرخصی ها")
@section('css')
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>لیست تایید مرخصی ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#">نما</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    لیست تایید مرخصی ها
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
                                @if(!empty($leaves->all()))
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing8 text-center">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>نوع مرخصی</th>
                                                <th>نام کاربر</th>
                                                <th>از روز - ساعت</th>
                                                <th>تا روز - ساعت</th>
                                                <th>وضعیت مرخصی</th>
                                                <th>جواب مرخصی</th>
                                            </tr>
                                            </thead>
                                            @if(!$searched)
                                                @php $row = (($leaves->currentPage() - 1) * $leaves->perPage() ) + 1; @endphp
                                            @else
                                                @php $row = 1; @endphp
                                            @endif
                                            <tbody>
                                            @foreach($leaves as $leave)
                                                <tr>
                                                    <td>
                                                        {{convertToPersianNumber($row)}}
                                                    </td>
                                                    <td>
                                                        @if($leave->type == 1)
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
                                                        {{$leave['leave_user_info']['name'] . ' ' . $leave['leave_user_info']['family']}}
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{$leave['start_day'] .' ' . ' ساعت - ' . $leave->start_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{$leave['end_day'] .' ' . ' ساعت - ' . $leave->end_hour}}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        @if($leave->confirmation == 1)
                                                            <p class="badge badge-success">
                                                                تایید شده
                                                            </p>
                                                        @else
                                                            <p class="badge badge-danger">
                                                                تایید نشده
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$leave->disapproval_reason}}
                                                    </td>
                                                </tr>
                                                @php $row++ @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @if(!$searched)
                                            {{$leaves->links()}}
                                        @endif
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="alert alert-danger mb-0">
                                                مرخصی ای یافت نشد
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
