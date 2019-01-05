<?php

if_post('/slack/event', function ()
{
    /**kiki*/error_log(strip_tags(print_r(json_decode(input_post_raw(), true), true))."\n", 3, "/tmp/error_user.log");

    $type = input_json('type');

    if ($type === 'url_verification') {

        $challenge = input_json('challenge');

        return [
            'challenge' => $challenge,
        ];

    } elseif ($type === 'event_callback') {

        $event = input_json('event');

        otherwise(! isset($event['bot_id']));

        switch ($event['type']) {
            case 'message':

                $reply_message = dialogue_push('slack_smarty_coin', $event['text']);

                break;
        }

        return [];
    }
});
