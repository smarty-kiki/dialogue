<?php

if_get('/work/receive', function ()
{
    list($msg_signature, $timestamp, $nonce, $echostr) = input_list('msg_signature', 'timestamp', 'nonce', 'echostr');

    return business_wechat_verify_url($msg_signature, $timestamp, $nonce, $echostr);
});

if_post('/work/receive', function ()
{
    list($msg_signature, $timestamp, $nonce) = input_list('msg_signature', 'timestamp', 'nonce');

    $message_info = business_wechat_receive_message($msg_signature, $timestamp, $nonce, input_post_raw());

    $type = $message_info['type'];

    $message = $message_info['message'];

    switch ($type) {

    case 'text':

        $reply_message = dialogue_push(dialogue_user_info(
            $message['user_id'], 0, 'business_wechat'
        ), $message['content'], true);

        list($user_id) = list_dialogue_user_info($reply_message['user_info']);

        return business_wechat_reply_message($user_id, $reply_message['content']);
    }
});
