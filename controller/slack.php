<?php

if_post('/slack/event', function ()
{
    $type = input_json('type');

    if ($type === 'url_verification') {

        $challenge = input_json('challenge');

        return [
            'challenge' => $challenge,
        ];

    } elseif ($type === 'event_callback') {

        $event = input_json('event');

        /**kiki*/error_log(strip_tags(print_r($event, true))."\n", 3, "/tmp/error_user.log");

        otherwise(! isset($event['bot_id']));

        switch ($event['type']) {
            case 'message':

                $reply_message = dialogue_push('slack_smarty_coin', $event['text']);

                break;
        }

        return [];
    }
});
