var Errors = {};
var form_data = new FormData();
var wizard_contract = $('#wizard_contract');
var form_phases = $("#form_phases");
var phases_count = 0;
var contract_cost = 0;

function calculate_total_prices(tag, num) {
    var phase_prices = $(".phase_price");
    for (var i = 0; i < phase_prices.length; i++) {
        calculate_total_prices_each(i);
    }
}

function calculate_total_prices_each(count) {
    let prepayment_review = parseInt($("#prepayment_review").val());
    let contract_cost = parseInt($("#contract_cost").val());
    var phase_prices = $(".phase_price");
    var has_empty = false;
    var tag = $('#cost_' + parseInt(count + 1));

    var this_cost_tag;
    for (var i = 0; i <= count; i++) {
        this_cost_tag = $('#cost_' + parseInt(i + 1));
        if (this_cost_tag.val() !== '') {
            prepayment_review += parseInt(this_cost_tag.val());
        }
        if (parseInt(this_cost_tag.val() === 0) && parseInt(count + 1) === phase_prices.length) {
            has_empty = true;
        }
    }

    var final = contract_cost - prepayment_review;

    var this_val = $(tag).val();

    if (!has_empty && final !== 0 && parseInt(count + 1) === phase_prices.length) {
        if (this_val === '') {
            $(tag).parents('tr').find('.phase_price_total').val('');
        } else {
            $(tag).parents('tr').find('.phase_price_total').val(0);
            $(tag).val(final + parseInt(this_val));
        }
    } else if (final < 0) {
        if (this_val === '') {
            $(tag).parents('tr').find('.phase_price_total').val('');
        } else {
            $(tag).parents('tr').find('.phase_price_total').val(0);
            $(tag).val(final + parseInt(this_val));
        }
    } else {
        if (this_val === '') {
            $(tag).parents('tr').find('.phase_price_total').val('');
        } else {
            $(tag).parents('tr').find('.phase_price_total').val(final);
            $(tag).val(parseInt(this_val));
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
            contract_cost = $("#contract_cost").val();
            if ($("#prepayment_checkbox").is(':checked')) {
                $("#prepayment_review").val(Math.ceil(contract_cost * .3));
                $("#prepayment_review_total").val(Math.ceil(contract_cost - (contract_cost * .3)));
                $("#prepayment_review_total_span").text(contract_cost);
            } else {
                $("#prepayment_review").val(0);
                $("#prepayment_review_total").val(contract_cost);
                $("#prepayment_review_total_span").text(contract_cost);
            }
        }

        if (currentIndex === 2) {
            form_phases = $("#form_phases");
            final_table = $('#final_table');
            phases_count = form_phases.find('._phase_').length;

            final_table.find('tbody').find('tr:not(:first-child)').remove();

            var ph_tr = '';
            for (var ph = 1; ph <= phases_count; ph++) {
                ph_tr = '<tr>\n' +
                    '        <td>فاز ' + ph + '</td>' +
                    '        <td>' +
                    '            <input id="start_date_' + ph + '" type="text" class="form-control" data-jdp>' +
                    '        </td>' +
                    '        <td>' +
                    '            <input id="end_date_' + ph + '" type="text" class="form-control" data-jdp>' +
                    '        </td>' +
                    '        <td>' +
                    '            <input value="0" oninput="calculate_total_prices(this, ' + ph + ')" id="cost_' + ph + '" type="text" class="form-control phase_price">' +
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
                    form_data.append('contract_cost', contract_cost.val())
                }

                var start_date = $('#start_date');
                if (!start_date.val()) {
                    Errors.start_date = 'تاریخ شروع قرارداد الزامی است';
                } else {
                    form_data.delete('start_date');
                    form_data.append('start_date', start_date.val())
                }

                var end_date = $('#end_date');
                if (!end_date.val()) {
                    Errors.end_date = 'تاریخ پایان قرارداد الزامی است';
                } else {
                    form_data.delete('end_date');
                    form_data.append('end_date', end_date.val())
                }

                var comment = $('#comment');
                if (!comment.val()) {
                    Errors.comment = 'شرح خدمات الزامی است';
                } else {
                    form_data.delete('comment');
                    form_data.append('comment', comment.val())
                }

                var proposal_file = $('#proposal_file');
                if (proposal_file.val()) {
                    form_data.delete('proposal_file');
                    form_data.append('proposal_file', proposal_file[0].files[0])
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

                phases_count = form_phases.find('._phase_').length;

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
            phases_count = form_phases.find('._phase_').length;

            var phases = [];
            for (var p = 1; p <= phases_count; p++) {
                var phase = $('#phase_' + p);
                if (!phase.val()) {
                    Errors['phase_' + p] = 'فاز ' + p + ' الزامی است';
                    alert('توضیحات فاز ' + p + ' الزامی است');
                    return false;
                }

                var start_date = $('#start_date_' + p);
                if (!start_date.val()) {
                    alert('تاریخ شروع فاز ' + p + ' الزامی است');
                    return false;
                }

                var end_date = $('#end_date_' + p);
                if (!end_date.val()) {
                    alert('تاریخ پایان فاز ' + p + ' الزامی است');
                    return false;
                }

                var cost = $('#cost_' + p);
                if (!cost.val()) {
                    alert('قیمت فاز ' + p + ' الزامی است');
                    return false;
                }

                var obj = {
                    'description': phase.val(),
                    'start_date': start_date.val(),
                    'end_date': end_date.val(),
                    'price': cost.val(),
                };

                phases.push(obj);
            }

            form_data.append('phases', JSON.stringify(phases));
            form_data.append('project_id', $('#project_id').val());
            form_data.append('_token', $('#_token_value_').val());

            var final_ajax_result = $('.final_ajax_result');
            var my_loader = $('#my_loader');
            my_loader.fadeIn(500);
            var res_p = "";
            var url = '/legalUser-access/dashboard/contract/create';
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
                    if (error) {
                        $.each(errorMsg, function (i, val) {
                            res_p = '<p class="alert alert-danger mb-4">' + val + '</p>';
                            final_ajax_result.append(res_p);
                        });

                    } else {
                        res_p = '<p class="alert alert-success mb-4">' + errorMsg + ' ...</p>';
                        final_ajax_result.append(res_p);
                        setTimeout(function () {
                            window.location.href = '/';
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
