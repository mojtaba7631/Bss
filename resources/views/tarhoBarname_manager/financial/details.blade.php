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
            cursor: pointer;
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

        function payment_order(phase_id, tag) {
            var payment_modal = $('#payment_modal');
            payment_modal.find('#phase_id').val(phase_id);
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

        function removeComma(Number) {
            return Number.replace(/,/g, '');
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="tab-content mt-0">
                            <div class="tab-pane show active" id="Users">
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
                                        @foreach($phases as $phase)
                                            <tr>
                                                <td>
                                                    @if($phase->phase_number == 0)
                                                        پیش پرداخت
                                                    @else
                                                        {{$phase->phase_number}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$phase->comments == "" ? '----' : $phase->comments}}
                                                </td>

                                                <td>
                                                    {{convertToPersianNumber(number_format($phase->cost))}}
                                                    <input type="hidden" class="cost" value="{{$phase->cost}}">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{@number_format($phase->cost)}}"
                                                           name="amount_payable"
                                                           oninput="setRemaining(this)"
                                                           class="form-control bg-white text-dark amount_payable">
                                                </td>
                                                <td>
                                                    <input type="text" value="0"
                                                           name="remaining"
                                                           class="form-control bg-white text-dark remaining">
                                                </td>
                                                <td>
                                                    <a onclick="payment_order('{{$phase->id}}', this)"
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
                <form action="{{route('tarhoBarname_Payment_order')}}" method="post" class="modal-footer">
                    @csrf
                    <input type="hidden" id="phase_id" name="phase_id">
                    <input type="hidden" id="m_amount_payable" name="m_amount_payable">
                    <button type="submit" class="btn btn-success">بله مطمئنم</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر</button>
                </form>

            </div>
        </div>
    </div>
@endsection
