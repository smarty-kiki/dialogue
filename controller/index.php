<?php

if_get('/', function ()
{
    $html = remote_get('https://daily.zhihu.com/story/9697094');

    $text = boson_html_to_text($html);

    $res = boson_nlp_summary($text);

    var_dump($res);exit;

    return 'hello world';
});
