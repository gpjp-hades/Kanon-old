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

            $this->ensureTable("books", [
                "id INTEGER PRIMARY KEY",
                "author TEXT",
                "name TEXT",
                "genere INTEGER",
                "region INTEGER"
            ]);

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
        if ($this->db->insert("config", $data)) {
            $this->logger->info("Populated table $name");
            return true;
        }
        return false;
    }
}
