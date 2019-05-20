<?php

if_get('/wechat/receive', function ()
{
    list($signature, $timestamp, $nonce, $echostr) = input_list('signature', 'timestamp', 'nonce', 'echostr');

    return wechat_verify_url($signature, $timestamp, $nonce, $echostr);
});

if_post('/wechat/receive', function ()
{
    list($signature, $timestamp, $nonce, $openid) = input_list('signature', 'timestamp', 'nonce', 'openid');

    $message_info = wechat_receive_message($signature, $timestamp, $nonce, $openid, input_post_raw());

    $type = $message_info['type'];

    $message = $message_info['message'];

    $from_user_id = $message['user_id'];

    switch ($type) {

    case 'text':

        wechat_reply_is_typing($from_user_id, true);

        $reply_message = dialogue_push(dialogue_user_info(
            $from_user_id, 0, 'im', 'wechat'
        ), $message['content'], true);

        list($user_id) = list_dialogue_user_info($reply_message['user_info']);

        $reply_message_string = wechat_reply_message($user_id, $reply_message['content']);

        wechat_reply_is_typing($from_user_id, false);

        return $reply_message_string;

    case 'voice':

        wechat_reply_is_typing($from_user_id, true);

        $reply_message = dialogue_push(dialogue_user_info(
            $from_user_id, 0, 'im', 'wechat'
        ), wechat_rtrim_voice_text($message['content']), true);

        list($user_id) = list_dialogue_user_info($reply_message['user_info']);

        $reply_message_string = wechat_reply_message($user_id, $reply_message['content']);

        wechat_reply_is_typing($from_user_id, false);

        return $reply_message_string;

    case 'location':

        wechat_reply_is_typing($from_user_id, true);

        $received_message_json = json($message);

        $reply_message = dialogue_push(dialogue_user_info(
            $from_user_id, 0, 'im', 'wechat'
        ), $received_message_json, true);

        list($user_id) = list_dialogue_user_info($reply_message['user_info']);

        $reply_message_string = wechat_reply_message($user_id, $reply_message['content']);

        wechat_reply_is_typing($from_user_id, false);

        return $reply_message_string;

    case 'link':

        wechat_reply_is_typing($from_user_id, true);

        $reply_message = dialogue_push(dialogue_user_info(
            $from_user_id, 0, 'im', 'wechat'
        ), $message['url'], true);

        list($user_id) = list_dialogue_user_info($reply_message['user_info']);

        $reply_message_string = wechat_reply_message($user_id, $reply_message['content']);

        wechat_reply_is_typing($from_user_id, false);

        return $reply_message_string;
    }
});
