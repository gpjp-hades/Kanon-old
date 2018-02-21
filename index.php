<?php

session_start();

require_once "lib/autoloader.php";

new class {
	function __construct() {

		$this->db = new \lib\db();

		if (!isset($_SESSION['books']))
			$_SESSION['books'] = [];

		if (!isset($_SESSION['vars']))
			$_SESSION['vars'] = ["name" => "", "surname" => "", "class" => ""];
			
		$GLOBALS = ["myBooks" => []];

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
				if (!isset($_POST['book'])) {
					
					$GLOBALS['state'] = "error";
					$GLOBALS['message'] = "Žádná kniha nevybraná";

					$this->loadBooks();
					\lib\autoloader::getTemplate("index");

				} else if ($this->db->has($_POST['book'])) {
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
					isset($_POST['myBooks']) &&
					in_array($_POST['myBooks'], $_SESSION['books'])
				) {

					unset($_SESSION['books'][$_POST['myBooks']]);
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
				try {
					$validate = new \lib\validate($this->db);
					if ($validate->failed) {
						$GLOBALS['state'] = "info";
						$GLOBALS['title'] = "Nezvolili jste dostatek děl";
						$GLOBALS['message'] = $validate->getRegionMessage();

						$this->loadBooks();
						\lib\autoloader::getTemplate("index");
					} else {
						header("Location: preview");
					}
				} catch (\Exception $e) {
					$GLOBALS['state'] = "error";
					$GLOBALS['message'] = $e->getMessage();

					$this->loadBooks();
					\lib\autoloader::getTemplate("index");
				}
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
		$books = $this->db->getAll();

		foreach ($_SESSION['books'] as $book) {
			array_push($GLOBALS['myBooks'],  array_merge(["id" => $book], $this->db->getInfo($book)));
			unset($books[$book]);
		}

		$GLOBALS['books'] = $books;
	}
};
