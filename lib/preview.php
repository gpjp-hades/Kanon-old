<?php

namespace lib;

class preview {
    function __construct($db, $autoloader) {

        $this->db = $db;
        $this->autoloader = $autoloader;
        
        if (!$this->checkVars()) {
            header("Location: " . \lib\autoloader::ROOT);
            exit();
        }

        $class = [
            "a" => "Oktáva A",
            "b" => "Oktáva B",
            "c" => "4. C"
        ][$_SESSION['vars']['class']];

        $books = [];

        foreach ($_SESSION['books'] as $book) {
			$books[$book] = $this->db->getInfo($book);
        }

        ksort($books);

        $GLOBALS = [];

        if (isset($_POST['state']) && $_POST['state'] == "save") {
            if (!\lib\csrf::check()) {
                header("Location: .");
                exit();
            }
            try {
                $save = new \lib\save("../out");
                $save->save($books);
                $GLOBALS['state'] = "success";
                $GLOBALS['message'] = \lib\local::SAVE_SUCCESS;
                $_SESSION['print'] = true;
            } catch (\Exception $e) {
                $GLOBALS['state'] = "error";
                $GLOBALS['message'] = \lib\local::SAVE_FAILED;
            }
        }

        $GLOBALS = array_merge($GLOBALS, [
            "name" => $_SESSION['vars']['name'],
            "surname" => $_SESSION['vars']['surname'],
            "class" => $class,
            "books" => array_map([$this, "replaceRegion"], $books),
            "print" => $_SESSION['print']
        ]);

        $this->autoloader->getTemplate("preview");

    }

    function replaceRegion($book) {
        $book['region'] = \lib\local::REGIONS[$book['region']];
        return $book;
    }

    function checkVars() {
        if (
            isset($_SESSION['vars']['name']) &&
            isset($_SESSION['vars']['surname']) &&
            isset($_SESSION['vars']['class']) &&
            is_array($_SESSION['books']) &&
            count($_SESSION['books']) == \lib\local::BOOKS
        )
            return true;
        return false;
    }
};