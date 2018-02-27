<?php

namespace bootstrap;

class seed extends \model\container {

    /**
     * Database schema:
     * 
     * users
     *  - INT  id
     *  - TEXT name
     *  - TEXT surname
     *  - INT  class
     *  - INT token
     *  
     * books
     *  - INT  id
     *  - TEXT author
     *  - TEXT name
     *  - INT  region
     *  - INT  genere
     * 
     * users_books
     *  - INT  user
     *  - INT  book
     * 
     */

    function check():bool {

        if (!$this->container->db->has("sqlite_master", [
            "AND" => [
                "type" => "table",
                "OR" => [
                    "name" => [
                        "users",
                        "books",
                        "users_books"
                    ]
                ]
            ]
        ])) {
            
            $this->ensureTable("users", [
                "id INTEGER PRIMARY KEY",
                "name TEXT",
                "surname TEXT",
                "class INTEGER",
                "token INTEGER"
            ]);

            if ($this->ensureTable("books", [
                "id INTEGER PRIMARY KEY",
                "author TEXT",
                "name TEXT",
                "genere INTEGER NULL",
                "region INTEGER"
            ])) {
                $books = $this->parseCSV('../db/kanon.csv', ['[i]id', '[i]region', '[s]author', '[s]name']);
                $this->populate('books', $books);
            }

            $this->ensureTable("users_books", [
                "user INTEGER",
                "book INTEGER"
            ]);
            return true;
        }
        return false;
    }

    private function ensureTable(string $name, array $fields):bool {
        $fieldsString = implode(", ", $fields);

        if (is_array(
            $this->db->query("CREATE TABLE IF NOT EXISTS $name ($fieldsString);")->fetchAll()
        )) {
            $this->logger->info("Created table $name");
            return true;
        }
        return false;
    }

    private function populate(string $table, array $data):bool {
        if ($this->db->insert($table, $data)) {
            $this->logger->info("Populated table $table");
            return true;
        }
        return false;
    }

    private function parseCSV(string $fname, array $fields):array {
        if (!is_file($fname))
            throw new \InvalidArgumentException($fname . " not found");
        
        $handle = fopen($fname, "r");
        $ret = [];
        if ($handle) {
            while (($data = fgetcsv($handle, 0, ";")) !== false) {
                $line = [];
                foreach ($fields as $id => $field) {
                    $line[substr($field, 3)] = substr($field, 0, 3) == "[i]" ? (int) $data[$id] : (string) $data[$id];
                }
                array_push($ret, $line);
            }
            fclose($handle);
        }
        
        return $ret;
    }
}
