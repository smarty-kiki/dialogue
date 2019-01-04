<?php

function slack_say_to_smarty_ds($message)
{
    return remote_post('https://hooks.slack.com/services/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', json_encode([
        'text' => $message
    ]), 10);
}
