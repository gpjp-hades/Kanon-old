<?php

namespace lib;

class db {
    private $book = [];

    function __construct($fname) {
        $this->load($fname);
        //$this->testLoad();
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
        
        $file = str_replace("\n", "\n;", file_get_contents($fname));
        $this->book = [];
        
        foreach (array_chunk(str_getcsv($file, ";"), 4) as $line) {
            if (!isset($line[1]))
                continue;
            $this->book[$line[0]] = [
                "id" => $line[0],
                "name" => $line[3],
                "author" => $line[2],
                "region" => $line[1]
            ];
        }
    }

    function has($book) {
        if (isset($this->book[$book]))
            return true;
        
        return false;
    }

    function getInfo($book) {
        if ($this->has($book))
            return $this->book[$book];
        
        return false;
    }

    function getAll() {
        return $this->book;
    }
}