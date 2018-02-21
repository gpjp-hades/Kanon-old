<?php

namespace lib;

class db {
    private $book = [];

    function __construct() {
        //$this->load();
        $this->testLoad();
    }

    function testLoad() {
        $this->book = [
            ["name" => "hola", "author" => "pepa", "region" => 0],
            ["name" => "hola", "author" => "pepa", "region" => 0]
        ];
    }

    function load(string $fname) {
        if (!is_file($fname))
            return false;
        
        $this->file = file_get_contents($fname);
    }

    function has(int $book) {
        if (isset($this->book[$book]))
            return true;
        
        return false;
    }

    function getInfo(int $book) {
        if ($this->has($book))
            return $this->book[$book];
        
        return false;
    }

    function getAll() {
        return $this->book;
    }
}