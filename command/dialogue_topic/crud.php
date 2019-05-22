<?php

function split_chinese_words($raw_string)
{/*{{{*/
    if (! trim($raw_string)) {
        return [];
    }

    $symbols = [' ', ' ', ',', '，', '、', '/', '\\', '|', '.'];
    $string_arr = (array) $raw_string;

    foreach ($symbols as $symbol) {

        $tmp = [];

        foreach ($string_arr as $string) {

            $string = trim($string);

            $tmp = array_merge($tmp, explode($symbol, $string));
        }

        $string_arr = array_unique($tmp);
    }

    return $string_arr;
}/*}}}*/

dialogue_topic([
    '创建 CRUD',
], function ($user_info, $content, $time) {/*{{{*/

    // 表名
    $message = dialogue_ask_and_wait($user_info, '发我一下名字');
    if (! $message) return;
    $entity_name = $message['content'];

    // 开始字段输入
    $message = dialogue_ask_and_wait($user_info, '好的，需要添加的字段名发我，一次全发给我吧');
    if (! $message) return;
    $column_name_str = $message['content'];
    $column_names = split_chinese_words($column_name_str);

    $entity_structs = [];
    foreach ($column_names as $column_name) {

        $entity_struct = [
            'display_name' => $column_name,
            'datatype' => 'varchar(1000)',
            'format' => '',
        ];

        // 枚举识别
        if (
            str_finish($column_name, '分类') || 
            str_finish($column_name, '类型') || 
            str_finish($column_name, '状态') || 
            str_finish($column_name, '阶段') || 
            str_finish($column_name, '种类')
        ) {
            $message = dialogue_ask_and_wait($user_info, '好的，'.$column_name.'是枚举吧？有哪些呢？一次发给我吧');
            if (! $message) return;
            $answer_str = $message['content'];

            if (! str_finish($answer_str, '不是')) {
                $entity_struct['format'] = split_chinese_words($answer_str);
            }
        }

        // 价格识别
        if (
            str_finish($column_name, '价格') || 
            str_finish($column_name, '价钱') || 
            str_finish($column_name, '金额') || 
            str_finish($column_name, '价') || 
            str_finish($column_name, '金') || 
            str_finish($column_name, '价值')
        ) {
            $message = dialogue_ask_and_wait($user_info, '好的，'.$column_name.'是金钱单位吧？');
            if (! $message) return;
            $answer_str = $message['content'];

            if (! str_finish($answer_str, '不是')) {
                $entity_struct['datatype'] = 'int(11)';
            }
        }

        // 时间识别
        if (
            str_finish($column_name, '日') || 
            str_finish($column_name, '日期') || 
            str_finish($column_name, '时间') || 
            str_finish($column_name, '时间点')
        ) {
            $message = dialogue_ask_and_wait($user_info, '好的，'.$column_name.'是日期 (精确到日的) 还是时间 (精确到秒的)？');
            if (! $message) return;
            $answer_str = $message['content'];

            if (
                str_finish($answer_str, '时间')
            ) {
                $entity_struct['datatype'] = 'datetime';
            }

            if (
                str_finish($answer_str, '日期')
            ) {
                $entity_struct['datatype'] = 'date';
            }
        }

        $entity_structs[] = $entity_struct;
    }

    $last_confirm_message = sprintf('好的，我复述一下，%s 有 %s %s 个字段。没问题吧？', $entity_name, implode('、', array_get($entity_structs, '*.display_name')), count($entity_structs));

    $message = dialogue_ask_and_wait($user_info, $last_confirm_message);
    $confirm = $message['content'];

    dialogue_say($user_info, '不管有没有问题，反正都已经创建好了，页面访问链接是 http://xxxx.xxx/crud/page/'.$entity_name.' , restful 接口是 http://xxxx.xxx/crud/api/'.$entity_name);
});/*}}}*/
