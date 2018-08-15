<?php

if_get('/wechat/receive', function ()
{
    list($signature, $timestamp, $nonce, $echostr) = input_list('signature', 'timestamp', 'nonce', 'echostr');

    return wechat_verify_url($signature, $timestamp, $nonce, $echostr);
});

if_post('/wechat/receive', function ()
{
});
