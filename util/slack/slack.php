<?php

function slack_say_to_smarty_ds($message)
{
    return remote_post('https://hooks.slack.com/services/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', json_encode([
        'text' => $message
    ]), 10);
}
