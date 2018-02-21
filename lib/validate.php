<?php

namespace lib;

class validate {

    private $books = [];
    public $failed = false;
    
    function __construct($db) {
        $this->db = $db;
        $this->books = $this->loadBooks();
        
        if (!$this->checkCount())
            throw new \Exception(\lib\local::MORE_BOOKS);

        $this->region = $this->checkRegion(\lib\local::MIN_REGIONS);
        if (in_array(false, $this->region)) {
            $this->failed = true;
        }

        $authors = $this->checkAuthor(\lib\local::MAX_AUTHORS);
        if ($authors !== true) {
            throw new \Exception(\lib\local::MORE_AUTHORS($authors));
        }
    }

    function getRegionMessage() {
        $ret = "";

        foreach ($this->region as $id => $region) {
            if ($region) {
                $ret .= "<span class='text-success'>" . \lib\local::GRAPHICONS['ok'] . \lib\local::REGIONS[$id] . "</span><br />" . PHP_EOL;
            } else {
                $ret .= "<strong class='text-danger'>" . \lib\local::GRAPHICONS['failed'] . \lib\local::REGIONS[$id] . "</strong><br />" . PHP_EOL;
            }
        }

        return $ret;
    }

    function loadBooks() {
        $ret = [];
        foreach ($_SESSION['books'] as $book) {
            array_push($ret,  array_merge(["id" => $book], $this->db->getInfo($book)));
        }
        return $ret;
    }

    function checkAuthor(int $max) {
        $authors = [];
        foreach ($this->books as $book) {
            if (!isset($authors[$book['author']]))
                $authors[$book['author']] = 0;
            
            $authors[$book['author']]++;
            if ($authors[$book['author']] > $max && $book['author'] != "") {
                return $book['author'];
            }
        }
        return true;
    }

    function checkRegion(array $required) {
        $count = [];
        foreach ($this->books as $book) {
            if (!isset($count[$book['region']]))
                $count[$book['region']] = 0;
            
            $count[$book['region']]++;
        }

        $ret = [];
        
        foreach ($required as $id => $min) {
            $ret[$id] = isset($count[$id]) && $count[$id] >= $min;
        }

        return $ret;
    }

    function checkCount() {
        return count($this->books) == \lib\local::BOOKS;
    }
}