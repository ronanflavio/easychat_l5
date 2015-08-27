<!-- If you already referenced the jQuery (v. 2.x.x), delete this line -->
{{ HTML::script('vendor/adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}
<!-- -->

<!-- -->

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
                        <form role="form" class="form-horizontal form-bordered user-list" name="form-user-list-{{ $user->id }}">
                            <span class="notifications-{{ $user->id }}"></span>
                            <a href="javascript:void(0);" class="user-selected" data-user-id="{{ $user->id }}" data-user-url="{{ URL::action('ChatController@getMessagesList') }}">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <!-- User image here (use the "crop-chat" class into the "img" tag) -->
                                                    <img src="{{ URL::to('/') }}/assets/img/avatar.png" class="crop-chat"/>
                                                </div>
                                                <div class="col-md-9">
                                                    <!-- Username here -->
                                                    {{ $user->name }}
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
                            <button type="button" class="btn btn-primary form-control send-button" data-to="0" data-url-send-message="{{ URL::action('ChatController@getSendMessage') }}"> Ok </button>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of conversation here -->
        <!-- data div -->
        <div class="only-links"
            data-users-list="{{ URL::action('ChatController@getUsersList', array('ajax', 0)) }}"
            data-get-chat-news="{{ URL::action('ChatController@getCheckMessages') }}"
            data-get-all-chat-news="{{ URL::action('ChatController@getCheckAllMessages') }}">
        </div>

    </div>
</div>

{{ HTML::script('assets/js/chat.js') }}
{{ HTML::script('vendor/format-date-time/jquery.formatDateTime.min.js') }}

<style type="text/css">
.scroll-users { height: {{ $config['conversation_inner_box_size'] }} !important; }
.scroll-bottom { height: {{ $config['conversation_inner_box_size'] }} !important; }
.slimScrollDiv { height: {{ $config['conversation_inner_box_size'] }} !important; }
</style>

