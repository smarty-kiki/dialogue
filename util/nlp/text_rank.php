<?php

define('NLP_APP_KEY', '25224800');
define('NLP_APP_SECRET', '29ef121a79bae9c762e8626d6a0c856f');
define('NLP_APP_CODE', 'ca42dc926d8a498db18926928a3e9f2a');

function text_rank_get_summary($text)
{/*{{{*/
    $res = remote_post_json('http://summary.market.alicloudapi.com/clouds/nlp/summary', json([
        'document' => $text,
        'maxLength' => mb_strlen($text) * 0.2,
    ]), 10, 3, ['Authorization:APPCODE '.NLP_APP_CODE]);

    return $res['data'];
}/*}}}*/
