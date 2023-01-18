@extends('employer.layout.employer_layout')
@section('title')
    تیکت ها
@endsection

@section('css')
    <style>
        .contact_big_img {
            width: 150px;
            height: 150px;
            margin: 20px auto 30px;
        }

        .contact_big_name {
            font-size: 12pt;
        }

        .ticket_body, .chatapp_body {
            height: 70vh;
            overflow: auto;
            position: relative;
        }

        .chatapp_list {
            height: 100%;
        }

        .chatapp_list .right_chat {
            padding-bottom: 25px;
        }

        .chat_gif {
            width: 120px;
            margin-top: 50px;
        }

        .send_file_btn input {
            position: absolute;
            right: 0;
            left: 0;
            margin: auto;
            top: 0;
            bottom: 0;
            width: 100%;
            height: 40px;
            opacity: 0;
        }

        #send_file_file_name {
            position: absolute;
            top: -65px;
            right: -13px;
            box-shadow: 0 0 15px rgba(0, 0, 0, .2);
            border-radius: 5px;
            padding: 15px 20px;
            min-width: 150px;
            background: #fff;
            display: none;
        }

        #send_file_file_name i {
            color: red;
            position: absolute;
            left: 10px;
            top: 17px;
            margin: auto;
        }

        .chat-message {
            position: sticky;
            background: #fff;
            width: 100%;
            bottom: 10px;
        }

        .right .ticket_message_attach {
            position: absolute;
            right: 0;
            bottom: 38px;
            height: 30px;
            text-align: center;
        }

        .left .ticket_message_attach {
            position: absolute;
            left: 0;
            bottom: 38px;
            height: 30px;
            text-align: center;
        }

        .chat-history {
            padding: 35px 20px !important;
        }

        .contact_new_messages_count {
            position: absolute;
            right: -5px;
            top: -5px;
            border-radius: 50%;
            text-align: center;
            background: green;
            width: 29px;
            height: 29px;
            color: #fff;
            line-height: 29px;
            font-size: 8pt;
        }

        .media .avtar-pic img {
            width: 100%;
            max-width: 35px;
            border-radius: 4px;
        }
    </style>
@endsection

@section('js')
    <script>
        var send_file_btn_input = $('#send_file_btn_input');
        send_file_btn_input.change(
            function () {
                var file = $(this)[0].files[0];
                if (file) {
                    var send_file_file_name = $("#send_file_file_name");
                    send_file_file_name.find('span').text(file.name);
                    send_file_file_name.slideDown();
                }
            }
        );

        function close_file(tag) {
            $(tag).prev('span').text("");
            $(tag).parent().slideUp();
            send_file_btn_input.val("");
        }

        var d = $('#chat_scroll');
        d.scrollTop(d.prop("scrollHeight"));
    </script>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h1>تیکت ها ها</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">نما</a></li>
                                <li class="breadcrumb-item active" aria-current="page">گزارش ها</li>
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
                        <div class="body position-relative ticket_body">
                            <div class="chatapp_list">
                                <ul class="right_chat list-unstyled mb-0">
                                    @foreach($users as $user)
                                        <li class="offline">
                                            <a href="{{route('employer_ticket_index', ['contact_id' => $user['id']])}}">

                                                @if($user['new_messages'] > 0)
                                                    <span
                                                        class="contact_new_messages_count">{{$user['new_messages']}}</span>
                                                @endif

                                                <div class="media">
                                                    <div class="avtar-pic w35 bg-red">
                                                        @if((file_exists($user['image']) and !is_dir($user['image'])))
                                                            <img src="{{asset($user['image'])}}">
                                                        @else
                                                            <span>
                                                                {{substr($user['name'],0, 2) . ' ' . substr($user['family'],0,2)}}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="media-body">
                                                        <span
                                                            class="name">{{$user['name'] . ' ' . $user['family']}}</span>
                                                        <span class="message">
                                                            @foreach($user['roles'] as $role)
                                                                {{$role}}
                                                            @endforeach
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div id="chat_scroll" class="chatapp_body">
                                <div class="chat-header clearfix">
                                    <div class="row clearfix">
                                        <div class="col-lg-12">
                                            <div class="chat-about">
                                                @if($contact_id != "")
                                                    <h6 class="m-b-0">
                                                        {{$contact_info['name'] . ' ' . $contact_info['family']}}
                                                    </h6>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-history">
                                    <ul class="message_data">
                                        @if($contact_id != "")
                                            @if(!empty($messages->all()))
                                                @foreach($messages as $message)
                                                    @if($message['from'] == auth()->id())
                                                        @php
                                                            $dir = "right";
                                                            $img = auth()->user()->image;
                                                        @endphp
                                                    @else
                                                        @php
                                                            $dir = "left";
                                                            if (file_exists($message['images']) and !is_dir($message['images'])){
                                                                $img = $message['images'];
                                                            } else {
                                                                $img = '/placeholder/user_placeholder_2.png';
                                                            }

                                                        @endphp
                                                    @endif
                                                    <li class="{{$dir}} clearfix">
                                                        <img class="user_pix" src="{{asset($img)}}"
                                                             alt="avatar">
                                                        <div class="message">
                                                            <span>{{$message['content']}}</span>
                                                        </div>
                                                        <span class="data_time">
                                                        {{$message['jalali_date'] . " " . $message['jalali_time']}}
                                                    </span>

                                                        @if($message['has_file'] > 0)
                                                            <a href="{{route('employer_ticket_download_ticket', ['ticket_id' => $message['ticket_id']])}}"
                                                               class="btn btn-warning ticket_message_attach">
                                                                <i class="icon-paper-clip"></i>
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endforeach

                                            @else
                                                <p class="alert alert-info">
                                                    هیچ پیامی ارسال نشده است. اولین پیام خود را ارسال نمایید.
                                                </p>
                                            @endif
                                        @else
                                            <div class="text-center">
                                                <img class="chat_gif" src="{{asset('images/chat_gif.gif')}}">

                                                <p class="mt-4 text-center">
                                                    جهت ارسال پیام از لیست مخاطبان در سمت چپ یک نفر را انتخاب کنید.
                                                </p>
                                            </div>
                                        @endif
                                    </ul>
                                </div>

                                @if($contact_id != "")
                                    <div class="chat-message clearfix">
                                        <form
                                            action="{{route("employer_ticket_send", ["contact_id" => $contact_id])}}"
                                            enctype="multipart/form-data"
                                            method="post" class="input-group mb-0">
                                            @csrf
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="btn btn-link send_file_btn position-relative">
                                                        <p id="send_file_file_name">
                                                            <span></span>
                                                            <i onclick="close_file(this)" class="fa fa-times"></i>
                                                        </p>
                                                        <i class="icon-paper-clip text-warning"></i>
                                                        <input name="file" id="send_file_btn_input" type="file">
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="content" type="text" class="form-control"
                                                      placeholder="متن را اینجا وارد کنید..."></textarea>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <button type="submit"
                                                            class="btn btn-link send_file_btn position-relative">
                                                        <i class="icon-cursor text-success"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <div class="chatapp_detail text-center vivify pullLeft delay-150">
                                <div class="profile-image">
                                    @if(file_exists($contact_info['image']) and !is_dir($contact_info['image']))
                                        @php
                                            $image_src = asset($contact_info['image']);
                                        @endphp
                                    @else
                                        @php
                                            $image_src = asset('placeholder/user_placeholder.png');
                                        @endphp
                                    @endif
                                    <img src="{{$image_src}}"
                                         class="rounded-circle contact_big_img mb-3" alt="">
                                </div>

                                <h5 class="mb-0 mt-4 contact_big_name">
                                    {{$contact_info['name'] . ' ' . $contact_info['family']}}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
