<?php

define('NLP_APP_KEY', '25224800');
define('NLP_APP_SECRET', '29ef121a79bae9c762e8626d6a0c856f');
define('NLP_APP_CODE', 'ca42dc926d8a498db18926928a3e9f2a');

function nlp_html_to_text($html)
{/*{{{*/
    $search = array("'<script[^>]*?>.*?</script>'si", "'<[\/\!]*?[^<>]*?>'si", "'([\r\n])[\s]+'", "'&(quot|#34|#034|#x22);'i", "'&(amp|#38|#038|#x26);'i", "'&(lt|#60|#060|#x3c);'i", "'&(gt|#62|#062|#x3e);'i", "'&(nbsp|#160|#xa0);'i", "'&(iexcl|#161);'i", "'&(cent|#162);'i", "'&(pound|#163);'i", "'&(copy|#169);'i", "'&(reg|#174);'i", "'&(deg|#176);'i", "'&(#39|#039|#x27);'", "'&(euro|#8364);'i", "'&a(uml|UML);'", "'&o(uml|UML);'", "'&u(uml|UML);'", "'&A(uml|UML);'", "'&O(uml|UML);'", "'&U(uml|UML);'", "'&szlig;'i", );
    $replace = array("", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), chr(174), chr(176), chr(39), chr(128), "ä", "ö", "ü", "Ä", "Ö", "Ü", "ß", );

    return mb_convert_encoding(preg_replace($search, $replace, $html), 'utf-8');
}/*}}}*/

function nlp_summary($text, $max_length = 120)
{/*{{{*/

    return remote_post_json('http://summary.market.alicloudapi.com/clouds/nlp/summary', json([
        'document' => $text,
        'maxLength' => $max_length,
    ]), 10, 3, ['Authorization:APPCODE '.NLP_APP_CODE]);
}/*}}}*/
