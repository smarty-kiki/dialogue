<?php

if_post('/slack/event', function ()
{
    $challenge = input_json('challenge');

    return [
        'challenge' => $challenge,
    ];
});
