<?php

function dialogue_user_info($user_id, $channel_id, $type, $source)
{/*{{{*/
    return [
        'user_id' => $user_id,
        'channel_id' => $channel_id,
        'type' => $type,
        'source' => $source,
    ];
}/*}}}*/

function list_dialogue_user_info($user_info)
{/*{{{*/
    return [
        $user_info['user_id'],
        $user_info['channel_id'],
        $user_info['type'],
        $user_info['source'],
    ];
}/*}}}*/

dialogue_user_tube_string_action(function ($user_info) {

    list($user_id, $channel_id, $type, $source) = list_dialogue_user_info($user_info);

    if ($type === 'im') {
        return $source.'_u_'.$user_id;
    } else {
        return $source.'_c_'.$channel_id;
    }
});
