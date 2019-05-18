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

                $message = str_replace('<@UF85D4HEK> ', '', $event['text']);

                $reply_message = dialogue_push(dialogue_user_info(
                    $event['user'], 0, 'slack'
                ), $message);

                break;
        }

        return [];
    }
});
