<?php

define('WECHAT_APP_ID', 'AppIDwx72dd707ef1edfa1a');
define('WECHAT_APP_SECRET', 'd2ee36a4a0c2f82954894a36c0a4b031');
define('WECHAT_TOKEN', 'HelloWorld');
define('WECHAT_ENCODING_AES_KEY', '0o9iORmjwPszqCzjZA5dqO4aA4nif2OmeyKnjBvPeFd');

function wechat_verify_url($msg_signature, $timestamp, $nonce, $echostr)
{/*{{{*/
    static $token = WECHAT_TOKEN;
    static $encoding_AES_key = WECHAT_ENCODING_AES_KEY;

    if (strlen($encoding_AES_key) != 43) {
        throw new Exception('IllegalAesKey');
    }

    $signature = business_wechat_sha1($token, $timestamp, $nonce, $echostr);

    if ($signature != $msg_signature) {
        throw new Exception('ValidateSignatureError');
    }

    return $signature;
}/*}}}*/
