<?php

define('AMAP_WEB_KEY', '945269c6991ecbce458b8c6526fecac8');
define('AMAP_WEB_DOMAIN', 'https://m.amap.com');

function get_web_localsearch_url($longitude, $latitude, array $keywords)
{/*{{{*/
    return AMAP_WEB_DOMAIN."/around/?".http_build_query([
        'locations' => implode(',', [$longitude, $latitude]),
        'keywords'  => implode(',', $keywords),
        'defaultIndex' => 1,
        'searchRadius' => 3000,
        'key' => AMAP_WEB_KEY,
    ]);
}/*}}}*/
