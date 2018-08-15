<?php

dialogue_topic([
    '*这本书怎么样',
    '*在豆瓣图书的信息'
], function ($user_id, $content, $time, $book_query) {/*{{{*/

    $book_query = trim($book_query);

    $books = douban_book_search($book_query, 20);

    if ($books) {

        if (($count = count($books)) > 1) {

            $message = "在豆瓣共查到 $count 本与 '$book_query' 相关的图书，回复想查的序号：";

            foreach ($books as $no => $book) {
                $message .= "\n".($no + 1)." 《{$book['title']}》{$book['pubdate']} 出版";
            }

            do {
                $user_answer = dialogue_ask_and_wait($user_id, $message, null, 60);

                if (is_null($user_answer)) {
                    return; // wait timeout
                }

                $no = $user_answer - 1;
                $message = "没有 '$user_answer'";

            } while (! array_key_exists($no, $books));

            $book = $books[$no];

        } else {

            $book = reset($books);
        }

        $answer = "《{$book['title']}》在豆瓣图书有 {$book['rating']['numRaters']} 个读者评价，{$book['rating']['average']} 的平均分，图书摘要：\n{$book['summary']}";

        dialogue_say($user_id, $answer);
    } else {
        dialogue_say($user_id, "豆瓣上没搜到跟 '$book_query' 相关的图书...");            
    }

});/*}}}*/
