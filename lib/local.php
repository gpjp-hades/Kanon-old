<?php

namespace lib;

class local {
    
    const MORE_BOOKS        = "Nezvolili jste <strong>20</strong> knih.";
    const UNKNOWN_ACTION    = "Neznámá akce";
    const BOOK_NOT_FOUND    = "Kniha nenalezena";
    const CLEARED           = "Kánon vyčištěn";
    const NO_BOOK_SELECTED  = "Žádná kniha nezvolena";
    const REGION_FAIL_TITLE = "Nezvolili jste dostatečný počet děl z jednotlivých období";
    const SAVE_FAILED       = "Odeslání se nepodařilo";
    const SAVE_SUCCESS      = "Kánon odeslán";

    const MISSING_UNAME     = "Vyplňtě jméno";
    const MISSING_CLASS     = "Vyberte třídu";

    static function MORE_AUTHORS ($name) {
        return "Máte více než 2 díla od autora <strong>" . $name . "</strong>";
    }

    const MIN_REGIONS   = [2, 3, 4, 5];
    const MAX_AUTHORS   = 2;
    const BOOKS         = 20;

    const REGIONS = [
        "Literatura do konce 18. století",
        "Literatura 19. století",
        "Světová literatura 20. a 21. století",
        "Česká literatura 20. a 21. století"
    ];

    const GRAPHICONS = [
        "ok" => "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> ",
        "failed" => "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> "
    ];

}