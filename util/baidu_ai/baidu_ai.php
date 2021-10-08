<?php

define('BAIDU_AI_API_KEY', 'q4s01fPlWPsB0EtRbgtmUCKH');
define('BAIDU_AI_SECRET_KEY', 'w8AN8M2xj8XYcroWPPO2Z47mKGcQooxT');

function _baidu_ai_post($url, $data)
{/*{{{*/
    static $cached = [];

    $identifier = $url . serialize($data);

    if (isset($cached[$identifier])) {

        return $cached[$identifier];
    }

    $res = http_json([
        'url' => $url,
        'data' => $data,
    ]);

    log_module('baidu_ai', $url);
    log_module('baidu_ai', json($data));
    log_module('baidu_ai', json($res));

    return $cached[$identifier] = $res;
}/*}}}*/

/**
 * 获取 baidu ai 平台 access_token
 *
 * @access protected
 * @return void
 */
function _baidu_ai_access_token()
{/*{{{*/
    static $cache_key = 'baidu_ai_access_token';

    static $client_id = BAIDU_AI_API_KEY;
    static $client_secret = BAIDU_AI_SECRET_KEY;

    static $access_token = null;

    if (is_null($access_token)) {

        $access_token = cache_get($cache_key);

        if (! $access_token) {

            $info = http_json("https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret");

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
 */
function baidu_ai_nlp_simnet($text1, $text2)
{/*{{{*/
    $access_token = _baidu_ai_access_token();

    $post = json([
        'text_1' => $text1,
        'text_2' => $text2,
        'model' => 'BOW', // 'BOW', 'CNN', 'GRNN'
    ]);

    return _baidu_ai_post('https://aip.baidubce.com/rpc/2.0/nlp/v2/simnet?charset=UTF-8&access_token='.$access_token, $post);
}/*}}}*/

/**
 * 词义相似度接口
 *
 */
function baidu_ai_nlp_word_emb_sim($word1, $word2)
{/*{{{*/
    $access_token = _baidu_ai_access_token();

    $post = json([
        'word_1' => $word1,
        'word_2' => $word2,
    ]);

    return _baidu_ai_post('https://aip.baidubce.com/rpc/2.0/nlp/v2/word_emb_sim?charset=UTF-8&access_token='.$access_token, $post);
}/*}}}*/

/**
 * 依存句法分析接口
 *
 */
function baidu_ai_nlp_depparser($text, $mode = 1, $with_str = false)
{/*{{{*/
    static $postag_maps = [
        'a' =>  '形容词',
        'ad' => '副形词',
        'ag' => '形语素',
        'an' => '名形词',
        'b' =>  '区别词',
        'c' =>  '连词',
        'd' =>  '副词',
        'dg' => '副语素',
        'e' =>  '叹词',
        'f' =>  '方位词',
        'g' =>  '语素',
        'h' =>  '前接成分',
        'i' =>  '成语',
        'j' =>  '简称略语',
        'k' =>  '后接成分',
        'l' =>  '习用语',
        'm' =>  '数词',
        'n' =>  '名词',
        'ng' => '名语素',
        'nr' => '人名',
        'ns' => '地名',
        'nt' => '机构团体',
        'nz' => '其他专名',
        'o' =>  '拟声词',
        'p' =>  '介词',
        'q' =>  '量词',
        'r' =>  '代词',
        's' =>  '处所词',
        't' =>  '时间词',
        'tg' => '时语素',
        'u' =>  '助词',
        'un' => '未知词',
        'v' =>  '动词',
        'vd' => '副动词',
        'vg' => '动语素',
        'vn' => '名动词',
        'w' =>  '标点符号',
        'x' =>  '非语素字',
        'y' =>  '语气词',
        'z' =>  '状态词',
    ];

    static $deprel_maps = [
        'ATT' => '定中关系',
        'QUN' => '数量关系',
        'COO' => '并列关系',
        'APP' => '同位关系',
        'ADJ' => '附加关系',
        'VOB' => '动宾关系',
        'POB' => '介宾关系',
        'SBV' => '主谓关系',
        'SIM' => '比拟关系',
        'TMP' => '时间关系',
        'LOC' => '处所关系',
        'DE'  => '"的"字结构',
        'DI'  => '"地"字结构',
        'DEI' => '"得"字结构',
        'SUO' => '"所"字结构',
        'BA'  => '"把"字结构',
        'BEI' => '"被"字结构',
        'ADV' => '状中结构',
        'CMP' => '动补结构',
        'DBL' => '兼语结构',
        'CNJ' => '关联词',
        'CS'  => '关联结构',
        'MT'  => '语态结构',
        'VV'  => '连谓结构',
        'HED' => '核心',
        'FOB' => '前置宾语',
        'DOB' => '双宾语',
        'TOP' => '主题',
        'IS'  => '独立结构',
        'IC'  => '独立分句',
        'DC'  => '依存分句',
        'VNV' => '叠词关系',
        'YGC' => '一个词',
        'WP'  => '标点',
    ];

    $access_token = _baidu_ai_access_token();

    $post = json([
        'text' => $text,
        'mode' => $mode,
    ]);

    $res = _baidu_ai_post('https://aip.baidubce.com/rpc/2.0/nlp/v1/depparser?charset=UTF-8&access_token='.$access_token, $post);

    if ($res['items']) {

        if ($with_str) {

            foreach ($res['items'] as &$item) {

                if ($item['head']) {
                    $item['head_info'] = &$res['items'][$item['head'] - 1];
                }

                $item['postag_str'] = $postag_maps[$item['postag']];
                $item['deprel_str'] = $deprel_maps[$item['deprel']];
            }
        }

        return $res;
    }

    return [];
}/*}}}*/

/**
 * 词法分析接口
 *
 */
function baidu_ai_nlp_lexer($text, $with_str = false)
{/*{{{*/
    static $pos_maps = [
        'n' => '普通名词',
        'f' => '方位名词',
        's' => '处所名词',
        't' => '时间名词',
        'nr' => '人名',
        'ns' => '地名',
        'nt' => '机构团体名',
        'nw' => '作品名',
        'nz' => '其他专名',
        'v' => '普通动词',
        'vd' => '动副词',
        'vn' => '名动词',
        'a' => '形容词',
        'ad' => '副形词',
        'an' => '名形词',
        'd' => '副词',
        'm' => '数量词',
        'q' => '量词',
        'r' => '代词',
        'p' => '介词',
        'c' => '连词',
        'u' => '助词',
        'xc' => '其他虚词',
        'w' => '标点符号',
    ];

    static $ne_maps = [
        'PER' => '人名',
        'LOC' => '地名',
        'ORG' => '机构名',
        'TIME' => '时间',
    ];

    $access_token = _baidu_ai_access_token();

    $post = json([
        'text' => $text,
    ]);

    $res = _baidu_ai_post('https://aip.baidubce.com/rpc/2.0/nlp/v1/lexer?charset=UTF-8&access_token='.$access_token, $post);

    if (isset($res['items'])) {

        if ($with_str) {

            foreach ($res['items'] as &$item) {

                if ($item['ne']) {

                    $item['pos_str'] = '专有名词-'.$ne_maps[$item['ne']];
                }

                if ($item['pos']) {

                    $item['pos_str'] = $pos_maps[$item['pos']];
                }
            }
        }

        return $res;
    }

    return [];
}/*}}}*/

/**
 * 文章标签接口
 *
 */
function baidu_ai_nlp_tag($title, $content)
{/*{{{*/
    $access_token = _baidu_ai_access_token();

    $post = json([
        'title' => $title,
        'content' => $content,
    ]);

    $res = _baidu_ai_post('https://aip.baidubce.com/rpc/2.0/nlp/v1/keyword?charset=UTF-8&access_token='.$access_token, $post);

    if (isset($res['items'])) {

        return $res['items'];
    }

    return [];
}/*}}}*/

/**
 * 文章摘要接口
 *
 */
function baidu_ai_nlp_summary($title, $content, $max_summary_len = 300)
{/*{{{*/
    $access_token = _baidu_ai_access_token();

    $post = json([
        'title' => $title,
        'content' => $content,
        'max_summary_len' => $max_summary_len,
    ]);

    $res = _baidu_ai_post('https://aip.baidubce.com/rpc/2.0/nlp/v1/news_summary?charset=UTF-8&access_token='.$access_token, $post);

    if (isset($res['summary'])) {

        return $res['summary'];
    }

    return [];
}/*}}}*/

function nlp_v_n_checker($verbs, $nouns)
{/*{{{*/
    if ($verbs !== '*') {
        $verbs = array_flip((array) $verbs);
    }

    if ($nouns !== '*') {
        $nouns = array_flip((array) $nouns);
    }

    static $verb_pos_maps = [
        'v' => '普通动词',
        'vd' => '动副词',
        'vn' => '名动词',
    ];

    static $noun_pos_maps = [
        'n' => '普通名词',
        'f' => '方位名词',
        's' => '处所名词',
        't' => '时间名词',
        'nr' => '人名',
        'ns' => '地名',
        'nt' => '机构团体名',
        'nw' => '作品名',
        'nz' => '其他专名',
        'vn' => '名动词',
        'an' => '名形词',
    ];

    static $ne_maps = [
        'PER' => '人名',
        'LOC' => '地名',
        'ORG' => '机构名',
        'TIME' => '时间',
    ];

    return function ($content) use ($verbs, $nouns, $verb_pos_maps, $noun_pos_maps, $ne_maps) {

        $res = baidu_ai_nlp_lexer($content);

        if (isset($res['items'])) {

            $verb_matched = false;
            $noun_matched = false;
            $catched = [];

            foreach ($res['items'] as $item) {

                if ($verb_matched === false && $item['pos'] && isset($verb_pos_maps[$item['pos']])) {

                    if (isset($verbs[$item['item']])) {

                        $verb_matched = true;

                    } elseif ($verbs === '*') {

                        $verb_matched = true;
                        $catched[] = $item['item'];
                    }
                }

                if ($noun_matched === false && (($item['pos'] && isset($noun_pos_maps[$item['pos']])) || isset($ne_maps[$item['ne']]))) {

                    if (isset($nouns[$item['item']])) {

                        $noun_matched = true;
                    } elseif ($nouns === '*') {

                        $noun_matched = true;
                        $catched[] = $item['item'];
                    }
                }
            }

            return [$verb_matched && $noun_matched, $catched];
        }

        return [false, []];
    };
}/*}}}*/
