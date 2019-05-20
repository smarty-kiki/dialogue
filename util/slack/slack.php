<?php

function slack_say_to_channel($channel, $message, $attachments = [])
{
    $config = config('slack');

    $data = [
        'token' => $config['bot_token'],
        'channel' => $channel,
        'text' => $message,
    ];

    if ($attachments) {
        $data['attachments'] = json_encode($attachments);
    }

    return remote_post('https://slack.com/api/chat.postMessage', $data, 10);
}
