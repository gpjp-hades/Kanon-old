<?php

namespace controller;

class preview {
    function __construct($db, $autoloader) {

        $this->db = $db;
        $this->autoloader = $autoloader;
        
        if (!$this->checkVars()) {
            header("Location: " . \lib\autoloader::ROOT);
            exit();
        }

        $class = \lib\local::CLASSNAME($_SESSION['vars']['class']);

        $books = [];

        foreach ($_SESSION['books'] as $book) {
			$books[$book] = $this->db->getInfo($book);
        }

        ksort($books);

        if (!isset($_SESSION['barcode']) || empty($_SESSION['barcode'])) {
            do {
                $code = $this->hashName(bin2hex(\openssl_random_pseudo_bytes(20)));
            } while (count(glob("../out/{a,b,c}/*-$code.list", GLOB_BRACE)) != 0);
            $_SESSION['barcode'] = $code;
        }

        $GLOBALS = [];

        if (isset($_POST['state']) && $_POST['state'] == "save") {
            if (!\lib\csrf::check()) {
                header("Location: " . \lib\autoloader::ROOT . "preview");
                exit();
            }
            try {
                $save = new \lib\save("../out");
                $save->save($books, $_SESSION['barcode']);
                $GLOBALS['state'] = "success";
                $GLOBALS['message'] = \lib\local::SAVE_SUCCESS;
                $_SESSION['print'] = true;
            } catch (\Exception $e) {
                $GLOBALS['state'] = "error";
                $GLOBALS['message'] = \lib\local::SAVE_FAILED;
            }
        }

        $GLOBALS = array_merge($GLOBALS, [
            "name" => $_SESSION['vars']['name'],
            "surname" => $_SESSION['vars']['surname'],
            "class" => $class,
            "books" => array_map([$this, "replaceRegion"], $books),
            "print" => $_SESSION['print'],
            "code" => $_SESSION['barcode']
        ]);

        $this->autoloader->getTemplate("preview");

    }

    static function getBarcodeImg($code) {

		$barcode = new \lib\barcode();

		$ret = "<img class='pull-right only-print' src='data:image/png;base64, ";

		$image = $barcode->render_image("ean-128", $code, [
			"w" => 150,
            "h" => 55,
            "ts" => 2,
            "th" => 13,
            "pb" => 20
		]);
		
		ob_start();
		imagepng($image);
		$ret .= base64_encode(ob_get_clean());
		$ret .= "' />";
		return $ret;
	}

	function hashName($name) {
		return substr(base_convert(md5($name), 16, 10), 0, 16);
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
};