<?php

namespace lib;

class save {
    
    function __construct($target) {
        if (!is_dir($target))
            throw new \Exception($target . " not found");
        $this->target = $target;

        if (!$this->checkVars())
            throw new \Exception("Session failed");
        
        $this->name = $_SESSION['vars']['name'];
        $this->surname = $_SESSION['vars']['surname'];
        $this->class = $_SESSION['vars']['class'];
    }

    function save(array $books, string $code) {
        $path = $this->target . "/" . $this->class;

        if (!is_dir($path))
            mkdir($path);
        
        $content = $this->name . " " . $this->surname . PHP_EOL;
        foreach ($books as $id => $book) {
            $content .= ["A", "B", "C", "D"][$book['region']] . 
                        str_pad($id, 3, "0", STR_PAD_LEFT) . ";" .
                        $book['author'] . ": " . $book['name'] . PHP_EOL;
        }

        $fname = $this->slugify($this->name . " " . $this->surname . " " . date("H:i:s-d.m.Y") . " " . $code) . ".list";
        file_put_contents($path . "/" . $fname, $content);
    }

    function checkVars() {
        if (
            isset($_SESSION['vars']['name']) &&
            isset($_SESSION['vars']['surname']) &&
            isset($_SESSION['vars']['class'])
        )
            return true;
        return false;
    }

    //source: https://stackoverflow.com/a/10152907
    function slugify($text,$strict = false) {
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('~[^\\pL\d.]+~u', '-', $text);

        $text = trim($text, '-');
        setlocale(LC_CTYPE, 'en_GB.utf8');
        
        if (function_exists('iconv')) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        $text = strtolower($text);
        $text = preg_replace('~[^-\w.]+~', '', $text);
        if (empty($text)) {
        return 'empty_$';
        }
        if ($strict) {
            $text = str_replace(".", "_", $text);
        }
        return $text;
    }
}
