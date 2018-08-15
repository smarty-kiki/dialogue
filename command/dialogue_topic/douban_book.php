<?php

dialogue_topic([
    '*这本书怎么样',
    '*在豆瓣图书的信息'
], function ($user_id, $content, $time, $book_query) {/*{{{*/

    $book_query = trim($book_query);

    $books = douban_book_search($book_query, 20);

    if ($books) {

        if (($count = count($books)) > 1) {

            $message = "在豆瓣共查到 $count 本与 '$book_query' 相关的图书，回复想查的书名：";

            $books_indexed = [];

            foreach ($books as $book) {
                $title = $book['title'];

                $message .= "\n$title";
                $books_indexed[$title] = $book;
            }

            $user_answer = dialogue_ask_and_wait($user_id, $message, null, 60);

            while ((! is_null($user_answer)) && (! array_key_exists($user_answer, $books_indexed))) {

                $user_answer = dialogue_ask_and_wait($user_id, "没有'$user_answer'", null, 60);
            }

            if (is_null($user_answer)) {
                return; // wait timeout
            }

            $book = $books_indexed[$user_answer];

        } else {
            $book = reset($books);
        }

        $answer = "{$book['title']} 这本书出版于 {$book['pubdate']}，豆瓣有 {$book['rating']['numRaters']} 给出 {$book['rating']['average']} 的平均分，图书摘要：\n{$book['summary']}";

        dialogue_say($user_id, $answer);
    } else {
        dialogue_say($user_id, "豆瓣上没搜到跟 '$book_query' 相关的图书...");            
    }

});/*}}}*/
