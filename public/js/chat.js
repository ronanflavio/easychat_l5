jQuery(function($){
    'use strict';

    var user_selected = null,
        url_users_list = $('.only-links').data('users-list'),
        users_list,
        img_loading = $('.only-links').data('img-loading'),
        img_loading_bar = $('.only-links').data('img-loading-bar');

    $(window).bind("load", function() {
        $.post(url_users_list, {'all_users':true}, function(response){
            users_list = response;
        });
    });

    $('.scroll-users').slimScroll();


    window.setInterval(function get_news() {
        if (user_selected != null) {
            var url = $('.only-links').data('get-chat-news'),
                user_id = user_selected.data('user-id');

            $.post(url, {'user_id': user_id}, function(response){
                if (response == true) {
                    refresh_chat(user_selected, 'true');
                    $('form[name=form-user-list-'+user_id+']').prependTo('.scroll-users');
                }
            });
        }

        var url_all = $('.only-links').data('get-all-chat-news');

        $.post(url_all, {}, function(response){
            if (response) {
                $.each (response, function(key, value) {
                    var form_user = $('form[name=form-user-list-'+value.sent_by+']'),
                        notifications_user = $('.notifications-'+value.sent_by);
                    form_user.prependTo('.scroll-users');
                    notifications_user.html('<span class="notification">'+value.message_count+'</span>')
                });
            }
        });

    }, 5000);

    $('.text-message').keypress(function(e){
        if (e.which == 13) {
            send_message($('.send-button'));
        }
    });

    if ($('.send-button').data('to') === 0) {
        $('.text-message').attr('readonly', 'readonly');
        $('.send-button').attr('disabled', 'disabled');
        $('.portlet-conversation').addClass('well well-grey');
    }

    $('.user-selected').click(function(e) {
        user_selected = $(this);
        var base_url = $('.div_conversation').data('base-url'),
            user_id = user_selected.data('user-id');
        $('.div_conversation').html('<div class="center" style="line-height:350px"><img src="'+img_loading+'"></div>');
        refresh_chat($(this), 'true');
        // $('form[name=form-user-list-'+user_id+']').closest('span.notification').remove();
        // $('form[name=form-user-list-'+user_id+']').closest('span.notification').remove();
    });

    $('body').on('click', '.load_conversation', function(){
        var base_url = $('.div_conversation').data('base-url');
        $(this).html('<div class="center"><img src="'+img_loading_bar+'"></div>');
        refresh_chat(user_selected, 'false');
    });

    function refresh_chat(input, limit) {
        var url = input.data('user-url'),
            user_id = input.data('user-id'),
            send_button = $('.send-button'),
            input_text = $('.text-message'),
            form_user = $('form[name=form-user-list-'+user_id+']');
        input_text.removeAttr('readonly');
        send_button.removeAttr('disabled');
        $('.portlet-conversation').removeClass('well');

        if (limit == 'true') {
            input_text.focus();
        }

        $(':input[name=to]').val(user_id);
        $('.user-list').removeClass('chat-user-selected');
        form_user.addClass('chat-user-selected');

        $.post(url, {'user_id': user_id, 'limit': limit}, function(response){
            var notifications_user = $('.notifications-'+user_id);
            if ( ! ($.isPlainObject(response) && 'status' in response)) {
                // not a json valid object
                return;
            }

            $('.user_conversation_title').text('Conversa com ' + response.user_name);

            if (response.status == 'success') {
                var $chat = $('<ul id="nav" class="list-unstyled" />'),
                    base_url = $('.div_conversation').data('base-url'),
                    chat;

                if (response.chat_count > 10 && limit == 'true') {
                    $chat.append('<a href="javascript:void(0);" class="load_conversation"><li><div class="alert alert-warning center">Ver todas as mensagens</div></li></a>');
                }

                for (var key in response.chats) {
                    chat = response.chats[key];
                        // Split timestamp into [ Y, M, D, h, m, s ]
                    var t = chat.created_at.split(/[- :]/),
                        // Apply each element to the Date function
                        d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

                        if ($.formatDateTime('dd/mm/yy', d) == $.formatDateTime('dd/mm/yy', new Date())) {
                            var date_message = 'Hoje '+$.formatDateTime('hh:ii', d);
                        } else {
                            var date_message = $.formatDateTime('dd/mm/yy (hh:ii)', d);
                        }

                    chat_append($chat, chat, response, date_message);
                }

                $('.div_conversation').html($chat);

                if (limit == 'true') {
                    $('.scroll-bottom').slimScroll({
                        scrollTo: $('.scroll-bottom')[0].scrollHeight
                    });
                }

                notifications_user.empty();

            } else {
                $('.div_conversation').html('<p>Não há conversas com este usuário.</p>');
            }
        });
    }

    $('.send-button').click(function(){
        send_message($(this));
    });

    function send_message(input) {
        var to = $(':input[name=to]').val(),
            url = input.data('url-send-message'),
            text = $('.text-message').val();
        $('.text-message').val('');
        $.post(url, {'to': to, 'text': text}, function(response){

            $('.text-message').val('');
            var $chat = $('ul#nav'),
                base_url = $('.div_conversation').data('base-url'),
                chat,
                new_chat = false;
            // Split timestamp into [ Y, M, D, h, m, s ]
            var t = response.chat.created_at.split(/[- :]/);
            // Apply each element to the Date function
            var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
            var date_message = 'Hoje '+$.formatDateTime('hh:ii', d);

            if ($chat.length == 0) {
                new_chat = true;
                $chat = $('<ul id="nav" class="list-unstyled" />');
            }

            chat_append($chat, response.chat, response, date_message);
            $('form[name=form-user-list-'+to+']').prependTo('.scroll-users');

            if (new_chat) {
                $('.div_conversation').html($chat);
            }

            $('.scroll-bottom').slimScroll({
                scrollTo: $('.scroll-bottom')[0].scrollHeight
            });
        });
    }

    function chat_append($chat, chat, response, date_message) {

        if (chat.sent_by == response.auth_id) {

            $chat.append('<li><div class="row row-conversation"><div class="'+(chat.col_size != null ? chat.col_size : 'col-md-9')+' media well-info well-chat pull-right"><div class="media-body"><div class="pull-right"><a href="javascript:void(0);"><strong>'+users_list[chat.sent_by]+'</strong></a></div><br /><p class="pull-right">'+chat.text+'</p><br /><div class="pull-left"><p class="text-muted">'+date_message+'</p></div><div></div></div></li>')

        } else {

            $chat.append('<li><div class="row row-conversation"><div class="'+(chat.col_size != null ? chat.col_size : 'col-md-9')+' media well-white well-chat"><div class="media-body"><div class="pull-left"><a href="javascript:void(0);"><strong>'+users_list[chat.sent_by]+'</strong></a></div><br /><p class="">'+chat.text+'</p><br /><div class="pull-right"><p class="text-muted">'+date_message+'</p></div><div></div></div></li>')

        }

    }

    $('.scroll-bottom').slimScroll({
        scrollTo: $('.scroll-bottom')[0].scrollHeight
    });
});
