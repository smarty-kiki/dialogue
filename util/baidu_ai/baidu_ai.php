<?php

define('BAIDU_AI_API_KEY', 'q4s01fPlWPsB0EtRbgtmUCKH');
define('BAIDU_AI_SECRET_KEY', 'w8AN8M2xj8XYcroWPPO2Z47mKGcQooxT');

function _baidu_ai_access_token()
{/*{{{*/
    static $cache_key = 'baidu_ai_access_token';

    static $client_id = BAIDU_AI_API_KEY;
    static $client_secret = BAIDU_AI_SECRET_KEY;

    static $access_token = null;

    if (is_null($access_token)) {

        $access_token = cache_get($cache_key);

        if (! $access_token) {

            $info = remote_get_json("https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret");

            if (array_key_exists('access_token', $info)) {

                $access_token = $info['access_token'];

                cache_set($cache_key, $access_token, $info['expires_in'] - 5);
            } else {
                throw new Exception($info['error_description']);
            }
        }
    }

    return $access_token;
}/*}}}*/

/**
 * 短文本相似度接口
 * 
 * @access protected
 * @return void
 */
function baidu_ai_nlp_simnet($text1, $text2)
{/*{{{*/
    $access_token = _baidu_ai_access_token();

    $post = json([
        'text_1' => $text1,
        'text_2' => $text2,
        'model' => 'BOW', // 'BOW', 'CNN', 'GRNN'
    ]);

    return remote_post_json('https://aip.baidubce.com/rpc/2.0/nlp/v2/simnet?charset=UTF-8&access_token='.$access_token, $post);
}/*}}}*/

function baidu_ai_nlp_word_emb_sim($word1, $word2)
{/*{{{*/
    $access_token = _baidu_ai_access_token();

    $post = json([
        'word_1' => $word1,
        'word_2' => $word2,
    ]);

    return remote_post_json('https://aip.baidubce.com/rpc/2.0/nlp/v2/word_emb_sim?charset=UTF-8&access_token='.$access_token, $post);
}/*}}}*/

function baidu_ai_nlp_depparser($text, $mode = 1)
{/*{{{*/
    $access_token = _baidu_ai_access_token();

    $post = json([
        'text' => $text,
        'mode' => $mode,
    ]);

    return remote_post_json('https://aip.baidubce.com/rpc/2.0/nlp/v1/depparser?charset=UTF-8&access_token='.$access_token, $post);
}/*}}}*/
