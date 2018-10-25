<?php

dialogue_topic([
    '获取摘要',
], function ($user_id, $content, $time) {/*{{{*/

    $url = dialogue_ask_and_wait($user_id, '好的，发我 URL 吧', '/^((ht|f)tps?):\/\/([\w\-]+(\.[\w\-]+)*\/)*[\w\-]+(\.[\w\-]+)*\/?(\?([\w\-\.,@?^=%&:\/~\+#]*)+)?/');

    $html = remote_get($url);

    if ($html) {

        $text = nlp_html_to_text($html);

        $res = nlp_summary($text);

        dialogue_say($user_id, $res);
    } else {
        dialogue_say($user_id, '这链接我访问不了');
    }
});/*}}}*/
