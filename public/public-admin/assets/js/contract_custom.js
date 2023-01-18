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

function setComma(tag, cp = false, is_prepayment = false, total_price = false) {
    var this_inp_value = $(tag).val();

    var first_char = this_inp_value.charAt(0);
    if (first_char === 0 || first_char === '0') {
        $(tag).val(this_inp_value.substring(1, this_inp_value.length));
    }

    var last_char = this_inp_value.charAt(this_inp_value.length - 1);
    if (last_char !== 0
        && last_char !== 1
        && last_char !== 2
        && last_char !== 3
        && last_char !== 4
        && last_char !== 5
        && last_char !== 6
        && last_char !== 7
        && last_char !== 8
        && last_char !== 9
        && last_char !== '0'
        && last_char !== '1'
        && last_char !== '2'
        && last_char !== '3'
        && last_char !== '4'
        && last_char !== '5'
        && last_char !== '6'
        && last_char !== '7'
        && last_char !== '8'
        && last_char !== '9'
    ) {
        $(tag).val(this_inp_value.substring(0, this_inp_value.length - 1));
    }

    var value = $(tag).val();
    value = removeComma(value);
    value = putComma(value);
    $(tag).val(value);

    if (cp) {
        setTimeout(function () {
            check_phases();
        }, 100)
    }

    if (total_price) {
        setTimeout(function () {
            type_price_chars();
        }, 150)
    }

    if (is_prepayment) {
        setTimeout(function () {
            var max_val = parseInt($(tag).attr('data-max'));
            if (parseInt(removeComma(this_inp_value)) > max_val) {
                var alert_modal = $("#alert_modal");
                alert_modal.find('p').remove();
                alert_modal.find('.modal-body').append('<p class="alert alert-danger">سقف پیش پرداخت مبلغ ' + putComma(max_val) + ' است.</p>');
                alert_modal.modal('show');

                $(tag).val(putComma(max_val));
                return false;
            }
        }, 100);
    }

    return true;
}


