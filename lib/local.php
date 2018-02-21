<?php

namespace lib;

class local {
    
    const MORE_BOOKS        = "Nezvolili jste <strong>20</strong> knih.";
    const UNKNOWN_ACTION    = "Neznámá akce";
    const BOOK_NOT_FOUND    = "Kniha nenalezena";
    const CLEARED           = "Kánon vymazán";
    const NO_BOOK_SELECTED  = "Žádná kniha nevybraná";
    const REGION_FAIL_TITLE = "Nezvolili jste dost děl z jednotlivých období";
    const SAVE_FAILED       = "Odeslání se nepodařilo";
    const SAVE_SUCCESS      = "Kánon odeslán";

    static function MORE_AUTHORS ($name) {
        return "Máte více než 2 díla od autora <strong>" . $name . "</strong>";
    }

    const MIN_REGIONS   = [0, 0, 0, 0];
    const MAX_AUTHORS   = 2;
    const BOOKS         = 20;

    const REGIONS = [
        "Doba kamenná",
        "Období toaletního papíru",
        "Někdy včera",
        "Diáře, protože proč ne"
    ];

    const GRAPHICONS = [
        "ok" => "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> ",
        "failed" => "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> "
    ];

}