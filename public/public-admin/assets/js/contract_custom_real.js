var Errors = {};
var form_data = new FormData();
var wizard_contract = $('#wizard_contract');
var form_phases = $("#form_phases");
var phases_count = 0;
var contract_cost = 0;

function calculate_total_prices() {
    var phase_prices = $(".phase_price");
    for (var i = 0; i < phase_prices.length; i++) {
        calculate_total_prices_each(i);
    }
}

function calculate_total_prices_each(count) {
    let prepayment_review = parseInt(removeComma($("#prepayment_review").val()));
    let contract_cost = parseInt(removeComma($("#contract_cost").val()));
    var phase_prices = $(".phase_price");
    var has_empty = false;
    var tag = $('#cost_' + parseInt(count + 1));

    var this_cost_tag;
    for (var i = 0; i <= count; i++) {
        this_cost_tag = $('#cost_' + parseInt(i + 1));
        if (removeComma(this_cost_tag.val()) !== '') {
            prepayment_review += parseInt(removeComma(this_cost_tag.val()));
        }
        if (parseInt(removeComma(this_cost_tag.val())) === 0 && parseInt(count + 1) === phase_prices.length) {
            has_empty = true;
        }
    }

    var final = contract_cost - prepayment_review;

    var this_val = removeComma($(tag).val());

    if (!has_empty && final !== 0 && parseInt(count + 1) === phase_prices.length) {
        if (this_val === '') {
            $(tag).parents('tr').find('.phase_price_total').val('');
        } else {
            $(tag).parents('tr').find('.phase_price_total').val(0);
            $(tag).val(putComma(final + parseInt(this_val)));
        }
    } else if (final < 0) {
        if (this_val === '') {
            $(tag).parents('tr').find('.phase_price_total').val('');
        } else {
            $(tag).parents('tr').find('.phase_price_total').val(0);
            $(tag).val(putComma(final + parseInt(this_val)));
        }
    } else {
        if (this_val === '') {
            $(tag).parents('tr').find('.phase_price_total').val('');
        } else {
            $(tag).parents('tr').find('.phase_price_total').val(putComma(final));
            if (parseInt(count + 1) === phase_prices.length) {
                $(tag).val(putComma(parseInt(final)));
            } else {
                $(tag).val(putComma(parseInt(this_val)));
            }
        }
    }
}

wizard_contract.steps({
    headerTag: 'h2',
    bodyTag: 'section',
    startIndex: 0,
    transitionEffect: 'slideLeft',
    onInit: function (event, currentIndex) {
        setButtonWavesEffect(event);
        var tag = $('#wizard_contract-p-' + currentIndex);
        var height = tag.find('.my_content_tab').height();
        tag.parent().css('min-height', Math.ceil(height + 200) + 'px');
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
        setButtonWavesEffect(event);
        var tag = $('#wizard_contract-p-' + currentIndex);
        var height = tag.find('.my_content_tab').height();
        tag.parent().css('min-height', Math.ceil(height + 200) + 'px');

        if (currentIndex === 2) {
            contract_cost = removeComma($("#contract_cost").val());
            if ($("#prepayment_checkbox").is(':checked')) {
                $("#prepayment_review").val(putComma(Math.ceil(contract_cost * .3)));
                $("#prepayment_review_total").val(putComma(Math.ceil(contract_cost - (contract_cost * .3))));
                $("#prepayment_review_total_span").text(putComma(contract_cost));
            } else {
                $("#prepayment_review").val(0);
                $("#prepayment_review_total").val(putComma(contract_cost));
                $("#prepayment_review_total_span").text(putComma(contract_cost));
            }
        }

        if (currentIndex === 2) {
            form_phases = $("#form_phases");
            final_table = $('#final_table');
            phases = form_phases.find('._phase_');

            final_table.find('tbody').find('tr:not(:first-child)').remove();

            var ph_tr = '';
            for (var ph = 1; ph <= phases.length; ph++) {
                var cost = 0;
                var day_count = 0;

                // var cost = phases.eq(parseInt(ph) - 1).attr('data-cost');
                // var day_count = phases.eq(parseInt(ph) - 1).attr('data-day-count');
                //
                // if (!day_count) {
                //     day_count = 0;
                // }
                //
                // if (!cost) {
                //     cost = 0;
                // } else {
                //     cost = putComma(cost);
                // }

                ph_tr = '<tr>\n' +
                    '        <td>فاز ' + ph + '</td>' +
                    '        <td>' +
                    '            <input value="' + day_count + '" id="day_count_' + ph + '" type="number" class="form-control">' +
                    '        </td>' +
                    '        <td>' +
                    '            <input value="' + cost + '" oninput="calculate_total_prices(this, ' + ph + ')" id="cost_' + ph + '" type="text" class="form-control phase_price">' +
                    '        </td>' +
                    '        <td>' +
                    '            <input value="0" readonly id="total_cost_' + ph + '" type="text" class="form-control phase_price_total">' +
                    '        </td>' +
                    '    </tr>';

                final_table.find('tbody').append(ph_tr);
            }
        }
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        Errors = {};
        $('.error_validate').text("");
        if (newIndex > currentIndex) {
            if (currentIndex === 0) {
                var project_title = $('#project_title');
                if (!project_title.val()) {
                    Errors.project_title = 'عنوان پروژه الزامی است';
                } else {
                    form_data.delete('project_title');
                    form_data.append('project_title', project_title.val())
                }

                var contract_cost = $('#contract_cost');
                if (!contract_cost.val()) {
                    Errors.contract_cost = 'قیمت پروژه الزامی است';
                } else {
                    form_data.delete('contract_cost');
                    form_data.append('contract_cost', removeComma(contract_cost.val()))
                }

                var start_date = $('#start_date');
                if (!start_date.val()) {
                    Errors.start_date = 'تاریخ شروع قرارداد الزامی است';
                } else {
                    form_data.delete('start_date');
                    form_data.append('start_date', start_date.val())
                }

                var total_day_count = $('#total_day_count');
                if (!total_day_count.val()) {
                    Errors.total_day_count = 'تعداد روزهای انجام کار الزامی است';
                } else {
                    form_data.delete('total_day_count');
                    form_data.append('total_day_count', total_day_count.val())
                }

                var comment = $('#comment');
                if (!comment.val()) {
                    Errors.comment = 'شرح خدمات الزامی است';
                } else {
                    form_data.delete('comment');
                    form_data.append('comment', comment.val())
                }

                var required_outputs = $('#required_outputs');
                if (!required_outputs.val()) {
                    Errors.required_outputs = 'خروجی های مورد انتظار الزامی است';
                } else {
                    form_data.delete('required_outputs');
                    form_data.append('required_outputs', required_outputs.val())
                }

                if (Object.keys(Errors).length > 0) {
                    $.each(Errors, function (i, val) {
                        $('#' + i + '_errors').text(val);
                    });

                    return false;
                } else {
                    return true;
                }
            } else if (currentIndex === 1) {

                setTimeout(function () {
                    phases_count = form_phases.find('._phase_').length;
                }, 500);

                for (var p = 1; p <= phases_count; p++) {
                    var phase = $('#phase_' + p);
                    if (!phase.val()) {
                        Errors['phase_' + p] = 'فاز ' + p + ' الزامی است';
                    } else {
                        form_data.delete('phase_' + p);
                        form_data.append('phase_' + p, phase.val())
                    }
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
        } else {
            return true;
        }
    },
    onFinishing: function (event, currentIndex) {
        Errors = {};
        $('.error_validate').text("");
        if (currentIndex === 2) {
            var phase_prices = $(".phase_price");
            var has_empty = false;
            var this_cost_tag;
            for (var i = 0; i < phase_prices.length; i++) {
                this_cost_tag = $('#cost_' + parseInt(i + 1));
                if (parseInt(removeComma(this_cost_tag.val())) === 0) {
                    has_empty = true;
                }
            }

            var alert_modal = $("#alert_modal");
            if (has_empty) {
                alert_modal.find('.modal-body').html('<p class="alert alert-danger">شما حداقل یک فاز با مبلغ 0 دارید، لطفا پر نمایید.</p>');
                alert_modal.modal('show');
                return false;
            }

            let contract_cost = parseInt(removeComma($("#contract_cost").val()));
            let total_day_count = parseInt($("#total_day_count").val());
            var last_phase_price = $('#cost_' + phase_prices.length);
            var ten_percent = putComma(Math.floor(contract_cost * 10 / 100));
            if (parseInt(removeComma(last_phase_price.val())) < ten_percent) {
                alert_modal.find('.modal-body').html('<p class="alert alert-danger">مبلغ آخرین فاز نمی تواند کمتر از 10% پروژه معادل ' + ten_percent + ' تومان باشد..</p>');
                alert_modal.modal('show');
                return false;
            }

            phases_count = form_phases.find('._phase_').length;

            var phases = [];
            var sum_days = 0;
            for (var p = 1; p <= phases_count; p++) {
                var phase = $('#phase_' + p);
                if (!phase.val()) {
                    Errors['phase_' + p] = 'فاز ' + p + ' الزامی است';
                    alert_modal.find('.modal-body').html('<p class="alert alert-danger">توضیحات فاز ' + p + ' الزامی است</p>');
                    alert_modal.modal('show');
                    return false;
                }

                var day_count = $('#day_count_' + p);
                if (!day_count.val() || parseInt(day_count.val()) <= 0) {
                    alert_modal.find('.modal-body').html('<p class="alert alert-danger">تعداد روز فاز ' + p + ' الزامی است</p>');
                    alert_modal.modal('show');
                    return false;
                }

                sum_days += parseInt(day_count.val());

                var cost = $('#cost_' + p);
                if (!cost.val()) {
                    alert_modal.find('.modal-body').html('<p class="alert alert-danger">قیمت فاز ' + p + ' الزامی است</p>');
                    alert_modal.modal('show');
                    return false;
                }

                var obj = {
                    'description': phase.val(),
                    'day_count': day_count.val(),
                    'price': removeComma(cost.val()),
                };

                phases.push(obj);
            }

            if (total_day_count !== sum_days) {
                alert_modal.find('.modal-body').html('<p class="alert alert-danger">تعداد کل روزهای پروژه با جمع روزهای فاز ها همخوانی ندارد</p>');
                alert_modal.modal('show');
                return false;
            }

            form_data.append('phases', JSON.stringify(phases));
            form_data.append('project_id', $('#project_id').val());
            form_data.append('_token', $('#_token_value_').val());

            var prepayment_checkbox = $('#prepayment_checkbox');
            var prepayment_value = 0;
            if (prepayment_checkbox.is(':checked')) {
                prepayment_value = removeComma($("#prepayment_review").val());
            }

            form_data.delete('prepayment');
            form_data.append('prepayment', prepayment_value);

            var final_ajax_result = $('.final_ajax_result');
            var my_loader = $('#my_loader');
            my_loader.fadeIn(500);
            var res_p = "";
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

                    console.log(errorMsg);

                    final_ajax_result.find('p').remove();

                    if (error) {
                        $.each(errorMsg, function (i, val) {
                            res_p = '<p class="alert alert-danger mb-4">' + val + '</p>';
                            final_ajax_result.append(res_p);
                        });

                    } else {
                        res_p = '<p class="alert alert-success mb-4">' + errorMsg + ' ...</p>';
                        final_ajax_result.append(res_p);
                        setTimeout(function () {
                            window.location.href = '/realUser-access/dashboard';
                        }, 2000)
                    }

                    my_loader.fadeOut(500);

                    var tag = $('#wizard_contract-p-' + currentIndex);
                    var height = tag.find('.my_content_tab').height();
                    tag.parent().css('min-height', Math.ceil(height + 200) + 'px');
                }
            });
        }
    },
    onFinished: function (event, currentIndex) {

    }
});

function checkNationalCode(code) {
    var L = code.length;

    if (L < 8 || parseInt(code, 10) == 0) return false;
    code = ('0000' + code).substr(L + 4 - 10);
    if (parseInt(code.substr(3, 6), 10) == 0) return false;
    var c = parseInt(code.substr(9, 1), 10);
    var s = 0;
    for (var i = 0; i < 9; i++)
        s += parseInt(code.substr(i, 1), 10) * (10 - i);
    s = s % 11;
    return (s < 2 && c == s) || (s >= 2 && c == (11 - s));
    return true;
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


