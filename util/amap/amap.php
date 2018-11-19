<?php

include __DIR__.'/../../frame/function.php';

define('AMAP_WEB_KEY', '945269c6991ecbce458b8c6526fecac8');
define('AMAP_WEB_DOMAIN', 'https://m.amap.com');

define('AMAP_SERVICE_KEY', '006ccae047fe5ac760554647864f5d2e');
define('AMAP_SERVICE_DEMAIN', 'https://restapi.amap.com');

function get_web_localsearch_url($longitude, $latitude, array $keywords)
{/*{{{*/
    return AMAP_WEB_DOMAIN."/around/?".http_build_query([
        'locations'     =>  implode(',',   [$longitude,  $latitude]),
        'keywords'      =>  implode(',',   $keywords),
        'defaultIndex'  =>  1,
        'searchRadius'  =>  3000,
        'key'           =>  AMAP_WEB_KEY,
    ]);
}/*}}}*/

function get_regeo_info($longitude, $latitude, array $keywords)
{/*{{{*/
    return remote_get_json(AMAP_SERVICE_DEMAIN.'/v3/geocode/regeo?'.http_build_query([
        'key'         =>  AMAP_SERVICE_KEY,
        'location'    =>  implode(',',       [$longitude,  $latitude]),
        'poitype'     =>  implode('|',       $keywords),
        'radius'      =>  3000,
        'extensions'  =>  'all',
        'batch'       =>  'true',
        'roadlevel'   =>  0,
    ]));
}/*}}}*/
