<?php

define('DOUBAN_API_DOMAIN', 'https://api.douban.com');

function douban_book_search($book_query, $limit = null)
{/*{{{*/
    $start = 0;
    $books = [];
    $book_query = urlencode($book_query);

    do {
        $book_infos = remote_get_json(DOUBAN_API_DOMAIN."/v2/book/search?q=$book_query&start=$start&count=100&fields=id,title,summary,author,pubdate,rating,binding,price");

        $start += $count = $book_infos['count'];
        $books = array_merge($books, $book_infos['books']);

    } while (
        $count &&
        (is_null($limit) || $count < $limit)
    );

    return array_slice($books, 0, $limit);
}/*}}}*/
