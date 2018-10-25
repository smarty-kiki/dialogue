<?php

function text_rank_get_summary($text)
{/*{{{*/
    $res_html = remote_post('https://cws.9sep.org/textrank', http_build_query([
        'text' => $text,
        'topk' => 3,
    ]), 5, 3);

    if ($res_html) {

        $res_html = mb_convert_encoding($res_html, 'utf-8');

        $dom = str_get_html($res_html);

        return $dom->find('[name=excerpt]')[0]->innertext;
    }
}/*}}}*/
