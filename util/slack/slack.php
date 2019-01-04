<?php

function slack_say_to_smarty_ds($message)
{
    return remote_post('https://hooks.slack.com/services/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', json_encode([
        'text' => $message
    ]), 10);
}
