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

    dialogue_say($user_id, get_web_localsearch_url($location_info['longitude'], $location_info['latitude'], [
        '美食', '超市', '电影院', '公交站', '地铁站'
    ]));

});/*}}}*/
