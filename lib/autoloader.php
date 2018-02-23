<?php

namespace lib;

ob_start();

final class autoloader {

	private $display = "";
	private $shouldDisplay = true;

	private $log = [];

	public const LIBS = ["lib"];
	public const TEMPLATES = "templates";
	public const ROOT = "/kanon/";
	public const WWWROOT = "/subdom/matlist";
	
	function __destruct() {
		if (isset($_GET['DEBUG'])) {
			// if DEBUG then get debug buffer
			echo ob_get_clean();
			$log = $this->getLog();
			if (!empty($log))
			print_r($log);
		} else {
			//else display basic output
			ob_end_clean();
			echo $this->display;
		}
		flush();
	}

	function __construct() {

		$final = [];
		foreach ($this::LIBS as $path) {
			$final = \array_merge($final, glob($_SERVER['DOCUMENT_ROOT'] . \lib\autoloader::WWWROOT . "/$path/*.{php}", GLOB_BRACE));
		}

		foreach ($final as $file) {
			try {
				$this->require($file);
			} catch (\Exception $e) {
				$this->log("autoloader", $e);
			}
		}
	}

	public function getTemplate(string $name) {
		$fname = $_SERVER['DOCUMENT_ROOT'] . \lib\autoloader::WWWROOT . "/" . \lib\autoloader::TEMPLATES . "/" . $name . ".phtml";
		if (!is_file($fname))
			return false;
		\lib\csrf::wipe();
		//pause debug buffer
		$pause = ob_get_contents();
		//clean buffer
		ob_clean();
		//get required file
		$ret = $this->require($fname);
		//save output of required file
		$this->display = ob_get_contents();
		//resume debug buffer
		echo $pause;
		return $ret;
	}

	function log(string $source, string $event) {
		return array_push($this->log, [$source, $event]);
	}

	function getLog() {
		return $this->log;
	}

	private function require(string $fname) {
		if (!is_file($fname))
			return false;

		return require_once $fname;
	}
}
