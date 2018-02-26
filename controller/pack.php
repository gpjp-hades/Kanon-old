<?php

namespace controller;

class pack {
    function __construct($db, $autoloader) {

        $this->db = $db;
        $this->autoloader = $autoloader;

        if (!isset($_SESSION['lists']))
            $_SESSION['lists'] = [];
        
        if (isset($_POST['state']) && !empty($_POST['state'])) {
            if (!\lib\csrf::check()) {
                header("Location: " . \lib\autoloader::ROOT . "download");
                exit();
            }
            if ($_POST['state'] == "add") {

                if (!isset($_POST['code']) || empty($_POST['code'])) {
                    $GLOBALS= [
                        "state" => "error",
                        "message" => \lib\local::NO_LIST_SELECTED
                    ];
                } else if (isset($_SESSION['lists'][$_POST['code']])) {
                    $GLOBALS= [
                        "state" => "error",
                        "message" => \lib\local::LIST_PRESENT
                    ];
                } else {
                    $files = glob("../out/{a,b,c}/*".date("Y")."-$_POST[code].list", GLOB_BRACE);
                    if (count($files) != 1) {
                        $GLOBALS= [
                            "state" => "error",
                            "message" => \lib\local::NO_LIST_FOUND
                        ];
                    } else {
                        $path = \explode("/", $files[0]);
                        $file = \explode("-", $path[count($path)-1]);
                        $_SESSION['lists'][$_POST['code']] = [
                            "name" => $file[0] . " " . $file[1],
                            "class" => $path[2],
                            "time" => $file[2] . ":" . $file[3] . ":" . $file[4] . " " . str_replace(".", ". ", $file[5]),
                            "code" => $_POST['code'],
                            "path" => $files[0]
                        ];
                    }
                }
            } else if ($_POST['state'] == 'remove') {
                if (!isset($_POST['code']) || empty($_POST['code'])) {
                    $GLOBALS= [
                        "state" => "error",
                        "message" => \lib\local::NO_LIST_SELECTED
                    ];
                } else if (!isset($_SESSION['lists'][$_POST['code']])) {
                    $GLOBALS= [
                        "state" => "error",
                        "message" => \lib\local::NO_LIST_FOUND
                    ];
                } else {
                    unset($_SESSION['lists'][$_POST['code']]);
                    $GLOBALS= [
                        "state" => "success",
                        "message" => \lib\local::LIST_REMOVED
                    ];
                }
            } else if ($_POST['state'] == 'wipe') {
                $_SESSION['lists'] = [];
                $GLOBALS= [
                    "state" => "success",
                    "message" => \lib\local::LIST_REMOVED
                ];
            } else if ($_POST['state'] == 'download') {
                if (count($_SESSION['lists']) == 0) {
                    $GLOBALS= [
                        "state" => "error",
                        "message" => \lib\local::LIST_DOWN_EMPTY
                    ];
                } else {
                    $zip = new \ZipArchive();
                    if (is_file("../out/download.zip"))
                        unlink("../out/download.zip");

                    if ($zip->open("../out/download.zip", \ZipArchive::CREATE)!==TRUE) {
                        $GLOBALS= [
                            "state" => "error",
                            "message" => \lib\local::LIST_DOWN_ERROR
                        ];
                    } else {
                        foreach ($_SESSION['lists'] as $file) {
                            $zip->addFile($file['path'], $file['class'] . "/" . str_replace(" ", "-", $file['name']) . ".gmz");
                        }
                        $zip->close();
                    }
                    $this->autoloader->downloadFile("../out/download.zip", "kanony.zip");
                    unlink("../out/download.zip");
                    exit();
                }
            }
        }

        $GLOBALS['list'] = $_SESSION['lists'];
        $this->autoloader->getTemplate("pack");
    }
};