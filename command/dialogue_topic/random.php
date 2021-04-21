<?php

dialogue_topic('创建随机', function ($user_info, $content, $time) {/*{{{*/

    $items = [];

    while ($message = dialogue_ask_and_wait($user_info, '发我随机项吧', null, 120)) {

        $content = trim($message['content']);
        $items = [$content];

        foreach (["\n", ',', '，', '|', ' '] as $delimiter) {
            $tmp_items = [];
            foreach ($items as $item) {
                $tmp_res = explode($delimiter, $item);
                array_push($tmp_items, ...$tmp_res);
            }
            $items = $tmp_items;
        }

        $message = dialogue_ask_and_wait($user_info, "确认一下随机项:\n".implode("\n", $items));
        if (! $message) return;
        $content = trim($message['content']);

        if (false !== array_search($content, ['好了', '好的', 'ok', 'Ok', 'OK', '是的', '没问题', '可以', '确定'])) {
            break;
        }
    }
    if (! $items) return;

    $message = dialogue_ask_and_wait($user_info, '后续抽出来的还需要留在随机项里吗?');
    if (! $message) return;
    $content = $message['content'];
    $should_pop_item = true;
    if (false !== array_search($content, ['好了', '好的', 'ok', 'Ok', 'OK', '是的', '没问题', '可以', '确定'])) {
        $should_pop_item = false;
    }

    $ask = '好，可以开始随机了，这次随几个？';

    while (null !== ($message = dialogue_ask_and_wait($user_info, $ask, null, 120))) {

        $answer = trim($message['content']);

        $user_info = $message['user_info'];

        if (false !== array_search($answer, ['算了', '完事了', '完事', '好了', '不用了', '用完了'])) {
            dialogue_say($user_info, '嗯嗯');
            break;
        }

        $count = (int) $answer;

        if ($count > 0) {
            $random_keys = (array) array_rand($items, $count);
            $res_items = [];
            foreach ($random_keys as $key) {
                $res_items[] = $items[$key];
                if ($should_pop_item) {
                    unset($items[$key]);
                }
            }
            $ask = implode("\n", $res_items)."\n(直接回复数字进行下一次随机)";
        } else {
            $ask = '直接发数字吧~';
        }
    }
});/*}}}*/
