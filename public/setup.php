<?php

if (!$_SERVER['SERVER_NAME'] == 'http://matlist.svarc.it')
    exit();

file_put_contents(".htaccess", "DirectoryIndex index.php71

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php71 [QSA,L]");

rename("index.php", "index.php71");

$autoloader = file_get_contents("../lib/autoloader.php");
$autoloader = str_replace([
    "public const ROOT = \"/kanon/\";",
    "public const WWWROOT = \"/kanon\";"
], [
    "public const ROOT = \"/\";",
    "public const WWWROOT = \"/subdom/matlist\";"
], $autoloader);

file_put_contents("../lib/autoloader.php", $autoloader);

unlink(__FILE__);