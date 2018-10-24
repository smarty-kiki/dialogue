<?php

define('BOSON_API_TOKEN', '0ezHUT7G.27930.jOs8hok6Uwin');

function boson_html_to_text($html)
{/*{{{*/
    $search = array("'<script[^>]*?>.*?</script>'si", "'<[\/\!]*?[^<>]*?>'si", "'([\r\n])[\s]+'", "'&(quot|#34|#034|#x22);'i", "'&(amp|#38|#038|#x26);'i", "'&(lt|#60|#060|#x3c);'i", "'&(gt|#62|#062|#x3e);'i", "'&(nbsp|#160|#xa0);'i", "'&(iexcl|#161);'i", "'&(cent|#162);'i", "'&(pound|#163);'i", "'&(copy|#169);'i", "'&(reg|#174);'i", "'&(deg|#176);'i", "'&(#39|#039|#x27);'", "'&(euro|#8364);'i", "'&a(uml|UML);'", "'&o(uml|UML);'", "'&u(uml|UML);'", "'&A(uml|UML);'", "'&O(uml|UML);'", "'&U(uml|UML);'", "'&szlig;'i", );
    $replace = array("", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), chr(174), chr(176), chr(39), chr(128), "ä", "ö", "ü", "Ä", "Ö", "Ü", "ß", );
    return preg_replace($search, $replace, $html);
}/*}}}*/

function boson_nlp_summary($text)
{/*{{{*/
    return remote_post_json('http://api.bosonnlp.com/summary/analysis', http_build_query([
        'title' => '',
        'content' => $text,
    ]), 10, 3, ['X-Token: '.BOSON_API_TOKEN]);
}/*}}}*/
