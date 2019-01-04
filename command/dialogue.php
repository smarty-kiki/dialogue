<?php

command('dialogue:operator', '启动接线员', function ()
{/*{{{*/
    $config_key = command_paramater('config_key', 'default');
    $memory_limit = command_paramater('memory_limit', 1048576 * 128);

    ini_set('memory_limit', $memory_limit.'b');

    dialogue_send_action(function ($user_id, $message) {
        //business_wechat_send_message($user_id, [],[],$message);

        slack_say_to_smarty_coin($message);
    });

    dialogue_topic_miss_action(function ($user_id, $message) {

        try {

            throw new Exception();

        } catch (Exception $ex) {
            /**kiki*/error_log(print_r($ex->getTraceAsString(), true)."\n", 3, "/tmp/error_user.log");
        }

        dialogue_say($user_id, "不懂 '$message'");
    });

//    dialogue_topic_match_extension_action(function ($content, $topic) {
//        $score = baidu_ai_nlp_simnet($content, $topic);
//        return [
//            $score > 0.7,
//            [],
//        ];
//    });

    dialogue_watch($config_key, $memory_limit);
});/*}}}*/
