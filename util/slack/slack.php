<?php

function slack_say_to_smarty_ds($message)
{
    return remote_post('https://hooks.slack.com/services/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', json_encode([
        'text' => $message
    ]), 10);
}
