<?php

namespace lib;

class csrf {

	function __construct() {
		if (!isset($_SESSION['csrf']))
			$_SESSION['csrf'] = [];
	}

	static function getValues() {
		$vals = [bin2hex(\openssl_random_pseudo_bytes(5)), bin2hex(\openssl_random_pseudo_bytes(15))];
		array_push($_SESSION['csrf'], $vals);
		return $vals;
	}

	static function wipe() {
		$_SESSION['csrf'] = [];
	}

	static function check() {
		if (!isset($_POST['csrf'])) {
			\lib\csrf::wipe();
			return false;
		}
		if (isset($_SESSION['csrf'])) {
			foreach ($_SESSION['csrf'] as $test) {
				if (isset($_POST[$test[0]]) && $_POST[$test[0]] == $test[1])
					return true;
			}
		}
		\lib\csrf::wipe();
		return false;
	}

	static function printHTML($newLine) {
		$vals = \lib\csrf::getValues();
		return "<input type='hidden' name='csrf' value='csrf' />
$newLine<input type='hidden' name='$vals[0]' value='$vals[1]' />\n";
	}
}