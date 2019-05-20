<?php

dialogue_topic([
    '测试正则',
    '正则测试',
    '正则实验',
    '正则调试',
    '正则验证',
], function ($user_info, $content, $time) {/*{{{*/

    $pattern = $subject = null;

    $ask = '好，发我吧';

    while (null !== ($message = dialogue_ask_and_wait($user_info, $ask, null, 120))) {

        $answer = trim($message['content']);

        $user_info = $message['user_info'];

        if (false !== array_search($answer, ['谢了', '谢谢', '完事', '好了', '好的'])) {
            dialogue_say($user_info, '嗯嗯');
            break;
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

dialogue_topic(['常用正则'], function ($user_info, $content, $time) {/*{{{*/

    dialogue_say($user_info,
        "自然数  /^(0|[1-9][0-9]*)$/\n"
        ."有理数  /^(\-|\+)?\d+(\.\d+)?$/\n"
        ."3-20位任意字符  /^.{3,20}$/\n"
        ."邮箱  /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/\n"
        ."日期  /^\d{4}-\d{1,2}-\d{1,2}&/\n"
    );

});/*}}}*/
