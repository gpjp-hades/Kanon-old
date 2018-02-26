<?php

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));

session_start();

require_once "../lib/autoloader.php";

class index {

	function __construct() {

		$this->autoloader = new \lib\autoloader();
		
		$this->db = new \lib\db("../db/kanon.csv");
		
		$this->checkPack();
		$this->checkPreview();
		$this->initVars();
		$this->clearVars();

		if (isset($_POST['state']) && !empty($_POST['state'])) {

			if (!\lib\csrf::check()) {
				header("Location: " . \lib\autoloader::ROOT);
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

	function checkPack() {
		if (strpos($_SERVER['REQUEST_URI'], \lib\autoloader::ROOT . "download") !== 0)
			return false;

		new \controller\pack($this->db, $this->autoloader);
		exit();
	}

	function validate($callback) {
		try {
			$validate = new \lib\validate($this->db);
			if ($validate->failed) {
				$this->display("index", "info", $validate->getRegionMessage(), \lib\local::REGION_FAIL_TITLE);
			} else {
				$callback();
				exit();
			}
		} catch (\Exception $e) {
			$this->display("index", "error", $e->getMessage());
			exit();
		}
	}

	function checkPreview() {
		if (strpos($_SERVER['REQUEST_URI'], \lib\autoloader::ROOT . "preview") !== 0)
			return false;

		$this->validate(function() {new \controller\preview($this->db, $this->autoloader);});

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
		if (isset($_POST['name']) && !empty($_POST['name'])) {
			if (@$_SESSION['vars']['name'] != $_POST['name'])
				$this->newCode();
			$_SESSION['vars']['name'] = substr($_POST['name'], 0, 20) . (strlen($_POST['name']) > 20 ? "...":"");
		}
		
		if (isset($_POST['surname']) && !empty($_POST['surname'])) {
			if (@$_SESSION['vars']['surname'] != $_POST['surname'])
				$this->newCode();
			$_SESSION['vars']['surname'] = substr($_POST['surname'], 0, 30) . (strlen($_POST['surname']) > 30 ? "...":"");
		}
		
		if (isset($_POST['class']) && !empty($_POST['class'])) {
			if (@$_SESSION['vars']['class'] != $_POST['class'])
				$this->newCode();
			$_SESSION['vars']['class'] = substr($_POST['class'], 0, 1);
		}
	}

	function newCode() {
		$_SESSION['print'] = false;
		unset($_SESSION['barcode']);
	}

	function clearVars() {
		$_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	}
	
	function add() {
		if (!isset($_POST['book'])) {
			$this->display("index", "error", \lib\local::NO_BOOK_SELECTED);
		} else if ($this->db->has($_POST['book'])) {
			array_push($_SESSION['books'], $_POST['book']);
			$this->newCode();
			$this->display();
		} else {
			$this->display("index", "error", \lib\local::BOOK_NOT_FOUND);
		}
	}

	function wipe() {
		$_SESSION['books'] = [];
		$this->newCode();
		$this->display("index", "success", \lib\local::CLEARED);
	}

	function remove() {
		if (!isset($_POST['myBooks'])) {
			$this->display("index", "error", \lib\local::NO_BOOK_SELECTED);
		} else if (in_array($_POST['myBooks'], $_SESSION['books'])) {

			unset($_SESSION['books'][array_search($_POST['myBooks'], $_SESSION['books'])]);
			$_SESSION['books'] = array_values($_SESSION['books']); //reindex
			$this->newCode();
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
			$this->validate(function() {
				header("Location: ".\lib\autoloader::ROOT."preview");
			});
		}
	}
	
	function display($template = "index", $state = null, $message = null, $title = null) {
		$GLOBALS['state'] = $state;
		$GLOBALS['title'] = $title;
		$GLOBALS['message'] = $message;

		$this->loadBooks();
		$this->autoloader->getTemplate($template);
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
		if ($this->db->has($book['id']))
			$book['region'] = \lib\local::REGIONS[$book['region']];
		return $book;
	}
};

new index;
