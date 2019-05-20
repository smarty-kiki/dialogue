<?php

define('SLACK_CHANNEL_COIN', 'G6SKN128J');
define('SLACK_BOT_USER_ID', 'UJGQ7GKLK');
define('SLACK_ADMIN_USER_ID', 'DJWCW67KQ');

function slack_say_to_channel($channel, $message, $attachments = [])
{
    $config = config('slack');

    $data = [
        'token' => base64_decode($config['bot_token']),
        'channel' => $channel,
        'text' => $message,
    ];

    if ($attachments) {
        $data['attachments'] = json_encode($attachments);
    }

    /**kiki*/error_log(print_r($data, true)."\n", 3, '/tmp/slack.log');

    return remote_post('https://slack.com/api/chat.postMessage', $data, 10);
}
