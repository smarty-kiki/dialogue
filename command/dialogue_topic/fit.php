<?php

dialogue_topic([
    '*燃脂*',
    '*心率*',
], function ($user_info, $content, $time) {/*{{{*/

    $birthday = '1988-01-01';
    $max_heart_rate = 220;
    $burn_rate_max = 0.85;
    $burn_rate_min = 0.65;

    $age = datetime_diff(datetime(), $birthday, '%y');
    $self_max_heart_rate = 220 - $age;
    $burn_heart_rate_max = round($self_max_heart_rate * $burn_rate_max);
    $burn_heart_rate_min = round($self_max_heart_rate * $burn_rate_min);

    dialogue_say($user_info, "您的燃脂心率范围为 $burn_heart_rate_min ~ $burn_heart_rate_max");

});/*}}}*/
