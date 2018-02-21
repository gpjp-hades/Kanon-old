<?php

session_start();

require_once "lib/autoloader.php";

class index {
	function __construct() {

		if (!isset($_SESSION['books']))
			$_SESSION['books'] = [];

		if (!isset($_SESSION['vars']))
			$_SESSION['vars'] = ["name" => "", "surname" => "", "class" => ""];
			
		$GLOBALS = [];

		$this->clearVars();

		if (isset($_POST['state']) && !empty($_POST['state'])) {

			if (!\lib\csrf::check()) {
				$this->loadBooks();
				\lib\autoloader::getTemplate("index");
				exit();
			}

			if (isset($_POST['name']) && !empty($_POST['name']))
				$_SESSION['vars']['name'] = $_POST['name'];
			
			if (isset($_POST['surname']) && !empty($_POST['surname']))
				$_SESSION['vars']['surname'] = $_POST['surname'];
			
			if (isset($_POST['class']) && !empty($_POST['class']))
				$_SESSION['vars']['class'] = $_POST['class'];

			if ($_POST['state'] == "add") {

				if (
					isset($_POST['book']) &&
					!empty($_POST['book']) &&
					\lib\books::has($_POST['book'])
				) {
					array_push($_SESSION['books'], $_POST['book']);
					
					$this->loadBooks();
					\lib\autoloader::getTemplate("index");
				} else {

					$GLOBALS['state'] = "error";
					$GLOBALS['message'] = "Kniha nenalezena";

					$this->loadBooks();
					\lib\autoloader::getTemplate("index");
				}
				
			} else if ($_POST['state'] == "wipe") {

				$_SESSION['books'] = [];

				$GLOBALS['state'] = "success";
				$GLOBALS['message'] = "Kánon vymazán";

				$this->loadBooks();
				\lib\autoloader::getTemplate("index");

			} else if ($_POST['state'] == "remove") {
				if (
					isset($_POST['myBook']) &&
					!empty($_POST['myBook']) &&
					in_array($_POST['myBook'], $_SESSION['books'])
				) {

					unset($_SESSION['books'][$_POST['myBook']]);
					$_SESSION['books'] = array_values($_SESSION['books']); //reindex

					$this->loadBooks();
					\lib\autoloader::getTemplate("index");
				} else {
					
					$GLOBALS['state'] = "error";
					$GLOBALS['message'] = "Kniha nenalezena";

					$this->loadBooks();
					\lib\autoloader::getTemplate("index");
				}
			} else if ($_POST['state'] == "finish") {
				new \lib\validate;
			} else {
				$GLOBALS['state'] = "error";
				$GLOBALS['message'] = "Neznámá akce";

				$this->loadBooks();
				\lib\autoloader::getTemplate("index");
			}
		} else {
			$this->loadBooks();
			\lib\autoloader::getTemplate("index");
		}
	}

	function clearVars() {
		$_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	}

	function loadBooks() {
		$GLOBALS['books'] = [
			["hola", "mola"]
		];

		$GLOBALS['myBooks'] = $_SESSION['books'];
	}
}

new index;