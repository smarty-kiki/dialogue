<?php

if_post('/slack/event', function ()
{
    $type = input_json('type');

    switch ($type) {
    case 'url_verification':

        $challenge = input_json('challenge');

        return [
            'challenge' => $challenge,
        ];
        break;

    case 'message':

        $text = input_json('text');

        /**kiki*/error_log(strip_tags(print_r($text, true))."\n", 3, "/tmp/error_user.log");

        slack_say_to_smarty_ds($text);

        return [];
        break;

    default:

        break;
    }

});
