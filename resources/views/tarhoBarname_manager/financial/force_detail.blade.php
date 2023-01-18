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
            cursor: pointer;
        }

        .not_verify_a i {
            font-size: 30px !important;
        }
    </style>
@endsection
@section('js')
    <script>

        function removeComma(Number) {
            return Number.replace(/,/g, '');
        }

        // function payment_order(payment_id) {
        //     var payment_modal = $('#payment_modal');
        //     payment_modal.find('#payment_id_modal').val(payment_id);
        //     payment_modal.modal('show');
        // }
        function setRemaining(tag) {
            var remaining = $(tag).parents('tr').find(".remaining");
            var cost_tag = $(tag).parents('tr').find(".cost");
            var total = parseInt(removeComma(cost_tag.val()));
            var value = parseInt(removeComma($(tag).val()));

            if (total >= value) {
                remaining.val(putComma(total - value));
                $(tag).val(putComma(value));
            } else {
                $(tag).val(putComma(total));
                remaining.val(0);
            }
        }

        function payment_order(payment_id, tag) {
            var payment_modal = $('#payment_modal');
            payment_modal.find('#payment_id_modal').val(payment_id);
            payment_modal.find('#m_amount_payable').val(removeComma($(tag).parents('tr').find('.amount_payable').val()));
            payment_modal.modal('show');
        }

        function putComma(Number) {
            Number += '';
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            Number = Number.replace(',', '');
            x = Number.split('.');
            y = x[0];
            z = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(y))
                y = y.replace(rgx, '$1' + ',' + '$2');
            return y + z;
        }

        function setComma(tag) {
            var value = $(tag).val();
            value = removeComma(value);
            value = putComma(value);
            $(tag).val(value);
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
                                        <th>مبلغ ویرایش شده نهایی تایید پرداخت (ریال)</th>
                                        <th>مبلغ باقی مانده (ریال)</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($phases as $phase_info)
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
                                                {{number_format($phase_info->price)}}
                                                <input type="hidden" class="cost" value="{{$phase_info->price}}">
                                            </td>
                                            <td>
                                                <input type="text" value="{{@number_format($phase_info->price)}}"
                                                       name="amount_payable"
                                                       oninput="setRemaining(this)"
                                                       class="form-control bg-white text-dark amount_payable">
                                            </td>

                                            <td>
                                                <input readonly type="text" value="0"
                                                       name="remaining"
                                                       class="form-control bg-white text-dark remaining">
                                            </td>
                                            <td>
                                                <a onclick="payment_order('{{$phase_info->payment_id}}', this)"
                                                   class="verify_a"
                                                   title="دستور واریز">
                                                    <i class="fa fa-credit-card"></i>
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
                <form action="{{route('tarhoBarname_second_force_payment')}}" method="post" class="modal-footer">
                    @csrf
                    <input type="hidden" id="payment_id_modal" value="" name="payment_id">
                    <input type="hidden" id="full_cost" value="" name="full_cost">
                    <input type="hidden" id="m_amount_payable" name="m_amount_payable">
                    <button type="submit" class="btn btn-success">بله مطمئنم</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
                </form>

            </div>
        </div>
    </div>
@endsection
