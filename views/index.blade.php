<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <!-- keep this line bellow if you haven't a X-CSRF-TOKEN meta tag yet -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- keep this style sheet in this page -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/ronanflavio/easychat/css/main.css') }}">
        <!-- Latest compiled and minified CSS [REMOVE IF YOU ALREADY ADDED] -->
        <link rel="stylesheet" href="{{ URL::asset('packages/ronanflavio/easychat/css/bootstrap.min.css') }}">
        <!-- If you already referenced the jQuery (v. 2.x.x), delete this line -->
        <script src="{{ URL::asset('packages/ronanflavio/easychat/js/jquery-2.1.4.min.js') }}"></script>
        <!-- -->
        <title>Easychat</title>
    </head>
    <body class="skin-blue sidebar-mini">

        <div class="row">
            <div class="col-md-12">

                <!-- Users list starts here -->
                <div class="col-md-4">
                    <!-- Begin Portlet PORTLET-->
                    <div class="portlet">
                        <div class="portlet-title">
                            <div class="caption">
                                Users list
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="scroller scroll-users" style="height:500px">

                                <!-- First iteration for active users, and the last for inactive users -->
                                @foreach ($users as $item)
                                @foreach ($item as $user)
                                <form role="form" class="form-horizontal form-bordered user-list" name="form-user-list-{{ $user->{config('packages.Ronanflavio.Easychat.tables.users.id')} }}">
                                    <span class="notifications-{{ $user->{config('packages.Ronanflavio.Easychat.tables.users.id')} }}"></span>
                                    <a href="javascript:void(0);" class="user-selected" data-user-id="{{ $user->{config('packages.Ronanflavio.Easychat.tables.users.id')} }}" data-user-url="{{ URL::route('easychat.messages.list') }}">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <!-- User image here (use the "crop-chat" class into the "img" tag) -->
                                                            <img src="{{ (config('packages.Ronanflavio.Easychat.tables.users.photo') ?: URL::asset('packages/ronanflavio/easychat/img/avatar.png')) }}" class="crop-chat"/>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <!-- Username here -->
                                                            {{ $user->{config('packages.Ronanflavio.Easychat.tables.users.name')} }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </form>
                                @endforeach
                                @endforeach
                                <!-- End of iteration here -->

                            </div>
                        </div>
                    </div>
                    <!-- End Portlet PORTLET-->
                </div>
                <!-- End of users list -->

                <!-- Begin conversation here -->
                <div class="col-md-8 well well-grey" style="height:{{ $config['conversation_out_box_size'] }}">
                    <div class="portlet gren">
                        <div class="portlet-title">
                            <div class="caption">
                                <p class="user_conversation_title">
                                    <!-- Isert here a title to the conversation area -->
                                    Select a user to chat
                                </p>
                            </div>
                        </div>
                        <div class="portlet-body portlet-conversation" style="height:{{ $config['conversation_inner_box_size'] }}">
                            <div class="scroller scroll-bottom" style="height:{{ $config['conversation_inner_box_size'] }} !important;">

                            <div class="div_conversation" data-base-url="{{ URL::to('/') }}"></div>
                                <!-- Conversation here -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <input type="text" class="form-control text-message form-control">
                                <span class="input-group-btn">
                                    <input type="hidden" name="to" value="0">
                                    <button type="button" class="btn btn-primary form-control send-button" data-to="0" data-url-send-message="{{ URL::route('easychat.send.message') }}"> Ok </button>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End of conversation here -->
                <!-- data div -->
                <div class="only-links"
                    data-users-list="{{ URL::route('easychat.users.list', ['ajax', 0]) }}"
                    data-get-chat-news="{{ URL::route('easychat.check.messages') }}"
                    data-get-all-chat-news="{{ URL::route('easychat.check.allmessages') }}"
                    data-img-loading="{{ URL::asset('packages/ronanflavio/easychat/img/loading.gif') }}"
                    data-img-loading-bar="{{ URL::asset('packages/ronanflavio/easychat/img/loading-bar.gif') }}">
                </div>

            </div>
        </div>

        <script src="{{ URL::asset('packages/ronanflavio/easychat/js/chat.js') }}"></script>
        <script src="{{ URL::asset('packages/ronanflavio/easychat/js/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ URL::asset('packages/ronanflavio/easychat/js/jquery.formatDateTime.min.js') }}"></script>

        <style type="text/css">
        .scroll-users { height: {{ $config['conversation_inner_box_size'] }} !important; }
        .scroll-bottom { height: {{ $config['conversation_inner_box_size'] }} !important; }
        .slimScrollDiv { height: {{ $config['conversation_inner_box_size'] }} !important; }
        </style>

    </body>
</html>
