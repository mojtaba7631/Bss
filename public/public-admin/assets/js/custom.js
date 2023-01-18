var Errors = {};
var form_data = new FormData();
var my_loader = $('#my_loader');

$('#wizard_real').steps({
    headerTag: 'h2',
    bodyTag: 'section',
    transitionEffect: 'slideLeft',
    onInit: function (event, currentIndex) {
        setButtonWavesEffect(event);
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
        setButtonWavesEffect(event);
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        Errors = {};
        $('.error_validate').text("");
        if (newIndex > currentIndex) {
            if (currentIndex === 0) {
                var name = $('#name');
                if (!name.val()) {
                    Errors.name = 'نام الزامی است';
                } else {
                    form_data.delete('name');
                    form_data.append('name', name.val())
                }

                var family = $('#family');
                if (!family.val()) {
                    Errors.family = 'نام خانوادگی الزامی است';
                } else {
                    form_data.delete('family');
                    form_data.append('family', family.val())
                }

                var national_code = $('#national_code');
                if (!national_code.val()) {
                    Errors.national_code = 'کد ملی الزامی است';
                } else if (!checkNationalCode(national_code.val())) {
                    Errors.national_code = 'کد ملی معتبر نیست';
                } else {
                    form_data.delete('national_code');
                    form_data.append('national_code', national_code.val())
                }

                var id_code = $('#id_code');
                if (!id_code.val()) {
                    Errors.id_code = 'شماره شناسنامه الزامی است';
                } else {
                    form_data.delete('id_code');
                    form_data.append('id_code', id_code.val())
                }

                var birth_date = $('#birth_date');
                if (!birth_date.val()) {
                    Errors.birth_date = 'تاریخ تولد الزامی است';
                } else {
                    form_data.delete('birth_date');
                    form_data.append('birth_date', birth_date.val())
                }

                var father_name = $('#father_name');
                if (!father_name.val()) {
                    Errors.father_name = 'نام پدر الزامی است';
                } else {
                    form_data.delete('father_name');
                    form_data.append('father_name', father_name.val())
                }

                var national_code_img = $('#national_code_img');
                if (!national_code_img.val()) {
                    Errors.national_code_img = 'تصویر کارت ملی الزامی است';
                } else {
                    form_data.delete('national_code_img');
                    form_data.append('national_code_img', national_code_img[0].files[0])
                }

                var image = $('#image');
                if (!image.val()) {
                    Errors.image = 'تصویر پروفایل الزامی است';
                } else {
                    form_data.delete('image');
                    form_data.append('image', image[0].files[0])
                }

                var Signature_img = $('#Signature_img');
                if (!Signature_img.val()) {
                    Errors.Signature_img = 'تصویر امضا الزامی است';
                } else {
                    form_data.delete('Signature_img');
                    form_data.append('Signature_img', Signature_img[0].files[0])
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
                var evidence = $('#evidence');
                if (!evidence.val()) {
                    Errors.evidence = 'مدرک الزامی است';
                } else {
                    form_data.delete('evidence');
                    form_data.append('evidence', evidence.val())
                }

                var field_study = $('#field_study');
                if (!field_study.val()) {
                    Errors.field_study = 'رشته تحصیلی الزامی است';
                } else {
                    form_data.delete('field_study');
                    form_data.append('field_study', field_study.val())
                }

                var address = $('#address');
                if (!address.val()) {
                    Errors.address = ' آدرس الزامی است';
                } else {
                    form_data.delete('address');
                    form_data.append('address', address.val())
                }

                var phone = $('#phone');
                if (!phone.val()) {
                    Errors.phone = 'تلفن الزامی است';
                } else {
                    form_data.delete('phone');
                    form_data.append('phone', phone.val())
                }

                var mobile = $('#mobile');
                if (!mobile.val()) {
                    Errors.mobile = ' موبایل الزامی است';
                } else {
                    form_data.delete('mobile');
                    form_data.append('mobile', mobile.val())
                }

                var social_no = $('#social_no');
                if (!social_no.val()) {
                    Errors.social_no = 'تلفن فضای مجازی  الزامی است';
                } else {
                    form_data.delete('social_no');
                    form_data.append('social_no', social_no.val())
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
            var bank = $('#bank');
            if (!bank.val()) {
                Errors.bank = 'بانک الزامی است';
            } else {
                form_data.delete('bank');
                form_data.append('bank', bank.find('option:selected').val())
            }

            var shaba_number = $('#shaba_number');
            if (!shaba_number.val()) {
                Errors.shaba_number = 'شماره شبا الزامی است';
            } else {
                form_data.delete('shaba_number');
                form_data.append('shaba_number', shaba_number.val())
            }

            var account_number = $('#account_number');
            if (!account_number.val()) {
                Errors.account_number = ' شماره حساب الزامی است';
            } else {
                form_data.delete('account_number');
                form_data.append('account_number', account_number.val())
            }

            var rule_checkbox = $('#rule_checkbox');
            if (!rule_checkbox.is(':checked')) {
                Errors.rule_checkbox = 'تایید قوانین الزامی است';
            } else {
                form_data.delete('rule_checkbox');
                form_data.append('rule_checkbox', rule_checkbox.val())
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
    },
    onFinished: function (event, currentIndex) {

        var url = '/user/register';
        var _token = $("#_token_value_").val();
        var result = "";
        form_data.delete('_token');
        form_data.append('_token', _token);

        form_data.delete('sex');
        form_data.append('sex', $('input[name=sex]:checked').val());

        form_data.delete('type');
        form_data.append('type', 0);

        my_loader.fadeIn(500);

        var final_ajax_result = $('.final_ajax_result');

        $.ajax({
            url: url,
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            method: 'POST',
        }).done(function (res) {
            if (!res['error']) {
                result = "<p class='alert alert-success mt-4'>" + res['message'] + "</p>";
                setTimeout(function () {
                    window.location.href = "/";
                }, 1200)
            } else {
                result = "<p class='alert alert-danger mt-4'>" + res['message'] + "</p>";
            }

            final_ajax_result.find('p').remove();
            final_ajax_result.append(result);
            my_loader.fadeOut(500);
            return true;
        });
    }

});

$('#wizard_legal').steps({
    headerTag: 'h2',
    bodyTag: 'section',
    transitionEffect: 'slideLeft',
    onInit: function (event, currentIndex) {
        setButtonWavesEffect(event);
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
        setButtonWavesEffect(event);
    },
    onStepChanging: function (event, currentIndex, newIndex) {

        Errors = {};
        $('.error_validate').text('');
        if (newIndex > currentIndex) {
            if (currentIndex === 0) {
                var co_name = $('#co_name');
                if (!co_name.val()) {
                    Errors.co_name = 'نام شرکت الزامی است';
                } else {
                    form_data.delete('co_name');
                    form_data.append('co_name', co_name.val())
                }

                var co_reg_number = $('#co_reg_number');
                if (!co_reg_number.val()) {
                    Errors.co_reg_number = 'شماره ثبت شرکت الزامی است';
                } else {
                    form_data.delete('co_reg_number');
                    form_data.append('co_reg_number', co_reg_number.val())
                }

                var co_national_id = $('#co_national_id');
                if (!co_national_id.val()) {
                    Errors.co_national_id = 'شناسه ملی شرکت الزامی است';
                } else {
                    form_data.delete('co_national_id');
                    form_data.append('co_national_id', co_national_id.val())
                }

                var co_reg_date = $('#co_reg_date');
                if (!co_reg_date.val()) {
                    Errors.co_reg_date = 'تاریخ ثبت شرکت الزامی است';
                } else {
                    form_data.delete('co_reg_date');
                    form_data.append('co_reg_date', co_reg_date.val())
                }

                var co_statute_image = $('#co_statute_image');
                if (!co_statute_image.val()) {
                    Errors.co_statute_image = 'عکس اساسنامه الزامی است';
                } else {
                    form_data.delete('co_statute_image');
                    form_data.append('co_statute_image', co_statute_image[0].files[0])
                }

                var Signature_img_legal = $('#Signature_img_legal');
                if (!Signature_img_legal.val()) {
                    Errors.Signature_img_legal = 'تصویر امضا الزامی است';
                } else {
                    form_data.delete('Signature_img');
                    form_data.append('Signature_img', Signature_img_legal[0].files[0])
                }

                var image_legal = $('#image_legal');
                if (!image_legal.val()) {
                    Errors.image_legal = 'تصویر لوگو الزامی است';
                } else {
                    form_data.delete('image');
                    form_data.append('image', image_legal[0].files[0])
                }

                var stamp_img = $('#stamp_img');
                if (!stamp_img.val()) {
                    Errors.stamp_img = 'تصویر مهر الزامی است';
                } else {
                    form_data.delete('stamp_img');
                    form_data.append('stamp_img', stamp_img[0].files[0])
                }

                if (Object.keys(Errors).length > 0) {
                    $.each(Errors, function (i, val) {
                        $('#' + i + '_errors').text(val);
                    });

                    console.log(Errors);

                    return false;
                } else {
                    return true;
                }

            } else if (currentIndex === 1) {

                var ceo_name = $('#ceo_name');
                if (!ceo_name.val()) {
                    Errors.ceo_name = 'نام مدیرعامل الزامی است';
                } else {
                    form_data.delete('ceo_name');
                    form_data.append('ceo_name', ceo_name.val())
                }

                var ceo_family = $('#ceo_family');
                if (!ceo_family.val()) {
                    Errors.ceo_family = 'نام خانوادگی مدیرعامل الزامی است';
                } else {
                    form_data.delete('ceo_family');
                    form_data.append('ceo_family', ceo_family.val())
                }

                var ceo_national_code = $('#ceo_national_code');
                if (!ceo_national_code.val()) {
                    Errors.ceo_national_code = 'کدملی مدیرعامل الزامی است';
                } else {
                    form_data.delete('ceo_national_code');
                    form_data.append('ceo_national_code', ceo_national_code.val())
                }

                var ceo_id_code = $('#ceo_id_code');
                if (!ceo_id_code.val()) {
                    Errors.ceo_id_code = 'شماره شناسنامه مدیرعامل الزامی است';
                } else {
                    form_data.delete('ceo_id_code');
                    form_data.append('ceo_id_code', ceo_id_code.val())
                }

                var ceo_mobile = $('#ceo_mobile');
                if (!ceo_mobile.val()) {
                    Errors.ceo_mobile = 'موبایل مدیرعامل الزامی است';
                } else {
                    form_data.delete('ceo_mobile');
                    form_data.append('ceo_mobile', ceo_mobile.val())
                }


                var manager_name = $('#manager_name');
                if (!manager_name.val()) {
                    Errors.manager_name = 'نام عضو هیات مدیره الزامی است';
                } else {
                    form_data.delete('manager_name');
                    form_data.append('manager_name', manager_name.val())
                }

                var manager_family = $('#manager_family');
                if (!manager_family.val()) {
                    Errors.manager_family = 'نام خانوادگی عضو هیات مدیره الزامی است';
                } else {
                    form_data.delete('manager_family');
                    form_data.append('manager_family', manager_family.val())
                }

                // var manager_national_code = $('#manager_national_code');
                // if (!manager_national_code.val()) {
                //     Errors.manager_national_code = 'کدملی عضو هیات مدیره الزامی است';
                // } else {
                //     form_data.delete('manager_national_code');
                //     form_data.append('manager_national_code', manager_national_code.val())
                // }

                var manager_id_code = $('#manager_id_code');
                if (!manager_id_code.val()) {
                    Errors.manager_id_code = 'شماره شناسنامه عضو هیات مدیره الزامی است';
                } else {
                    form_data.delete('manager_id_code');
                    form_data.append('manager_id_code', manager_id_code.val())
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
            var legal_bank = $('#legal_bank');
            if (!legal_bank.val()) {
                Errors.legal_bank = 'بانک الزامی است';
            } else {
                form_data.delete('legal_bank');
                form_data.append('legal_bank', legal_bank.find('option:selected').val())
            }

            var co_shaba_number = $('#co_shaba_number');
            if (!co_shaba_number.val()) {
                Errors.co_shaba_number = 'شماره شبا الزامی است';
            } else {
                form_data.delete('co_shaba_number');
                form_data.append('co_shaba_number', co_shaba_number.val())
            }

            var co_account_number = $('#co_account_number');
            if (!co_account_number.val()) {
                Errors.co_account_number = ' شماره حساب الزامی است';
            } else {
                form_data.delete('co_account_number');
                form_data.append('co_account_number', co_account_number.val())
            }

            var co_phone = $('#co_phone');
            if (!co_phone.val()) {
                Errors.co_phone = ' تلفن الزامی است';
            } else {
                form_data.delete('co_phone');
                form_data.append('co_phone', co_phone.val())
            }

            var co_post_code = $('#co_post_code');
            if (!co_post_code.val()) {
                Errors.co_post_code = ' کدپستی الزامی است';
            } else {
                form_data.delete('co_post_code');
                form_data.append('co_post_code', co_post_code.val())
            }

            var co_address = $('#co_address');
            if (!co_address.val()) {
                Errors.co_address = ' آدرس الزامی است';
            } else {
                form_data.delete('co_address');
                form_data.append('co_address', co_address.val())
            }

            var co_rule_checkbox = $('#co_rule_checkbox');
            if (!co_rule_checkbox.is(':checked')) {
                Errors.co_rule_checkbox = 'تایید قوانین الزامی است';
            } else {
                form_data.delete('co_rule_checkbox');
                form_data.append('co_rule_checkbox', co_rule_checkbox.val())
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
    },
    onFinished: function (event, currentIndex) {
        var url = '/user/register';
        var _token = $("#_token_value_").val();
        var result = "";
        form_data.delete('_token');
        form_data.append('_token', _token);

        form_data.delete('type');
        form_data.append('type', 1);

        my_loader.fadeIn(500);

        var final_ajax_result = $('.final_ajax_result');

        $.ajax({
            url: url,
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            method: 'POST',
        }).done(function (res) {
            if (!res['error']) {
                result = "<p class='alert alert-success mt-4'>" + res['message'] + "</p>";
                setTimeout(function () {
                    window.location.href = "/";
                }, 1200)
            } else {
                result = "<p class='alert alert-danger mt-4'>" + res['message'] + "</p>";
            }

            final_ajax_result.find('p').remove();
            final_ajax_result.append(result);
            my_loader.fadeOut(500);
            return true;
        });
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
