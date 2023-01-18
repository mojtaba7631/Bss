@extends('admin.layout.reg_layout')
@section('title',"ثبت نام")

@section('css')
    <style>
        body {
            background-image: url({{asset("/images/back_.png")}}) !important;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
        }

        .radio_span {
            font-size: 13px;
        }

        .card_bottom {
            margin-bottom: 0 !important;
        }

        .inp_check {
            display: contents !important;
        }

        .sheba_rtl {
            direction: ltr;
        }

        .error_validate {
            font-size: 12px;
        }

        .error_loc {
            display: inline-block;
        }

        .wizard .steps .number {
            display: none;
        }
    </style>
@endsection

@section('js')
    <script>
        $('input[type=radio][name=type]').change(function () {
            let type = $(this).val();
            let for_show = type;
            let for_hide = type === 'legal' ? 'real' : 'legal';
            $('.' + for_show).fadeIn();
            $('.' + for_hide).hide();
            setTimeout(function () {
                setHeight();
            }, 1000)
        });

        function setHeight(currentIndex = 1) {
            var tag = $('#wizard_legal_' + currentIndex);
            var height = tag.height();
            tag.parent().css('min-height', Math.ceil(height + 200) + 'px');
        }

        setHeight();

        $(document).ready(function () {
            var video = document.getElementById("bg_video");
            video.play();

            $('.datePicker').persianDatepicker({
                initialValue: false,
                format: "YYYY/MM/DD"
            });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <input type="hidden" id="_token_value_" value="{{csrf_token()}}">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card card_shadow">
                <div class="body">
                    <h6 class="mt-5">ایجاد حساب کاربری</h6>
                    <br>
                    <div class="fancy-radio">
                        <label class="mr-5">
                            <input id="real" name="type" value="real" type="radio" checked>
                            <span class="radio_span"><i></i>حقیقی</span>
                        </label>
                        <label>
                            <input id="legal" name="type" value="legal" type="radio">
                            <span class="radio_span"><i></i>حقوقی</span>
                        </label>
                    </div>

                    <br>

                    <div id="wizard_real" class="real">
                        <h2>مشخصات فردی</h2>
                        <section>
                            <div class="form-auth-small m-t-20">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <input id="name" type="text" class="form-control round" placeholder="نام *"
                                                   name="name" value="">
                                            <div id="name_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <input id="family" type="text" class="form-control round" name="family"
                                                   placeholder="نام خانوادگی *">
                                            <div id="family_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <input id="national_code" type="text" class="form-control round"
                                                   placeholder="کدملی *"
                                                   name="national_code">
                                            <div id="national_code_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <input id="id_code" type="text" class="form-control round" name="id_code"
                                                   placeholder="شماره شناسنامه *">
                                            <div id="id_code_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 mt-3">
                                        <h6>جنسیت</h6><br>
                                        <div class="fancy-radio  mb-4">
                                            <label class="mr-5">
                                                <input name="sex" value="1" type="radio" class="inp_check"
                                                       checked>
                                                <span class="radio_span"><i></i>مرد</span>
                                            </label>
                                            <label>
                                                <input name="sex" value="0" class="inp_check" type="radio">
                                                <span class="radio_span"><i></i>زن</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 mt-3">
                                        <span>تاریخ تولد</span>
                                        <input id="birth_date" class="form-control datePicker"
                                               name="birth_date">
                                        <div id="birth_date_errors" class="error_validate text-danger"></div>
                                    </div>
                                    <div class="col-12 col-md-4 mt-3">
                                        <span>نام پدر</span>
                                        <div class="form-group">
                                            <input id="father_name" type="text" class="form-control round"
                                                   name="father_name"
                                                   placeholder="نام پدر *">
                                            <div id="father_name_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <label>بارگذاری تصویر کارت ملی *</label>
                                                <div class="card card_bottom">
                                                    <div class="body">
                                                        <input type="file" class="dropify" name="national_code_img"
                                                               id="national_code_img">
                                                        <div id="national_code_img_errors"
                                                             class="error_validate text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label>بارگذاری عکس پروفایل *</label>
                                                <div class="card card_bottom">
                                                    <div class="body">
                                                        <input type="file" class="dropify" name="image"
                                                               id="image">
                                                        <div id="image_errors"
                                                             class="error_validate text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label>بارگذاری امضا *</label>
                                                <div class="card card_bottom">
                                                    <div class="body">
                                                        <input type="file" class="dropify" name="Signature_img"
                                                               id="Signature_img">
                                                        <div id="Signature_img_errors"
                                                             class="error_validate text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <h2>ارتباطات</h2>
                        <section>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <div id="evidence_errors" class="error_validate text-danger"></div>
                                        <select id="evidence" class="form-select form-control round" name="evidence"
                                                aria-label="Default select example round">
                                            <option selected value="">لطفا مدرک خود را انتخاب کنید *</option>
                                            <option value="1">فوق دکتری</option>
                                            <option value="2">دکتری</option>
                                            <option value="3">کارشناسی ارشد</option>
                                            <option value="4">کارشناسی</option>
                                            <option value="5">فوق دیپلم</option>
                                            <option value="6">دیپلم</option>
                                            <option value="7">حوزوی</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <div id="field_study_errors" class="error_validate text-danger"></div>
                                        <input id="field_study" type="text" name="field_study"
                                               class="form-control round"
                                               placeholder="رشته تحصیلی *">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div id="address_errors" class="error_validate text-danger"></div>
                                        <textarea id="address" type="text"
                                                  class="form-control round text_area_height"
                                                  placeholder="آدرس محل سکونت *" name="address"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div id="phone_errors" class="error_validate text-danger"></div>
                                        <input id="phone" type="text" name="phone" class="form-control round"
                                               placeholder="تلفن ثابت *">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div id="mobile_errors" class="error_validate text-danger"></div>
                                        <input id="mobile" type="text" name="mobile" class="form-control round"
                                               placeholder="تلفن همراه *">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div id="social_no_errors" class="error_validate text-danger"></div>
                                        <input id="social_no" type="text" class="form-control round "
                                               name="social_no"
                                               placeholder="تلفن شبکه های مجازی *">
                                    </div>
                                </div>


                            </div>
                        </section>
                        <h2>اطلاعات مالی</h2>
                        <section>
                            <div class="row">
                                <div class="col-12 col-md-4 mb-5">
                                    <select id="bank" class="form-select form-control round" name="bank"
                                            aria-label="Default select example round">
                                        <option selected value="">لطفا بانک خود را انتخاب کنید *</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="bank_errors" class="error_validate text-danger"></div>
                                </div>
                                <div class="col-12 col-md-4 mb-5">
                                    <div class="form-group">
                                        <input id="account_number" type="text" name="account_number"
                                               class="form-control round"
                                               placeholder="شماره حساب *">
                                        <div id="account_number_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-md-11">
                                    <input id="shaba_number" type="text" name="shaba_number"
                                           class="form-control round sheba_rtl"
                                           placeholder="شماره شبا *">
                                    <div id="shaba_number_errors" class="error_validate text-danger"></div>
                                </div>
                                <div class="col-12 col-md-1">
                                    <label>IR</label>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="fancy-checkbox">
                                        <input id="rule_checkbox" type="checkbox" name="checkbox">
                                        <span>تمام قوانین را میپذیرم و مایلم در این سامانه نام نویسی کنم</span>
                                    </label>
                                    <div id="rule_checkbox_errors" class="error_validate text-danger"></div>

                                </div>


                                <div class="col-12 final_ajax_result"></div>
                            </div>
                        </section>
                    </div>

                    <div id="wizard_legal" class="legal" style="display: none">
                        <h2>مشخصات شرکت</h2>
                        <section id="wizard_legal_1">
                            <div class="form-auth-small m-t-20">
                                <div class="row my_content_tab">
                                    <div class="col-12 col-md-3">
                                        <span>نام شرکت</span>
                                        <div class="form-group">
                                            <input id="co_name" type="text" class="form-control round"
                                                   placeholder="نام شرکت *"
                                                   name="co_name">
                                            <div id="co_name_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <span>شماره ثبت</span>
                                        <div class="form-group">
                                            <input id="co_reg_number" name="co_reg_number" type="text"
                                                   class="form-control round" name="family"
                                                   placeholder="شماره ثبت *">
                                            <div id="co_reg_number_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <span>شناسه ملی</span>
                                        <div class="form-group">
                                            <input id="co_national_id" type="text" class="form-control round"
                                                   placeholder="شناسه ملی *"
                                                   name="co_national_id">
                                            <div id="co_national_id_errors" class="error_validate text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <span>تاریخ ثبت</span>
                                        <input data-jdp id="co_reg_date" type="text" class="form-control datePicker"
                                               style="display: block"
                                               name="co_reg_date">
                                        <div id="co_reg_date_errors" class="error_validate text-danger"></div>
                                    </div>

                                    <div class="col-12 mt-5">
                                        <div class="row">
                                            <div class="col-12 col-md-3">
                                                <label>بارگذاری امضا *</label>
                                                <div class="card card_bottom">
                                                    <div class="body">
                                                        <input type="file" class="dropify" name="Signature_img_legal"
                                                               id="Signature_img_legal">
                                                        <div id="Signature_img_legal_errors"
                                                             class="error_validate text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <label>بارگذاری لوگو *</label>
                                                <div class="card card_bottom">
                                                    <div class="body">
                                                        <input type="file" class="dropify" name="image_legal"
                                                               id="image_legal">
                                                        <div id="image_legal_errors"
                                                             class="error_validate text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <label>بارگذاری مهر *</label>
                                                <div class="card card_bottom">
                                                    <div class="body">
                                                        <input type="file" class="dropify" name="stamp_img"
                                                               id="stamp_img">
                                                        <div id="stamp_img_errors"
                                                             class="error_validate text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <label>بارگذاری اساسنامه *</label>
                                                <div class="card card_bottom">
                                                    <div class="body">
                                                        <input type="file" class="dropify" name="co_statute_image"
                                                               id="co_statute_image">
                                                        <div id="co_statute_image_errors"
                                                             class="error_validate text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                        <h2>مشخصات اعضا</h2>
                        <section id="wizard_legal_2">
                            <div class="row my_content_tab">
                                <div class="col-12 text-left mb-2">
                                    <label>مشخصات مدیرعامل</label>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="ceo_name" name="ceo_name" class="form-control round"
                                               placeholder="نام مدیرعامل *">
                                        <div id="ceo_name_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control round " name="ceo_family" id="ceo_family"
                                               placeholder="نام خانوادگی مدیرعامل *">
                                        <div id="ceo_family_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control round " name="ceo_national_code"
                                               id="ceo_national_code" placeholder="کدملی *">
                                        <div id="ceo_national_code_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control round " name="ceo_id_code"
                                               id="ceo_id_code" placeholder="شماره شناسنامه *">
                                        <div id="ceo_id_code_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control round " name="ceo_mobile"
                                               id="ceo_mobile" placeholder="موبایل مدیرعامل *">
                                        <div id="ceo_mobile_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 text-left mb-2">
                                    <label class="mt-4">اعضای هیات مدیره</label>
                                </div>
                                <div class="col-12 col-md-3">

                                    <div class="form-group">
                                        <input type="text" id="manager_name" name="manager_name"
                                               class="form-control round"
                                               placeholder="نام *">
                                        <div id="manager_name_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control round " name="manager_family"
                                               id="manager_family" placeholder="نام خانوادگی *">
                                        <div id="manager_family_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control round " name="manager_national_code"
                                               id="manager_national_code" placeholder="کدملی *">
                                        <div id="manager_national_code_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control round " name="manager_id_code"
                                               id="manager_id_code" placeholder="شماره شناسنامه *">
                                        <div id="manager_id_code_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <h2>ارتباطات</h2>
                        <section id="wizard_legal_3">
                            <div class="row my_content_tab">
                                <div class="col-12 col-md-3 mb-5">
                                    <select class="form-select form-control round" name="legal_bank" id="legal_bank"
                                            aria-label="Default select example round">
                                        <option value="" selected>لطفا بانک خود را انتخاب کنید *</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="legal_bank_errors" class="error_validate text-danger"></div>
                                </div>
                                <div class="col-12 col-md-3 mb-5">
                                    <div class="form-group">
                                        <input type="text" name="co_account_number" id="co_account_number"
                                               class="form-control round"
                                               placeholder="شماره حساب *">
                                        <div id="co_account_number_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5  ">
                                    <input type="text" name="co_shaba_number" id="co_shaba_number"
                                           class="form-control round sheba_rtl"
                                           placeholder="شماره شبا *">
                                    <div id="co_shaba_number_errors" class="error_validate text-danger"></div>
                                </div>
                                <div class="col-12 col-md-1 ">
                                    <label>IR</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="co_phone" id="co_phone" class="form-control round"
                                               placeholder="تلفن *">
                                        <div id="co_phone_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="co_post_code" id="co_post_code"
                                               class="form-control round"
                                               placeholder="کدپستی *">
                                        <div id="co_post_code_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="form-group">
                                        <textarea rows="2" type="text" name="co_address" id="co_address"
                                                  class="form-control round"
                                                  placeholder="آدرس *"></textarea>
                                        <div id="co_address_errors" class="error_validate text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" name="co_rule_checkbox"
                                               data-parsley-errors-container="#error-checkbox" id="co_rule_checkbox">
                                        <span>تمام قوانین را میپذیرم و مایلم در این سامانه نام نویسی کنم</span>
                                    </label>
                                    <div id="co_rule_checkbox_errors" class="error_validate text-danger"></div>

                                </div>

                                <div class="col-12 final_ajax_result"></div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


