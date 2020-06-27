<?php
    function getfiles($path){
        $out = array();
        foreach (glob("$path/*") as $filename){
            $p = pathinfo($filename);
            $out[] = $p;
        }
        return $out;
    }

    function getMenuLists($path = ".", $i = 0){
        if ($i >10)
            return;
        $pages = array();
        foreach (getfiles("$path") as $page){
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
        
        echo $_SERVER["PHP_SELF"];
    }
?>



