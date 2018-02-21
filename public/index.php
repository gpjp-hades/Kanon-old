<?php

session_start();

require_once "../lib/autoloader.php";

new class {

	function __construct() {

		$this->db = new \lib\db("../db/kanon.csv");
		
		$this->checkPreview();
		$this->initVars();
		$this->clearVars();

		if (isset($_POST['state']) && !empty($_POST['state'])) {

			if (!\lib\csrf::check()) {
				header("Location: .");
				exit();
			}

			$this->checkVars();

			if ($_POST['state'] == "add") {
				$this->add();
			} else if ($_POST['state'] == "wipe") {
				$this->wipe();
			} else if ($_POST['state'] == "remove") {
				$this->remove();
			} else if ($_POST['state'] == "finish") {
				$this->finish();
			} else {
				$this->display("index", "error", \lib\local::UNKNOWN_ACTION);
			}
		} else {
			$this->display();
		}
	}

	
	function checkPreview() {
		if ($_SERVER['REQUEST_URI'] != \lib\autoloader::ROOT . "/preview")
			return false;
		
		new \lib\preview($this->db);
		exit();
	}

	function initVars() {
		if (!isset($_SESSION['books']))
			$_SESSION['books'] = [];
		
		if (!isset($_SESSION['print']))
			$_SESSION['print'] = false;

		if (!isset($_SESSION['vars']))
			$_SESSION['vars'] = ["name" => "", "surname" => "", "class" => ""];
		
		$GLOBALS = ["myBooks" => []];
	}

	function checkVars() {
		if (isset($_POST['name']) && !empty($_POST['name']))
			$_SESSION['vars']['name'] = $_POST['name'];
		
		if (isset($_POST['surname']) && !empty($_POST['surname']))
			$_SESSION['vars']['surname'] = $_POST['surname'];
		
		if (isset($_POST['class']) && !empty($_POST['class']))
			$_SESSION['vars']['class'] = $_POST['class'];
	}

	function clearVars() {
		$_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	}
	
	function add() {
		if (!isset($_POST['book'])) {
			$this->display("index", "error", \lib\local::NO_BOOK_SELECTED);
		} else if ($this->db->has($_POST['book'])) {
			array_push($_SESSION['books'], $_POST['book']);
			$_SESSION['print'] = false;
			$this->display();
		} else {
			$this->display("index", "error", \lib\local::BOOK_NOT_FOUND);
		}
	}

	function wipe() {
		$_SESSION['books'] = [];
		$_SESSION['print'] = false;
		$this->display("index", "success", \lib\local::CLEARED);
	}

	function remove() {
		if (isset($_POST['myBooks']) && in_array($_POST['myBooks'], $_SESSION['books'])) {

			unset($_SESSION['books'][array_search($_POST['myBooks'], $_SESSION['books'])]);
			$_SESSION['books'] = array_values($_SESSION['books']); //reindex
			$_SESSION['print'] = false;
			$this->display();
		} else {
			$this->display("index", "error", \lib\local::BOOK_NOT_FOUND);
		}
	}

	function finish() {
		if (!isset($_POST['name']) || $_POST['name'] == '' || !isset($_POST['surname']) || $_POST['surname'] == '') {
			$this->display("index", "error", \lib\local::MISSING_UNAME);
		} else if (!isset($_POST['class']) || $_POST['class'] == '') {
			$this->display("index", "error", \lib\local::MISSING_CLASS);
		} else {
			try {
				$validate = new \lib\validate($this->db);
				if ($validate->failed) {
					$this->display("index", "info", $validate->getRegionMessage(), \lib\local::REGION_FAIL_TITLE);
				} else {
					header("Location: ".\lib\autoloader::ROOT."/preview");
				}
			} catch (\Exception $e) {
			$this->display("index", "error", $e->getMessage());
			}
		}
	}
	
	function display($template = "index", $state = null, $message = null, $title = null) {
		$GLOBALS['state'] = $state;
		$GLOBALS['title'] = $title;
		$GLOBALS['message'] = $message;

		$this->loadBooks();
		\lib\autoloader::getTemplate($template);
	}

	function loadBooks() {
		$books = $this->db->getAll();

		$myregions = [];

		sort($_SESSION['books']);

		foreach ($_SESSION['books'] as $id) {
			unset($books[$id]);

			$book = $this->replaceRegion($this->db->getInfo($id));
			if (!isset($myregions[$book['region']]))
				$myregions[$book['region']] = [];
			array_push($myregions[$book['region']], $book);
		}

		$GLOBALS['myBooks'] = $myregions;

		$regions = [];

		foreach ($books as $book) {
			$book = $this->replaceRegion($book);
			if (!isset($regions[$book['region']]))
				$regions[$book['region']] = [];
			array_push($regions[$book['region']], $book);
		}

		$GLOBALS['books'] = $regions;
	}

	function replaceRegion($book) {
		$book['region'] = \lib\local::REGIONS[$book['region']];
		return $book;
	}
};
