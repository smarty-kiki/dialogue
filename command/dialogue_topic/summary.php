<?php

dialogue_topic([
    '获取摘要',
    '帮我摘要一下',
    '摘要这个文章',
], function ($user_info, $content, $time) {/*{{{*/

    $message = dialogue_ask_and_wait($user_info, '好的，发我 URL 吧', '/^((ht|f)tps?):\/\/([\w\-]+(\.[\w\-]+)*\/)*[\w\-]+(\.[\w\-]+)*\/?(\?([\w\-\.,@?^=%&:\/~\+#]*)+)?/');

    $html = remote_get($message['content']);

    if ($html) {

        $text = html_to_text($html);

        $res = text_rank_get_summary($text);

        dialogue_say($user_info, $res);
    } else {
        dialogue_say($user_info, '这链接我访问不了');
    }
});/*}}}*/
