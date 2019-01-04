<?php

if_post('/slack/event', function ()
{
    $type = input_json('type');

    /**kiki*/error_log(strip_tags(print_r($type, true))."\n", 3, "/tmp/error_user.log");
    /**kiki*/error_log(print_r(input_post_raw(), true)."\n", 3, "/tmp/error_user.log");

    switch ($type) {
    case 'url_verification':

        $challenge = input_json('challenge');

        return [
            'challenge' => $challenge,
        ];
        break;

    case 'message':

        $text = input_json('text');

        slack_say_to_smarty_ds($text);

        return [];
        break;

    default:

        break;
    }

});
