@extends('realUser.layout.real_layout')
@section('title',"ثبت قرارداد")

@section('css')
    <link rel="stylesheet" href="{{asset('public-admin/assets/css/persian-datepicker.min.css')}}">
    <style>
        .bg-white * {
            color: #191f28;
        }

        .error_validate {
            font-size: 9pt;
            display: inline-block;
            margin-right: 3px;
        }

        .contract_levels {
            margin: 0;
            padding: 0;
            width: 100%;
            text-align: center;
        }

        .contract_levels li {
            width: 100%;
            height: 20px;
            background: #e2e2e2;
            list-style: none;
            position: relative;
        }

        .contract_levels li span {
            position: absolute;
            right: 0;
            left: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background: #e2e2e2;
            font-size: 20pt;
            line-height: 75px;
            color: #c7c7c7;
        }

        .contract_levels li.active_level, .contract_levels li.active_level span {
            background: #17C2D7;
            color: #fff;
            font-weight: bold;
        }

        .contract_level_body {
            padding: 50px 30px;
            display: none;
        }

        #contract_step_1 {
            display: block;
        }

        .zi-2 {
            z-index: 2;
        }

        .zi-1 {
            z-index: 1;
        }

        .has_main_border {
            border: 2px solid #17C2D7;
            border-radius: 20px;
            padding: 25px;
        }

        .has_main_border h4 {
            font-size: 12pt;
            font-weight: bold;
        }

        .has_main_border p {
            padding-right: 5px;
            margin: 0;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .range-from-example, .range-to-example {
            position: absolute;
            right: 15px;
            top: 54px;
            display: none;
        }

        .date_calender {
            cursor: pointer;
            margin-top: 10px;
        }

        #start_date_show_text,
        #end_date_show_text {
            cursor: default;
        }

        .range-from-example, .range-to-example {
            z-index: 99999;
        }

        .datepicker-plot-area .datepicker-day-view .month-grid-box .header .header-row-cell,
        .datepicker-plot-area .datepicker-day-view .table-days td span,
        .datepicker-plot-area .datepicker-navigator .pwt-btn-switch,
        .datepicker-plot-area .datepicker-year-view .year-item, .datepicker-plot-area .datepicker-month-view .month-item {
            font-family: "IRANSansDN", sans-serif;
        }

        .active_level_btn {
            padding: 7px 21px;
            border-radius: 30px;
            background: #17C2D7;
            border: none;
            color: #fff;
        }

        .active_level_btn i {
            color: #fff;
            margin: 2px 5px;
            font-size: 12pt;
        }

        .mt-5px {
            margin-top: 5px;
        }

        .w-88px {
            width: 88px;
        }

        #show_important_statistics.fix_top {
            position: fixed;
            top: 62px;
            right: 240px;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, .2);
            z-index: 9999;
        }

        .light_version .table tr td, .light_version .table tr th {
            border: 1px solid #17C2D7;
        }

        .animate_loader {
            animation: mymove 2s infinite alternate;
        }

        @keyframes mymove {
            0% {
                transform: rotate(0)
            }
            100% {
                transform: rotate(360deg)
            }
        }

        #my_loader {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .8);
        }

        #my_loader img {
            position: absolute;
            right: 0;
            left: 0;
            bottom: 0;
            top: 0;
            margin: auto;
            width: 150px;
            height: auto;
        }

        #price_characters {
            font-size: 8pt;
            margin: 0 0 7px 0;
            position: absolute;
            bottom: -23px;
            right: 23px;
            color: #17C2D7;
        }

        @media (max-width: 767px) {
            #price_characters {
                bottom: -23px;
                right: 12px;
            }
        }
    </style>
@endsection

@section('js')
    <script src="{{asset('public-admin/assets/js/persian-date.min.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/persian-datepicker.min.js')}}"></script>
    <script src="{{asset('public-admin/assets/js/number_to_persian.js')}}"></script>

    <script>
        $(document).ready(function () {
            // setTimeout(function () {
            //     console.clear();
            // }, 500);
            // setTimeout(function () {
            //     console.clear();
            // }, 2000);
            // setTimeout(function () {
            //     console.clear();
            // }, 2500);
            // setTimeout(function () {
            //     console.clear();
            // }, 3000);
        });

        var show_important_statistics = $("#show_important_statistics");
        var last_step_preview = $('#last_step_preview');

        $(window).scroll(function () {
            var scrollTop = $(window).scrollTop();
            var window_width = $(window).width();
            if (scrollTop > 350 && window_width > 996) {
                show_important_statistics.addClass('fix_top');
                show_important_statistics.css('width', parseInt(window_width - 226) + 'px');
            } else {
                show_important_statistics.removeClass('fix_top');
                show_important_statistics.css('width', 'auto');
            }
        });

        $(window).resize(function () {
            if ($(window).width() < 997) {
                show_important_statistics.removeClass('fix_top');
            }
        });

        var Errors = {};
        var form_data = new FormData();
        var total_price = 0;
        var prepayment = 0;
        var reminding = 0;
        var total_days = 0;
        var reminding_days = 0;

        var alert_modal = $("#alert_modal");
        var price_characters = $("#price_characters");
        var total_day_count = $("#total_day_count");
        var prepayment_section_step2 = $(".prepayment_section_step2");
        var show_prepayment_price_p = $(".show_prepayment_price_p");
        var total_price_inp = $("#total_price_inp");
        var show_total_price_p = $(".show_total_price_p");
        var show_total_days_p = $(".show_total_days_p");
        var show_reminder_price_p = $(".show_reminder_price_p");
        var prepayment_inp = $(".prepayment_inp");
        var to, from;
        var from_selected_date = [];
        var to_selected_date = [];
        var total_days_count_show = $("#total_days_count_show");
        var show_reminding_days_p = $(".show_reminding_days_p");
        var form_date_picker_jq = $("#form_date_picker");
        var to_date_picker_jq = $("#to_date_picker");
        var start_date_show_text = $('#start_date_show_text');
        var end_date_show_text = $('#end_date_show_text');
        var start_date_inp = $('#start_date_inp');
        var end_date_inp = $('#end_date_inp');
        var prepayment_checkbox = $('#prepayment_checkbox');
        var has_prepayment = 1;
        to = $(".range-to-example").persianDatepicker({
            inline: true,
            observer: true,
            altField: '.range-to-example-alt',
            format: "YYYY/MM/DD",
            navigator: {
                text: {
                    btnNextText: '+',
                    btnPrevText: '-',
                }
            },
            initialValue: false,
            autoClose: true,
            onSelect: function (unix) {
                to.touched = true;
                if (from && from.options && from.options.maxDate != unix) {
                    var cachedValue = from.getState().selected.unixDate;
                    end_date_show_text.text(to.model.inputElement.value);
                    end_date_inp.val(to.model.inputElement.value);
                    from.options = {maxDate: unix};
                    if (from.touched) {
                        from.setDate(cachedValue);
                    }
                }
                to_selected_date = to.getState().selected.dateObject.State.gregorian;
                from_selected_date = from.getState().selected.dateObject.State.gregorian;
                $("#end_date_calender").trigger('click');
                calculate_difference_dates();
            }
        });
        from = $(".range-from-example").persianDatepicker({
            inline: true,
            observer: true,
            altField: '.range-from-example-alt',
            format: "YYYY/MM/DD",
            navigator: {
                text: {
                    btnNextText: '+',
                    btnPrevText: '-',
                }
            },
            autoClose: true,
            initialValue: false,
            onSelect: function (unix) {
                from.touched = true;
                if (to && to.options && to.options.minDate != unix) {
                    var cachedValue = to.getState().selected.unixDate;
                    start_date_show_text.text(from.model.inputElement.value);
                    start_date_inp.val(from.model.inputElement.value);
                    to.options = {minDate: unix};
                    if (to.touched) {
                        to.setDate(cachedValue);
                    }
                }
                from_selected_date = from.getState().selected.dateObject.State.gregorian;
                to_selected_date = to.getState().selected.dateObject.State.gregorian;
                $("#start_date_calender").trigger('click');
                calculate_difference_dates();
            }
        });

        function showDatePicker(id, tag) {
            if ($(id).attr('data-show') === '1') {
                $(id).attr('data-show', '0');
                $(tag).removeAttr('class');
                $(tag).addClass('fa fa-calendar date_calender');
                $(id).fadeOut();
            } else {
                $(tag).removeAttr('class');
                $(tag).addClass('fa fa-times text-danger date_calender');
                $(id).attr('data-show', '1');
                $(id).fadeIn(250);
            }

            return true;
        }

        function calculate_difference_dates() {
            var date1 = new Date(from_selected_date['month'] + "/" + from_selected_date['day'] + "/" + from_selected_date['year']);
            var date2 = new Date(to_selected_date['month'] + "/" + to_selected_date['day'] + "/" + to_selected_date['year']);

            // To calculate the time difference of two dates
            var Difference_In_Time = date2.getTime() - date1.getTime();

            // To calculate the no. of days between two dates
            var Difference_In_Days = Math.ceil(Difference_In_Time / (1000 * 3600 * 24));

            if (!isNaN(Difference_In_Days) && Difference_In_Days !== undefined && Difference_In_Days > 0) {
                total_days_count_show.text(Difference_In_Days + " روز");
                show_total_days_p.text(Difference_In_Days + " روز");
                total_day_count.val(Difference_In_Days);
                total_days = Difference_In_Days;
                reminding_days = Difference_In_Days;
                show_reminding_days_p.text(Difference_In_Days);
            } else {
                total_days_count_show.text(0 + " روز");
                show_total_days_p.text(0);
                total_day_count.val(0);
                total_days = 0;
                reminding_days = 0;
                show_reminding_days_p.text(Difference_In_Days);
            }

            return true;
        }

        function changeStep(num, from_last = false) {
            var phases;
            var contract_levels = $('.contract_levels');
            contract_levels.find('li').removeClass('active_level');

            var phase_section = $(".the_phase");
            var phase_count = phase_section.length;

            if (num === 1) {
                for (n = 0; n < num; n++) {
                    contract_levels.find('li:nth-child(' + parseInt(n + 1) + ')').addClass('active_level');
                }
            }

            if (num > 1) {
                show_total_price_p.text(total_price_inp.val());
            }

            if (num === 2) {
                if (!check_validation(num)) {
                    $('html,body').animate({
                        scrollTop: $("#contract_levels_row").offset().top - 100,
                    }, 1100);

                    return false;
                }

                for (n = 0; n < num; n++) {
                    contract_levels.find('li:nth-child(' + parseInt(n + 1) + ')').addClass('active_level');
                }

                calculate_difference_dates();

                total_price = parseInt(removeComma(total_price_inp.val()));
                if (has_prepayment === 0) {
                    prepayment_section_step2.hide();
                    reminding = total_price;
                    show_reminder_price_p.text(putComma(reminding));
                    prepayment_inp.val(0);
                } else {
                    prepayment_section_step2.show();
                    prepayment = Math.floor(total_price * .3);
                    reminding = total_price - prepayment;
                    show_reminder_price_p.text(putComma(reminding));
                    prepayment_inp.val(putComma(prepayment));
                    prepayment_inp.attr('data-max', parseInt(prepayment));
                }

                if (!from_last) {
                    if (parseInt(phase_count) > 1) {
                        for (var phase_box = 1; phase_box < phase_count; phase_box++) {
                            phase_section.eq(phase_box).remove();
                        }

                        var action_btn = '<div class="col-12 col-md-4 mt-4 pt-4">' +
                            '<button onclick="add_new_phase(this)" class="btn btn-success mt-4px">' +
                            '<i class="fa fa-plus text-white"></i>' +
                            '</button>' +
                            '</div>';

                        phase_section.eq(0).find('.phase_section_main_row').append(action_btn);
                        phase_section.eq(0).find('textarea').prop('disabled', false);
                        phase_section.eq(0).find('input').prop('disabled', false);
                        prepayment_inp.prop('disabled', false);
                    }
                }

                if (from_last) {
                    check_phases();
                }
            }

            if (num === 3) {
                phases = $(".the_phase");
                var last_phase = phases.eq(phases.length - 1);

                for (n = 0; n < parseInt(num - 1); n++) {
                    contract_levels.find('li:nth-child(' + parseInt(n + 1) + ')').addClass('active_level');
                }

                var phase_description = last_phase.find('.phase_description');
                if (!phase_description.val()) {
                    alert_modal.find('p').remove();
                    alert_modal.find('.modal-body').append('<p class="alert alert-danger">توضیحات فاز الزامی است</p>');
                    alert_modal.modal('show');
                    return false;
                }

                var phase_day_count = last_phase.find('.phase_day_count');
                if (!phase_day_count.val()) {
                    alert_modal.find('p').remove();
                    alert_modal.find('.modal-body').append('<p class="alert alert-danger">تعداد روز فاز الزامی است</p>');
                    alert_modal.modal('show');
                    return false;
                }

                var phase_price = last_phase.find('.phase_price');
                if (!phase_price.val()) {
                    alert_modal.find('p').remove();
                    alert_modal.find('.modal-body').append('<p class="alert alert-danger">مبلغ فاز الزامی است</p>');
                    alert_modal.modal('show');
                    return false;
                }

                var result = check_phases();
                if (result[1] !== 0) {
                    err = '<p class="alert alert-danger mb-0">باقیمانده کل مبالغ وارد شده باید 0 باشد.</p>';
                    alert_modal.find('.modal-body').find('p').remove();
                    alert_modal.find('.modal-body').append(err);
                    alert_modal.modal('show');
                    return false;
                }

                if (result[2] !== 0) {
                    err = '<p class="alert alert-danger mb-0">باقیمانده کل تعداد روز وارد شده باید 0 باشد.</p>';
                    alert_modal.find('.modal-body').find('p').remove();
                    alert_modal.find('.modal-body').append(err);
                    alert_modal.modal('show');
                    return false;
                }

                var tr;
                last_step_preview.find('tbody').find('tr').remove();

                if (has_prepayment === 1) {
                    tr = '<tr>' +
                        '<th colspan="2" class="text-left">پیش پرداخت</th>' +
                        '<th>' + prepayment_inp.val() + '</th>' +
                        '<th rowspan="' + parseInt(phases.length + 1) + '">' + putComma(total_price) + '</th>' +
                        '</tr>';

                    last_step_preview.find('tbody').append(tr);
                }

                for (n = 0; n < num; n++) {
                    contract_levels.find('li:nth-child(' + parseInt(n + 1) + ')').addClass('active_level');
                }

                for (var s = 0; s < phases.length; s++) {
                    phase_day_count = phases.eq(s).find('.phase_day_count').val();
                    phase_price = phases.eq(s).find('.phase_price').val();

                    if (s === 0 && has_prepayment === 0) {
                        tr = '<tr>' +
                            '<th>' + parseInt(s + 1) + '</th>' +
                            '<th>' + phase_day_count + '</th>' +
                            '<th>' + putComma(phase_price) + '</th>' +
                            '<th rowspan="' + phases.length + '">' + putComma(total_price) + '</th>' +
                            '</tr>';
                    } else {
                        tr = '<tr>' +
                            '<th>' + parseInt(s + 1) + '</th>' +
                            '<th>' + phase_day_count + '</th>' +
                            '<th>' + putComma(phase_price) + '</th>' +
                            '</tr>';
                    }

                    last_step_preview.find('tbody').append(tr);
                }
            }

            if (num === 4) {
                phases = $(".the_phase");
                var obj;
                var final_phases = [];

                for (var h = 0; h < phases.length; h++) {

                    obj = {
                        'description': phases.eq(h).find('.phase_description').val(),
                        'day_count': removeComma(phases.eq(h).find('.phase_day_count').val()),
                        'price': removeComma(phases.eq(h).find('.phase_price').val()),
                    };

                    final_phases.push(obj);
                }

                form_data.delete('prepayment');
                form_data.append('prepayment', parseInt(removeComma(prepayment_inp.val())));
                form_data.append('phases', JSON.stringify(final_phases));
                form_data.append('project_id', $('#project_id').val());
                form_data.append('_token', $('#csrf_token_inp').val());

                var final_ajax_result = $('.final_ajax_result');
                var my_loader = $('#my_loader');
                my_loader.fadeIn(500);
                var res_p;
                var url = $('#route_inp').val();
                $.ajax({
                    url: url,
                    data: form_data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    method: "post",
                    success: function (res) {
                        var error = res['error'];
                        var errorMsg = res['errorMsg'];

                        final_ajax_result.find('p').remove();

                        if (error) {
                            $.each(errorMsg, function (i, val) {
                                res_p = '<p class="alert alert-danger mb-4 mt-4">' + val + '</p>';
                                final_ajax_result.append(res_p);
                            });

                            finish_btn.prop('disabled', false);

                        } else {
                            res_p = '<p class="alert alert-success mb-4">' + errorMsg + ' ...</p>';
                            final_ajax_result.append(res_p);
                            setTimeout(function () {
                                window.location.href = $('#return_route_inp').val();
                            }, 2000)
                        }

                        my_loader.fadeOut(500);
                        return true;
                    }
                });
            }

            if (num < 4) {
                $('.contract_level_body').fadeOut(0);
                $('#contract_step_' + num).fadeIn(150);
                return true;
            }

            return true;
        }

        function check_validation(num) {
            Errors = {};
            $('.error_validate').text("");

            if (num === 1) {
                return true;
            }

            if (num === 2) {
                if (!total_price_inp.val()) {
                    Errors.contract_cost = 'مبلغ پروژه الزامی است';
                } else {
                    form_data.delete('contract_cost');
                    form_data.append('contract_cost', removeComma(total_price_inp.val()));
                }

                if (prepayment_checkbox.is(':checked')) {
                    has_prepayment = 1;
                } else {
                    has_prepayment = 0;
                }

                if (!start_date_inp.val()) {
                    Errors.start_date = 'تاریخ شروع قرارداد الزامی است';
                } else {
                    form_data.delete('start_date');
                    form_data.append('start_date', start_date_inp.val());
                }

                if (!end_date_inp.val()) {
                    Errors.end_date = 'تاریخ پایان قرارداد الزامی است';
                }

                if (!total_day_count.val()) {
                    Errors.total_day_count = 'تعداد روزهای انجام کار الزامی است';
                } else {
                    form_data.delete('total_day_count');
                    form_data.append('total_day_count', total_day_count.val());
                }

                var comment = $('#comment');
                if (!comment.val()) {
                    Errors.comment = 'شرح خدمات الزامی است';
                } else {
                    form_data.delete('comment');
                    form_data.append('comment', comment.val());
                }

                var required_outputs = $('#required_outputs');
                if (!required_outputs.val()) {
                    Errors.required_outputs = 'خروجی های مورد انتظار الزامی است';
                } else {
                    form_data.delete('required_outputs');
                    form_data.append('required_outputs', required_outputs.val());
                }

                if (Object.keys(Errors).length > 0) {
                    $.each(Errors, function (i, val) {
                        $('#' + i + '_errors').text(val);
                    });
                    return false;
                } else {
                    return true;
                }
            }
        }

        function add_new_phase(tag) {
            if (reminding === 0) {
                alert_modal.find('p').remove();
                alert_modal.find('.modal-body').append('<p class="alert alert-danger">سقف مبلغ پر شده و قادر به افزودن فاز نیستید.</p>');
                alert_modal.modal('show');
                return false;
            }

            if (reminding_days === 0) {
                alert_modal.find('p').remove();
                alert_modal.find('.modal-body').append('<p class="alert alert-danger">سقف تعداد روز پر شده و قادر به افزودن فاز نیستید.</p>');
                alert_modal.modal('show');
                return false;
            }

            var phase_description = $(tag).parents('.phase_section').find('.phase_description');
            if (!phase_description.val()) {
                alert_modal.find('p').remove();
                alert_modal.find('.modal-body').append('<p class="alert alert-danger">توضیحات فاز الزامی است</p>');
                alert_modal.modal('show');
                return false;
            }

            var phase_day_count = $(tag).parents('.phase_section').find('.phase_day_count');
            if (!phase_day_count.val()) {
                alert_modal.find('p').remove();
                alert_modal.find('.modal-body').append('<p class="alert alert-danger">تعداد روز فاز الزامی است</p>');
                alert_modal.modal('show');
                return false;
            }

            var phase_price = $(tag).parents('.phase_section').find('.phase_price');
            if (!phase_price.val()) {
                alert_modal.find('p').remove();
                alert_modal.find('.modal-body').append('<p class="alert alert-danger">مبلغ فاز الزامی است</p>');
                alert_modal.modal('show');
                return false;
            }

            var phase_section = $(".phase_section");
            var phase_count = phase_section.length;

            var err;
            var check_phases_res = check_phases();

            if (!check_phases_res[0]) {
                err = '<p class="alert alert-danger mb-0">' + check_phases_res[1] + '</p>';
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').append(err);
                alert_modal.modal('show');
                return false;
            }

            if (parseInt(phase_count) < 10) {
                $(tag).parents('.phase_section').find('input').prop('disabled', true);
                $(tag).parents('.phase_section').find('textarea').prop('disabled', true);

                phase_count++;
                var phase = '<div class="has_main_border the_phase phase_section mt-4">' +
                    '<div class="row phase_section_main_row">' +
                    '<div class="col-12">' +
                    '<label>خروجی های فاز ' + phase_count + '</label>' +
                    '<textarea placeholder="توضیحات فاز" rows="5" class="form-control phase_description"></textarea>' +
                    '</div>' +

                    '<div class="col-12 col-md-4 mt-4">' +
                    '<label>تعداد روز</label>' +
                    '<input oninput="setComma(this, true)" type="text" class="form-control phase_day_count">' +
                    '</div>' +

                    '<div class="col-12 col-md-4 mt-4">' +
                    '<label>مبلغ فاز (ریال)</label>' +
                    '<input oninput="setComma(this, true)" type="text" class="form-control phase_price text-left">' +
                    '</div>' +

                    '<div class="col-12 col-md-4 mt-4 pt-4">' +
                    '<button onclick="add_new_phase(this)" class="btn btn-success mt-5px">' +
                    '<i class="fa fa-plus text-white"></i>' +
                    '</button>' +
                    '<button onclick="remove_phase(this)" class="btn btn-danger mt-5px ml-2">' +
                    '<i class="fa fa-times text-white"></i>' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                $(tag).parents('.phase_section').after(phase);
                prepayment_inp.prop('disabled', true);

                phase_section.find('button.btn.btn-success').parent().remove();
            } else {
                err = '<p class="alert alert-danger mb-0">تعداد فاز ها نمی تواند بیش از 10 تا باشد.</p>';
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').append(err);
                alert_modal.modal('show');
            }

            return true;
        }

        function remove_phase(tag) {
            var last_phase_section = $(tag).parents('.has_main_border').prev();

            var phase_section_count = $(".phase_section").length;

            var action_btn = '';
            if (phase_section_count > 2) {
                action_btn = '<div class="col-12 col-md-4 mt-4 pt-4">' +
                    '<button onclick="add_new_phase(this)" class="btn btn-success mt-5px">' +
                    '<i class="fa fa-plus text-white"></i>' +
                    '</button>' +
                    '<button onclick="remove_phase(this)" class="btn btn-danger mt-5px ml-2">' +
                    '<i class="fa fa-times text-white"></i>' +
                    '</button>' +
                    '</div>';
            } else {
                action_btn = '<div class="col-12 col-md-4 mt-4 pt-4">' +
                    '<button onclick="add_new_phase(this)" class="btn btn-success mt-5px">' +
                    '<i class="fa fa-plus text-white"></i>' +
                    '</button>' +
                    '</div>';

                prepayment_inp.prop('disabled', false);
            }

            last_phase_section.find('.phase_section_main_row').append(action_btn);
            last_phase_section.find('textarea').prop('disabled', false);
            last_phase_section.find('input').prop('disabled', false);

            $(tag).parents('.has_main_border').slideUp(350);
            setTimeout(function () {
                $(tag).parents('.has_main_border').remove();
                check_phases();
            }, 400);
            return true;
        }

        function check_phases() {
            var phases = $('.the_phase');
            var err;
            var final_reminding_price = total_price;
            var final_reminding_days = total_days;
            var this_phases_day = 0;
            var last_phases_price = 0;
            var total_phases_price = 0;
            var total_phases_day = 0;
            var last_phases_day = 0;

            if (has_prepayment === 0) {
                prepayment = 0;
                prepayment_section_step2.hide();
            } else {
                prepayment_section_step2.show();
                prepayment = parseInt(removeComma(prepayment_inp.val()));
                final_reminding_price -= prepayment;
                final_reminding_price = Math.abs(final_reminding_price);
                total_phases_price += prepayment;
            }

            if (phases.length > 0) {

                for (var i = 0; i < phases.length; i++) {
                    if (!phases.eq(i).find('.phase_price').val()) {
                        this_phases_price = 0;
                    } else {
                        this_phases_price = parseInt(removeComma(phases.eq(i).find('.phase_price').val()));
                    }
                    final_reminding_price -= this_phases_price;
                    total_phases_price += this_phases_price;
                    if (i === phases.length - 1) {
                        last_phases_price = this_phases_price;
                    }

                    if (!phases.eq(i).find('.phase_day_count').val()) {
                        this_phases_day = 0;
                    } else {
                        this_phases_day = parseInt(removeComma(phases.eq(i).find('.phase_day_count').val()));
                    }
                    final_reminding_days -= this_phases_day;
                    total_phases_day += this_phases_day;
                    if (i === phases.length - 1) {
                        last_phases_day = this_phases_day;
                    }
                }

            } else {
                err = '<p class="alert alert-danger mb-0">خطایی رخ داده است، لطفا مجددا تلاش کنید</p>';
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').append(err);
                alert_modal.modal('show');

                setTimeout(function () {
                    location.reload();
                }, 1500)
                return false;
            }

            if (final_reminding_price < 0) {
                err = '<p class="alert alert-danger mb-0">جمع کل مبلغ وارد شده باید کوچکتر از ' + putComma(total_price) + ' باشد.</p>';
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').append(err);
                alert_modal.modal('show');

                phases.eq(phases.length - 1).find('.phase_price').val(putComma(Math.abs(parseInt(total_price - (total_phases_price - last_phases_price)))));
                show_reminder_price_p.text(putComma(0));
                reminding = 0;
                return [false, 'جمع کل مبلغ وارد شده باید کوچکتر از ' + putComma(total_price) + ' باشد.'];
            } else {
                show_reminder_price_p.text(putComma(final_reminding_price));
                reminding = final_reminding_price;
            }

            if (final_reminding_days < 0) {
                err = '<p class="alert alert-danger mb-0">جمع کل تعداد روز وارد شده باید کوچکتر از ' + putComma(total_days) + ' باشد.</p>';
                alert_modal.find('.modal-body').find('p').remove();
                alert_modal.find('.modal-body').append(err);
                alert_modal.modal('show');

                phases.eq(phases.length - 1).find('.phase_day_count').val(putComma(parseInt(total_days - (total_phases_day - last_phases_day))));
                show_reminding_days_p.text(putComma(0));
                return [false, 'جمع کل تعداد روز وارد شده باید کوچکتر از ' + putComma(total_days) + ' باشد.'];
            } else {
                show_reminding_days_p.text(putComma(final_reminding_days));
            }

            return [true, final_reminding_price, final_reminding_days];
        }

        function type_price_chars() {
            if (total_price_inp.val()) {
                price_characters.text(Num2persian(parseInt(removeComma(total_price_inp.val())) / 10) + ' تومان');
            } else {
                price_characters.text('...');
            }
        }
    </script>
@endsection

@section('content')
    <input id="csrf_token_inp" type="hidden" value="{{csrf_token()}}">
    <input type="hidden" id="project_id" value="{{$project_id}}">
    <input type="hidden" id="route_inp" value="{{route('real_contract_create')}}">
    <input type="hidden" id="return_route_inp" value="{{route('real_project_in_process')}}">
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>ثبت قرارداد</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ثبت قرارداد</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div id="contract_levels_row" class="row position-relative zi-2">
                <div class="col-12">
                    <ul class="contract_levels clearfix row">
                        <li class="col-4 active_level">
                            <span>1</span>
                        </li>

                        <li class="col-4">
                            <span>2</span>
                        </li>

                        <li class="col-4">
                            <span>3</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row position-relative zi-1">
                <div class="col-12">
                    {{------ level 1 ------}}
                    <div id="contract_step_1" class="bg-white shadow contract_level_body mb-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="has_main_border border-0">
                                    <h4 class="d-inline-block">عنوان پروژه: </h4>
                                    <p class="d-inline-block">
                                        {{$project_info['title']}}
                                    </p>
                                </div>

                                <div class="has_main_border">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <label>مبلغ کل پروژه (ریال)</label>
                                            <div id="contract_cost_errors"
                                                 class="error_validate text-danger"></div>
                                            <input id="total_price_inp" oninput="setComma(this, false,false,true)"
                                                   type="text"
                                                   class="form-control text-right">
                                            <p id="price_characters">...</p>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-2 clearfix pt-4 mt-10">
                                            <label class="float-left"> پیش پرداخت دارد؟</label>
                                            <label class="switch float-left ml-2">
                                                <input id="prepayment_checkbox" type="checkbox" checked>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-2">
                                            <label>تاریخ شروع</label>
                                            <div id="start_date_errors"
                                                 class="error_validate text-danger"></div>
                                            <div>
                                                <i onclick="showDatePicker('#form_date_picker', this)"
                                                   id="start_date_calender" class="fa fa-calendar date_calender"></i>
                                                <span id="start_date_show_text">----------------------</span>
                                                <input type="hidden" id="start_date_inp">
                                            </div>
                                            <div data-show="0" id="form_date_picker"
                                                 class="range-from-example my_dp"></div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-2 position-relative">
                                            <label>تاریخ پایان</label>
                                            <div id="end_date_errors"
                                                 class="error_validate text-danger"></div>
                                            <div>
                                                <i onclick="showDatePicker('#to_date_picker', this)"
                                                   id="end_date_calender"
                                                   class="fa fa-calendar date_calender"></i>
                                                <span id="end_date_show_text">----------------------</span>
                                                <input type="hidden" id="end_date_inp">
                                            </div>
                                            <div data-show="0" id="to_date_picker" class="range-to-example my_dp"></div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-2 position-relative">
                                            <label>تعداد کل روزها</label>
                                            <div>
                                                <input id="total_day_count" type="hidden">
                                                <span id="total_days_count_show" class="font-weight-bold">0 روز</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="has_main_border mt-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>شرح خدمات</label>
                                            <div id="comment_errors"
                                                 class="error_validate text-danger"></div>
                                            <textarea id="comment" class="summernote"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="has_main_border mt-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>خروجی مورد انتظار</label>
                                            <div id="required_outputs_errors"
                                                 class="error_validate text-danger"></div>
                                            <textarea id="required_outputs" class="summernote"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button onclick="changeStep(2)" class="active_level_btn ml-2">
                                            مرحله بعد
                                            <i class="fa fa-angle-left"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{------ level 2 ------}}
                    <div id="contract_step_2" class="bg-white shadow contract_level_body mb-5">
                        <div class="row">
                            <div class="col-12">
                                <div id="show_important_statistics" class="row">
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="has_main_border border-0">
                                            <h4 class="d-inline-block">مبلغ کل پروژه (ریال): </h4>
                                            <p class="d-inline-block show_total_price_p"></p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="has_main_border border-0">
                                            <h4 class="d-inline-block">مبلغ باقیمانده (ریال): </h4>
                                            <p class="d-inline-block show_reminder_price_p"></p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="has_main_border border-0">
                                            <h4 class="d-inline-block">تعداد کل روزها: </h4>
                                            <p class="d-inline-block show_total_days_p"></p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="has_main_border border-0">
                                            <h4 class="d-inline-block">تعداد روزهای باقیمانده: </h4>
                                            <p class="d-inline-block show_reminding_days_p"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="has_main_border prepayment_section_step2 mb-4">
                                    <div class="row phase_section_main_row">
                                        <div class="col-12 w-88px">
                                            <label>پیش پرداخت: (ریال) </label>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <input oninput="setComma(this, true, true)" data-max="0" type="text"
                                                   class="form-control prepayment_inp text-left">
                                        </div>
                                    </div>
                                </div>

                                <div class="has_main_border phase_section the_phase">
                                    <div class="row phase_section_main_row">
                                        <div class="col-12">
                                            <label>خروجی های فاز 1</label>
                                            <textarea placeholder="توضیحات فاز" rows="5"
                                                      class="form-control phase_description"></textarea>
                                        </div>

                                        <div class="col-12 col-md-4 mt-4">
                                            <label>تعداد روز</label>
                                            <input oninput="setComma(this, true)" type="text"
                                                   class="form-control phase_day_count">
                                        </div>

                                        <div class="col-12 col-md-4 mt-4">
                                            <label>مبلغ فاز (ریال)</label>
                                            <input oninput="setComma(this, true)" type="text"
                                                   class="form-control phase_price text-left">
                                        </div>

                                        <div class="col-12 col-md-4 mt-4 pt-4">
                                            <button onclick="add_new_phase(this)" class="btn btn-success mt-5px">
                                                <i class="fa fa-plus text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button onclick="changeStep(1)" class="active_level_btn">
                                            <i class="fa fa-angle-right"></i>
                                            مرحله قبل
                                        </button>
                                        <button onclick="changeStep(3)" class="active_level_btn ml-2">
                                            مرحله بعد
                                            <i class="fa fa-angle-left"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{------ level 3 ------}}
                    <div id="contract_step_3" class="bg-white shadow contract_level_body mb-5">
                        <div class="row">
                            <div class="col-12">
                                <div id="my_loader">
                                    <img class="animate_loader" src="{{asset('images/hamyaran_white.png')}}">
                                </div>

                                <div id="show_important_statistics" class="row">
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="has_main_border border-0">
                                            <h4 class="d-inline-block">مبلغ کل پروژه (ریال): </h4>
                                            <p class="d-inline-block show_total_price_p"></p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="has_main_border border-0">
                                            <h4 class="d-inline-block">تعداد کل روزها: </h4>
                                            <p class="d-inline-block show_total_days_p"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="has_main_border mb-4">
                                    <div class="table-responsive phase_section_main_row">
                                        <table id="last_step_preview" class="table table-borderless text-center">
                                            <thead>
                                            <tr>
                                                <th>فاز</th>
                                                <th>تعداد روز</th>
                                                <th>مبلغ (ریال)</th>
                                                <th>جمع کل</th>
                                            </tr>
                                            </thead>

                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button onclick="changeStep(2, true)" class="active_level_btn">
                                            <i class="fa fa-angle-right"></i>
                                            مرحله قبل
                                        </button>
                                        <button onclick="changeStep(4)" class="active_level_btn ml-2">
                                            تایید و ثبت قرارداد
                                            <i class="fa fa-angle-left"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 final_ajax_result mt-4">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="alert_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body"></div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">متوجه شدم</button>
                </div>
            </div>
        </div>
    </div>
@endsection
