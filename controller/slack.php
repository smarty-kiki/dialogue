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

        otherwise(! isset($event['bot_id']));

        /**kiki*/error_log(print_r($event, true)."\n", 3, '/tmp/error_user.log');

        switch ($event['type']) {

            case 'app_mention':

                otherwise($event['channel_type'] === 'group');

                $message = str_replace('<@UF85D4HEK> ', '', $event['text']);

                $reply_message = dialogue_push($event['user'], $message);

                break;
        }

        return [];
    }
});
