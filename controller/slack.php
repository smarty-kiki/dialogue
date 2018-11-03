<?php

if_get('/slack/event', function ()
{
    $challenge = input_json('challenge');

    return [
        'challenge' => $challenge,
    ];
});
