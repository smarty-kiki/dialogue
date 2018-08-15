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

    switch ($type) {
    case 'text':
        $reply_message = dialogue_push($message['user_id'], $message['content'], true);

        return wechat_reply_message($reply_message['user_id'], $reply_message['content']);
    }

});
