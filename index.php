<?php

session_start();

require_once "lib/autoloader.php";

class index {
	function __construct() {

		if (!isset($_SESSION['books']))
			$_SESSION['books'] = [];
			
		$_VARS = [];

		$this->clearVars();

		if (isset($_POST['state']) && !empty($_POST['state'])) {
			if ($_POST['state'] == "add") {

				if (!\lib\csrf::check()) {
					\lib\autoloader::getTemplate("csrf");
					exit();
				}

				if (
					isset($_POST['book']) &&
					!empty($_POST['book']) &&
					\lib\books::has($_POST['book'])
				) {
					array_push($_SESSION['books'], $_POST['book']);
					\lib\autoloader::getTemplate("index");
				} else {

					$_VARS['state'] = "error";
					$_VARS['message'] = "Kniha nenalezena";

					\lib\autoloader::getTemplate("index");
				}
				
			} else if ($_POST['state'] == "wipe") {

				$_SESSION['books'] = [];

				$_VARS['state'] = "success";
				$_VARS['message'] = "Kánon vymazán";

				\lib\autoloader::getTemplate("index");

			} else if ($_POST['state'] == "remove") {
				if (
					isset($_POST['book']) &&
					!empty($_POST['book']) &&
					in_array($_POST['book'], $_SESSION['books'])
				) {

					unset($_SESSION['books'][$_POST['book']]);
					$_SESSION['books'] = array_values($_SESSION['books']); //reindex

					$_VARS['state'] = "success";
					$_VARS['message'] = "Kánon vymazán";

					\lib\autoloader::getTemplate("index");
				} else {
					
					$_VARS['state'] = "error";
					$_VARS['message'] = "Kniha nenalezena";

					\lib\autoloader::getTemplate("index");
				}
			} else if ($_POST['state'] == "finish") {
			} else if ($_POST['state'] == "save") {
			}
		} else {
			\lib\autoloader::getTemplate("index");
		}
	}

	function clearVars() {
		$_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	}
}

new index;