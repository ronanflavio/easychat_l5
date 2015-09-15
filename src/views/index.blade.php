<link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/ronanflavio/easychat/css/main.css') }}">

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
                        <form role="form" class="form-horizontal form-bordered user-list" name="form-user-list-{{ $user->{Config::get('easychat::tables.users.id')} }}">
                            <span class="notifications-{{ $user->{Config::get('easychat::tables.users.id')} }}"></span>
                            <a href="javascript:void(0);" class="user-selected" data-user-id="{{ $user->{Config::get('easychat::tables.users.id')} }}" data-user-url="{{ URL::to(Config::get('easychat::uri').'/messages-list') }}">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <!-- User image here (use the "crop-chat" class into the "img" tag) -->
                                                    <img src="{{ (Config::get('easychat::tables.users.photo') ?: URL::asset('packages/ronanflavio/easychat/img/avatar.png')) }}" class="crop-chat"/>
                                                </div>
                                                <div class="col-md-9">
                                                    <!-- Username here -->
                                                    {{ $user->{Config::get('easychat::tables.users.name')} }}
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
                            {{ Form::hidden('to', 0) }}
                            <button type="button" class="btn btn-primary form-control send-button" data-to="0" data-url-send-message="{{ URL::to(Config::get('easychat::uri').'/send-message') }}"> Ok </button>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of conversation here -->
        <!-- data div -->
        <div class="only-links"
            data-users-list="{{ URL::to(Config::get('easychat::uri').'/users-list', array('ajax', 0)) }}"
            data-get-chat-news="{{ URL::to(Config::get('easychat::uri').'/check-messages') }}"
            data-get-all-chat-news="{{ URL::to(Config::get('easychat::uri').'/check-all-messages') }}"
            data-img-loading="{{ URL::asset('packages/ronanflavio/easychat/img/loading.gif') }}"
            data-img-loading-bar="{{ URL::asset('packages/ronanflavio/easychat/img/loading-bar.gif') }}">
        </div>

    </div>
</div>

<!-- If you already referenced the jQuery (v. 2.x.x), delete this line -->
<script src="{{ URL::asset('packages/ronanflavio/easychat/js/jquery-2.1.4.min.js') }}"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ URL::asset('packages/ronanflavio/easychat/css/bootstrap.min.css') }}">
<!-- -->

<script src="{{ URL::asset('packages/ronanflavio/easychat/js/chat.js') }}"></script>
<script src="{{ URL::asset('packages/ronanflavio/easychat/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ URL::asset('packages/ronanflavio/easychat/js/jquery.formatDateTime.min.js') }}"></script>

<style type="text/css">
.scroll-users { height: {{ $config['conversation_inner_box_size'] }} !important; }
.scroll-bottom { height: {{ $config['conversation_inner_box_size'] }} !important; }
.slimScrollDiv { height: {{ $config['conversation_inner_box_size'] }} !important; }
</style>

