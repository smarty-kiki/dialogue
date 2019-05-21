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

        if (isset($event['bot_id'])) {
            return [];
        }

        switch ($event['type']) {

            case 'app_mention':

                $message = trim(str_replace('<@'.SLACK_BOT_USER_ID.'>', '', $event['text']));

                $reply_message = dialogue_push(dialogue_user_info(
                    $event['user'], $event['channel'], 'group', 'slack'
                ), $message);

                break;

            case 'message':

                if (! isset($event['subtype'])) {

                    if ($event['channel_type'] === 'im') {

                        $reply_message = dialogue_push(dialogue_user_info(
                            $event['user'], $event['channel'], $event['channel_type'], 'slack'
                        ), $event['text']);
                    }
                }

                break;
        }

        return [];
    }
});

if_get('/slack/test', function () {

    return slack_say_to_channel(SLACK_ADMIN_USER_ID, 'test');
});
