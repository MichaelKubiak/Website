<?php
    define('_ROOT', getRootPath());
    define('_PHP', _ROOT . "/php");
    define('_CSS', _ROOT . "/css");
    define('_JS', _ROOT . "/js");
    define('_IMG', _ROOT . "/Images");

    function getRootPath(){
        $thispage = $_SERVER['PHP_SELF'];
        $splitpage = explode("/", $thispage);
        $out = ".";
        if (sizeof($splitpage) > 1){

            for ($x=1; $x<sizeof($splitpage)-1; $x++){
                $out .= "/..";
            }
        }
        return $out;
    }
?>