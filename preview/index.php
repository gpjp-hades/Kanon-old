<?php

namespace preview;

session_start();

require_once "../lib/autoloader.php";

new class {
    function __construct() {

        $this->db = new \lib\db("../db/kanon.csv");

        $this->clearVars();
        
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

        $GLOBALS = [
            "name" => $_SESSION['vars']['name'],
            "surname" => $_SESSION['vars']['surname'],
            "class" => $class,
            "books" => array_map([$this, "replaceRegion"], $books)
        ];
        
        if (isset($_POST['state']) && $_POST['state'] == "save") {
            try {
                $save = new \lib\save("../out");
                $save->save($books);
                $GLOBALS['state'] = "success";
                $GLOBALS['message'] = \lib\local::SAVE_SUCCESS;
            } catch (\Exception $e) {
                $GLOBALS['state'] = "error";
                $GLOBALS['message'] = \lib\local::SAVE_FAILED;
            }
        }

        \lib\autoloader::getTemplate("preview");

        
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

    function clearVars() {
		$_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	}
};