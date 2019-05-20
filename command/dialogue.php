<?php

command('dialogue:operator', '启动接线员', function ()
{/*{{{*/
    $config_key = command_paramater('config_key', 'default');
    $memory_limit = command_paramater('memory_limit', 1048576 * 128);

    ini_set('memory_limit', $memory_limit.'b');

    dialogue_async_send_action(function ($user_info, $message) {/*{{{*/

        list($user_id, $channel_id, $type, $source) = list_dialogue_user_info($user_info);

        switch ($source) {

            case 'business_wechat':

                business_wechat_send_message($user_id, [],[],$message);
                break;

            case 'slack':

                    /**kiki*/error_log(print_r($user_id, true)."\n", 3, '/tmp/error_user.log');
                    /**kiki*/error_log(print_r($channel_id, true)."\n", 3, '/tmp/error_user.log');
                    /**kiki*/error_log(print_r($type, true)."\n", 3, '/tmp/error_user.log');
                    /**kiki*/error_log(print_r($source, true)."\n", 3, '/tmp/error_user.log');
                    /**kiki*/error_log(print_r($message, true)."\n", 3, '/tmp/error_user.log');

                if ($type === 'im') {
                    slack_say_to_channel($channel_id, $message);
                } elseif ($type === 'channel' || $type === 'group') {
                    slack_say_to_channel($channel_id, "<@$user_id> ".$message);
                }
                break;

            default:

                log_notice($source.' to '.$user_id.': '.$message);
                break;
        }
    });/*}}}*/

    dialogue_topic_miss_action(function ($user_id, $message) {/*{{{*/

        dialogue_say($user_id, "不懂 '$message'");
    });/*}}}*/

    dialogue_topic_match_extension_action(function ($content, $topics) {/*{{{*/

        foreach ($topics as $topic) {

            log_notice($content.' - '.$topic);
            if ($topic === '测试正则' && $content === 'test') {
                return [true, []];
            }
        }

        return [false, []];
    });/*}}}*/

    dialogue_watch($config_key, $memory_limit);
});/*}}}*/
