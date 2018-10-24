<?php

define('WECHAT_ID', 'kiki_assistant');
define('WECHAT_APP_ID', 'wx8306a0c3b06a06af');
define('WECHAT_APP_SECRET', '88c47a8c4977f2c2ff01c0c32ce7583e');
define('WECHAT_TOKEN', 'HelloWorld');
define('WECHAT_ENCODING_AES_KEY', 'YknZQ8opfJ9T8gktkmPzeh26FUgo74rpi3LJO4uQH9L');

define('WECHAT_API_DOMAIN', 'https://api.weixin.qq.com');

function _wechat_access_token()
{/*{{{*/
    static $cache_key = 'wechat_access_token';

    static $app_id = WECHAT_APP_ID;
    static $app_secret = WECHAT_APP_SECRET;

    static $access_token = null;

    if (is_null($access_token)) {

        $access_token = cache_get($cache_key);

        if (! $access_token) {

            $info = remote_get_json(WECHAT_API_DOMAIN."/cgi-bin/token?grant_type=client_credential&appid=$app_id&secret=$app_secret", 3, 3);

            if (array_key_exists('access_token', $info)) {

                $access_token = $info['access_token'];

                cache_set($cache_key, $access_token, $info['expires_in'] - 5);
            } else {
                throw new Exception($info['errmsg']);
            }
        }
    }

    return $access_token;
}/*}}}*/

function wechat_verify_url($msg_signature, $timestamp, $nonce, $echostr)
{/*{{{*/
    static $token = WECHAT_TOKEN;
    static $encoding_AES_key = WECHAT_ENCODING_AES_KEY;

    if (strlen($encoding_AES_key) != 43) {
        throw new Exception('IllegalAesKey');
    }

    $signature = wechat_sha1($token, $timestamp, $nonce);

    if ($signature != $msg_signature) {
        throw new Exception('ValidateSignatureError');
    }

    return $echostr;
}/*}}}*/

function wechat_receive_message($msg_signature, $timestamp, $nonce, $openid, $post_raw)
{/*{{{*/
    $message = simplexml_load_string($post_raw);

    switch ($message->MsgType) {
    case 'text':
        return [
            'type' => (string) $message->MsgType,
            'message' => [
                'user_id' => (string) $message->FromUserName,
                'content' => (string) $message->Content,
            ],
        ];
    case 'voice':
        return [
            'type' => 'text',
            'message' => [
                'user_id' => (string) $message->FromUserName,
                'content' => (string) $message->Recognition,
            ],
        ];
    default:
        return [
            'type' => (string) $message->MsgType,
        ];
    }
}/*}}}*/

function wechat_send_message($user_id, $content)
{/*{{{*/
    $access_token = _wechat_access_token();

    $res = remote_post_json(WECHAT_API_DOMAIN."/cgi-bin/message/custom/send?access_token=$access_token", json_encode([
        'touser' => $user_id,
        'msgtype' => "text",
        'text' => [
            'content' => $content,
        ],
    ]));

    return ! $res['errcode'];
}/*}}}*/

function wechat_reply_is_typing($user_id, bool $typing)
{/*{{{*/
    $access_token = _wechat_access_token();

    $command = $typing ? 'Typing': 'CancelTyping';

    $res = remote_post_json(WECHAT_API_DOMAIN."/cgi-bin/message/custom/typing?access_token=$access_token", json_encode([
        'touser' => $user_id,
        'command' => $command,
    ]), 3, 3);

    return ! $res['errcode'];
}/*}}}*/

function wechat_reply_message($user_id, $content)
{/*{{{*/
    static $from_user_id = WECHAT_ID;

    $timestamp = time();

    return "<xml>
        <ToUserName><![CDATA[".$user_id."]]></ToUserName>
        <FromUserName><![CDATA[".$from_user_id."]]></FromUserName>
        <CreateTime>".$timestamp."</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[".$content."]]></Content>
    </xml>";
}/*}}}*/

function wechat_sha1($token, $timestamp, $nonce)
{/*{{{*/
    try {
        $array = array($token, $timestamp, $nonce);
        sort($array, SORT_STRING);
        $str = implode($array);

        return sha1($str);
    } catch (Exception $e) {
        throw new Exception('ComputeSignatureError');;
    }
}/*}}}*/
