<?php

namespace preview;

session_start();

require_once "../lib/autoloader.php";

new class {
    function __construct() {
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
        
        $GLOBALS = [
            "name" => $_SESSION['vars']['name'],
            "surname" => $_SESSION['vars']['surname'],
            "class" => $class,
            "books" => $books
        ];

        \lib\autoloader::getTemplate("preview");
        
    }

    function checkVars() {
        if (
            isset($_SESSION['vars']['name']) &&
            isset($_SESSION['vars']['surname']) &&
            isset($_SESSION['vars']['class']) &&
            is_array($_SESSION['books']) //&&
            //count($_SESSION['books']) == 20
        )
            return true;
        return false;
    }

    function clearVars() {
		$_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	}
};