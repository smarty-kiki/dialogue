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

        switch ($event['type']) {
            case 'message':

                $reply_message = dialogue_push('slack_smarty_coin', $event['text']);

                break;
        }

        return [];
    }
});
