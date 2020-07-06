<?php

    include("./constants.php");

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
        foreach (getfiles($path) as $page){
            if ($page[extension] == "html" || $page[extension] == "php" || $page[extension] == "phtml"){

                $name = getMetaName($path, $page);
                
                if ($path)
                    $name = "$path/$name";
                array_push($pages, "$name");
            } else if (!$page[extension]){
                
                array_push($pages, getMenuLists("$path/$page[filename]", $i+1));

            }

        }
        return $pages;
    }

    function getMetaName($path, $page){
        $pagecode = file_get_contents("$path/$page[filename].$page[extension]");
        $domdoc = new DOMDocument;

        @$domdoc -> loadHTML($pagecode);
        $meta = $domdoc->getElementByID("name");
        $name = $meta->getAttribute("name");
        return $name;
    }

    function getCurrentPage(){
        
        $page = pathinfo($_SERVER['PHP_SELF']);
        $path = _ROOT . $page[dirname];
        $name = getMetaName($path, $page);
        return "$path/$name";
    }

    function createMenuSystem($pages, $current){
        foreach ($pages as $page){
            if ($page == $current)
                $style = "this_menu";
            else
                $style = "other_menu";
            $splitpage = split("\\", $page);
            $name = array_pop($splitpage);
            echo "<a class=$style href=$page>$name</a>";
        }
    }
?>



