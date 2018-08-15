<?php

if_get('/wechat/receive', function ()
{
    list($msg_signature, $timestamp, $nonce, $echostr) = input_list('msg_signature', 'timestamp', 'nonce', 'echostr');

    return wechat_verify_url($msg_signature, $timestamp, $nonce, $echostr);
});

if_post('/wechat/receive', function ()
{
});
