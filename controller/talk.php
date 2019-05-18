<?php

if_get('/talk', function ()
{
    $message =  dialogue_push(dialogue_user_info(
        input('user'), 0, 'http'
    ), (string) input('msg'), true);

    return $message['content'];
});
