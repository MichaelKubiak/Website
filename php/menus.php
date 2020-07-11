<?php

    function getfiles($path){
        $out = array();
        foreach (glob("$path/*") as $filename){
            $p = pathinfo($filename);
            $out[] = $p;
        }
        return $out;
    }

    function getMenuLists($path = _ROOT, $i = 0){
        
        if ($i >10)
            return;
        $pages = array();
        $new = false;
        foreach (getfiles($path) as $page){
            if (!isset($page['extension'])){
                $next = getMenuLists("$path/$page[filename]", $i+1);
                if ($next != null){
                    array_push($pages, $next);
                    $new = true;
                }

            }
            else if ($page['extension'] == "html" || $page['extension'] == "phtml"){
                array_push($pages, $page);
                $new = true;
            }
        }
        if ($new = true)
            return $pages;
        else
            return null;
    }

    function getMetaName($path, $page){
        $pagecode = file_get_contents("$path/$page[basename]");
        $domdoc = new DOMDocument;

        @$domdoc -> loadHTML($pagecode);
        $meta = $domdoc->getElementByID("name");
        $name = $meta->getAttribute("name");

        return $name;
    }

    function getCurrentPage(){
        
        $page = pathinfo($_SERVER['PHP_SELF']);
        $path = _ROOT . $page['dirname'];
        $name = getMetaName($path, $page);
        return "$path/$name";
    }

    function createMenuSystem($pages, $current){
        foreach ($pages as $page){
            if ($page == $current)
                $style = "this_menu";
            else
                $style = "other_menu";
            $splitpage = explode("/", $page);
            $name = array_pop($splitpage);
            echo "<a class=$style href=$page>$name</a>";
        }
    }
?>



