<?php

namespace controller;

class index extends \model\controller {

    function __invoke($request, $response, $args) {

        if ($request->isGet()) {

            $allBooks = $this->db->select('books', '*');

            $myBooks = $this->db->select('books', [
                '[>]books_users' => [
                    'id' => 'book',
                    'user.id' => 'user'
                ]
            ], "*", [
                "user.token" => session_id()
            ]);

            $response = $this->sendResponse($request, $response, "index.phtml", [
                "allBooks" => $allBooks,
                "myBooks"  => $myBooks
            ]);

        } else if ($request->isDelete()) {

        }

        return $response;
    }

}