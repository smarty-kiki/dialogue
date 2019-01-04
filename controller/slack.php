<?php

if_post('/slack/event', function ()
{
    $type = input_json('type');

    /**kiki*/error_log(strip_tags(print_r($type, true))."\n", 3, "/tmp/error_user.log");
    /**kiki*/error_log(print_r(input_post_raw(), true)."\n", 3, "/tmp/error_user.log");

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

                slack_say_to_smarty_ds($event['text']);

                break;
        }

        return [];
    }
});
