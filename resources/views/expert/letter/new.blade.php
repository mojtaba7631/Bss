@extends('expert.layout.expert_layout')
@section('title',"نوشتن نامه جدید")
@section('css')
    <style>
        .selected_contacts {
            position: relative;
            /*border: 1px solid #c6c6c6;*/
            padding: 5px 10px 5px 25px;
            min-width: 100px;
            display: inline-block;
            box-shadow: 0 0 15px rgba(0, 0, 0, .2);
            margin: 7px;
        }

        .selected_contacts span {
            font-size: 9pt;
        }

        .selected_contacts i {
            position: absolute;
            left: 5px;
            top: 9px;
            font-size: 11pt;
            color: red;
            cursor: pointer;
        }

        #signature_loader, #signature_loader2 {
            display: none;
            width: 25px;
        }

        #confirm_row {
            display: none;
        }
    </style>
@endsection

@section('js')
    <script>
        var choose_contact_section = $('#choose_contact_section');
        var selected_contacts_div = $('#selected_contacts_div');
        var contact_select = $('#contact_select');
        var first_submit_row = $('#first_submit_row');
        var confirm_row = $('#confirm_row');
        var new_letter_id = $('#new_letter_id');
        var confirm_code = $('#confirm_code');
        var can_not_delete = false;
        var formData = new FormData();

        contact_select.change(function () {
            var selected_option = $(this).find('option:selected');

            var new_contact = '<div data-contact="' + selected_option.val() + '" class="selected_contacts">' +
                '<input type="hidden" name="contacts[]" value="' + selected_option.val() + '">' +
                '<span>' + selected_option.text() + '</span>' +
                ' <i onclick="removeContact(this)" class="fa fa-times"></i>' +
                '</div>';

            selected_contacts_div.append(new_contact);

            selected_option.remove()

            contact_select.find('option:first-child').prop('selected', true);
        });

        function removeContact(tag) {
            if (can_not_delete) {
                return false;
            }
            var txt = $(tag).parents('.selected_contacts').find('span').text();
            var val = $(tag).parents('.selected_contacts').find('input').val();
            contact_select.append('<option value="' + val + '">' + txt + '</option>');
            $(tag).parents('.selected_contacts').remove();
        }

        function doSignature(tag) {
            var contacts = selected_contacts_div.find('.selected_contacts');

            if (!contacts.length) {
                do_swal('انتخاب حداقل یک مخاطب الزامی است.');
                return false;
            }

            var final_contacts = [];
            for (var i = 0; i < contacts.length; i++) {
                final_contacts.push(contacts.eq(i).find('input').val());
            }

            formData.append('contacts', JSON.stringify(final_contacts));
            formData.append('title', $("#letter_title").val());
            formData.append('content', $("#letter_content").val());
            formData.append('_token', '{{csrf_token()}}');

            var loader = $("#signature_loader");
            $(tag).prop('disabled', true);
            loader.show();
            var url = "{{route('legalUser_letter_signature')}}";

            $.ajax({
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (res) {
                    if (!res['error']) {
                        first_submit_row.slideUp();
                        confirm_row.slideDown({
                            start: function () {
                                $(this).css({
                                    display: "flex"
                                })
                            }
                        });
                        var letter_id = res['letter_id'];
                        new_letter_id.val(letter_id);

                        choose_contact_section.css('opacity', .3);
                        contact_select.prop('disabled', true);
                        can_not_delete = true;

                    } else {
                        $(tag).prop('disabled', false);
                        loader.hide();
                        do_swal(res['errorMsg']);
                    }
                }
            });
        }

        function do_swal(txt) {
            swal(txt);
        }

        function send_letter(tag) {
            var loader = $("#signature_loader2");
            if (confirm_code.val()) {

                formData.append('letter_id', new_letter_id.val());
                formData.append('code', confirm_code.val());
                formData.append('_token', '{{csrf_token()}}');
                formData.append('title', $("#letter_title").val());
                formData.append('content', $("#letter_content").val());

                $(tag).prop('disabled', true);
                loader.show();
                var url = "{{route('legalUser_letter_add')}}";


                $.ajax({
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (res) {
                        if (!res['error']) {
                            do_swal(res['errorMsg']);
                            setTimeout(function () {
                                window.location.href = res['location'];
                            }, 2000)
                        } else {
                            $(tag).prop('disabled', false);
                            loader.hide();
                            do_swal(res['errorMsg']);
                            if (res['refresh']) {
                                setTimeout(function () {
                                    location.reload();
                                }, 1500)
                            }
                        }
                    }
                });
            } else {
                $(tag).prop('disabled', false);
                loader.hide();
                do_swal('کد تایید الزامی است.');
            }
        }
    </script>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>نوشتن نامه جدید</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">نوشتن نامه جدید</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right hidden-xs">
                        <a href="{{route('expert_letter_sent_index')}}" class="add_btn btn btn-sm btn-danger" title=""><i
                                class="fa fa-arrow-right mr-2"></i>
                            بازگشت به نامه های ارسالی
                        </a>

                        <a href="{{route('expert_letter_delivered_index')}}" class="add_btn btn btn-sm btn-danger" title=""><i
                                class="fa fa-arrow-right mr-2"></i>
                            بازگشت به نامه های دریافتی
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <div id="choose_contact_section" class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label>مخاطب (ها)</label>
                                    <select id="contact_select" class="form-control">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($contacts as $contact)
                                            <option value="{{$contact['contact']}}">
                                                @if($contact['type'] > 0)
                                                    {{$contact['co_name']}}
                                                @else
                                                    {{$contact['name'] . ' ' . $contact['family']}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div id="selected_contacts_div" class="col-12">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label>عنوان نامه</label>
                                    <input id="letter_title" class="form-control" name="title">
                                </div>
                                <div class="col-12 mt-4">
                                    <label>متن نامه</label>
                                    <textarea id="letter_content" class="summernote" name="content"></textarea>
                                </div>
                            </div>

                            <div id="first_submit_row" class="row mt-4">
                                <div class="col-12 col-sm-3 col-md-3 text-right">
                                    <button onclick="doSignature(this)" class="btn btn-success w-100">
                                        <img id="signature_loader" class="mr-1"
                                             src="{{asset('placeholder/loader_gif.svg')}}">
                                        <i class="fa fa-send mr-2"></i>
                                        امضا و ارسال
                                    </button>
                                </div>
                            </div>

                            <div id="confirm_row" class="row mt-4">
                                <div class="col-12 col-sm-6 col-md-3 text-right">
                                    <input type="hidden" id="new_letter_id">
                                    <input id="confirm_code" class="form-control" type="text" name="code"
                                           placeholder="کد تایید را وارد نمایید">
                                </div>
                                <div class="col-12 col-sm-4 col-md-2 text-right">
                                    <button onclick="send_letter(this)" class="btn btn-success w-100">
                                        <img id="signature_loader2" src="{{asset('placeholder/loader_gif.svg')}}">
                                        <i class="fa fa-check mr-2"></i>
                                        تایید
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
