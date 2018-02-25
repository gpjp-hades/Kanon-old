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
	public const WWWROOT = "/kanon";
	
	function __destruct() {
		$log = $this->getLog();
		if (isset($_GET['DEBUG'])) {
			echo ob_get_clean();
			if (!empty($log))
				echo "<xmp>" . print_r($log, true) . "</xmp>";
		} else {
			if (!empty($log))
				$this->getTemplate('derpy');
			ob_end_clean();
			echo $this->display;
		}
		flush();
	}

	function errorHandler(int $errno, string $errstr, string $errfile, int $errline) {
		$this->log($errfile, "Error: " . $errno . ", with message: " . $errstr . ", in file: " . $errfile . ", on line: " . $errline);
	}

	function __construct() {

		set_error_handler([$this, "errorHandler"], \E_ALL);

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

	public function downloadFile(string $fname, string $downName = null) {
		if (is_null($downName))
			$downName = $fname;
		
		\lib\csrf::wipe();

		ob_start();
		
		if (file_exists($fname)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$downName.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fname));
			readfile($fname);
		}
		$this->display = ob_get_clean();
	}

	public function getTemplate(string $name) {
		$fname = $_SERVER['DOCUMENT_ROOT'] . \lib\autoloader::WWWROOT . "/" . \lib\autoloader::TEMPLATES . "/" . $name . ".phtml";
		if (!is_file($fname))
			return false;
		\lib\csrf::wipe();
		ob_start();
		$ret = $this->require($fname);
		$this->display = ob_get_clean();
		return $ret;
	}

	public static function getLayout(string $name) {
		$fname = $_SERVER['DOCUMENT_ROOT'] . \lib\autoloader::WWWROOT . "/" . \lib\autoloader::TEMPLATES . "/" . $name . ".phtml";
		if (!is_file($fname))
			return false;
		
		return \lib\autoloader::require($fname);
	}

	function log(string $source, string $event) {
		return array_push($this->log, [$source, $event]);
	}

	function getLog() {
		return $this->log;
	}

	private static function require(string $fname) {
		if (!is_file($fname))
			return false;

		return require_once $fname;
	}
}
