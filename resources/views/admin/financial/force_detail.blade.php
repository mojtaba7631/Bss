@extends('tarhoBarname_manager.layout.tarhoBarname_layout')
@section('title')
    جزئیات پرداخت
@endsection
@section('css')
    <style>
        .light_version .table tr td, .light_version .table tr th {
            background: none;
        }

        .light_version .table.table-custom tbody tr:nth-child(odd) {
            background: #edfcff !important;
        }

        .light_version .table.table-custom tbody tr {
            box-shadow: 0 0 15px rgba(0, 0, 0, .1);
        }

        .verify_a {
            color: green;
        }

        .verify_a i {
            font-size: 30px !important;
        }

        .not_verify_a i {
            font-size: 30px !important;
        }
    </style>
@endsection
@section('js')
    <script>

        function payment_order(payment_id) {
            var payment_modal = $('#payment_modal');
            payment_modal.find('#payment_id_modal').val(payment_id);
            payment_modal.modal('show');
        }

    </script>
@endsection
@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>جزئیات پرداخت</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">جزئیات
                                    پرداخت {{$project_info->title}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom spacing8 text-center">
                                        <thead>
                                        <tr>
                                            <th>فاز</th>
                                            <th>توضیحات</th>
                                            <th>مبلغ (ریال)</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <td>
                                                @if($phase_info->phase_number == 0)
                                                    پیش پرداخت
                                                @else
                                                    {{$phase_info->phase_number}}
                                                @endif
                                            </td>

                                            <td>
                                                {{$phase_info->comments == "" ? '----' : $phase_info->comments}}
                                            </td>

                                            <td>
                                                {{convertToPersianNumber(number_format($payment_info->price))}}
                                            </td>

                                            <td>
                                                <a onclick="payment_order({{$payment_id}})" class="verify_a"
                                                        title="دستور واریز">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="payment_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">دستور واریز</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    آیا از دستور واریز مطمئن هستید؟
                </div>

                <!-- Modal footer -->
                <form action="{{route('tarhoBarname_force_payment')}}" method="post" class="modal-footer">
                    @csrf
                    <input type="hidden" id="payment_id_modal" name="payment_id">
                    <button type="submit" class="btn btn-success">بله مطمئنم</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
                </form>

            </div>
        </div>
    </div>
@endsection
