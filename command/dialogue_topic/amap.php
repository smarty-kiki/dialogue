<?php

dialogue_topic([
    '看一下这块位置怎么样',
    '看一下租这儿怎么样',
    '看一下附近怎么样',
    '看一下这位置好不好'
], function ($user_id, $content, $time) {/*{{{*/

    $location_info = null;

    $message = '好的，发我个定位';

    do {

        $location_info_json = dialogue_ask_and_wait($user_id, $message, null, 60);

        $location_info = json_decode($location_info_json, true);

        $message = '这个不是定位哦，发我个定位';

    } while (! $location_info);

    $localsearch_url = get_web_localsearch_url($location_info['longitude'], $location_info['latitude'], [
        '美食', '超市', '电影院', '公交站', '地铁站',
    ]);

    $regeo_info = get_regeo_info($location_info['longitude'], $location_info['latitude'], [
        '美食', '超市', '电影院', '公交站', '地铁站',
    ]);

    $poi_info = [];

    foreach ($regeo_info['regeocodes'][0]['pois'] as $poi) {

        $tmp_poi_types = explode(';', $poi['type']);

        $poi_info[$tmp_poi_types[1]] += 1;
    }

    $poi_str = '';

    foreach ($poi_info as $type => $count) {
        $poi_str .= "$type $count 个, ";
    }

    $reply_message = $regeo_info['regeocodes'][0]['formatted_address'].', 周边有'.$poi_str.'<a href="'.$localsearch_url.'">点这里查看</a>';

    dialogue_say($user_id, $reply_message);

});/*}}}*/
