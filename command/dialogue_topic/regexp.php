<?php

dialogue_topic([
    '测试正则',
], function ($user_id, $content, $time) {/*{{{*/

    $pattern = $subject = null;

    $ask = '好，发我吧';

    while ($answer = dialogue_ask_and_wait($user_id, $ask, null, 120)) {

        if (false !== array_search($answer, ['谢了', '谢谢', '完事', '好了', '好的'])) {
            return;
        }

        if (starts_with($answer, '/')) {
            $pattern = $answer;
        } else {
            $subject = $answer;
        }

        if (is_null($pattern)) {
            $ask = '收到['.$answer.']，发我一下正则吧';
        } elseif (is_null($subject)) {
            $ask = '收到['.$answer.']，发我一下测试值吧';
        } else {
            $matches = [];
            if (false === preg_match_all($pattern, $subject, $matches)) {
                $ask = '正则['.$pattern.']有问题';
            } else {
                ob_start();
                var_dump($matches);
                $ask = ob_get_contents();
                ob_end_clean();
            }
        }
    }

});/*}}}*/
