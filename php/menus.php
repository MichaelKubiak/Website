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
        return "$path$name";
    }

    function createMenuSystem($pages, $current){
        reorderArray($pages);
        echo "<nav class='screenwidth'>";
        foreach ($pages as $page){
            if ($page['extension']){
                $name = getMetaName($page['dirname'], $page);
                if ($name == str_replace([".", "/"], "", $current)){
                    $style = "this_menu";
                }else
                    $style = "other_menu";
                echo "<a class=$style href='$page[dirname]/$page[basename]' style='text-decoration:none'>$name</a>";
            }else{
                $heading = str_replace([".","/"], "", $page[0]['dirname']);
                echo "<div onmouseenter=dropdown($heading) onmouseout=closeMenu($heading) class=other_menu>$heading";
                echo "<div id='$heading' class='dropdown_content'>";
                foreach ($page as $subpage){
                    if ($subpage['extension']){
                        $name = getMetaName($subpage['dirname'], $subpage);
                        echo "<a onmouseenter=dropdown($heading) onmouseexit=closeMenu($heading) class=ddchild href='$subpage[dirname]/$subpage[basename]'>$name</a>";
                    }
                }
                
                
                echo "</div></div>";
            }
        }
        echo "</nav>";
    }

    function reorderArray(&$pages){
        $order = fopen(_ROOT . "/ordering", "r") or die ("An error has occurred, please contact me on michael.kubiak@bath.edu");
        $start = fgets($order);
        $end = fgets($order);
        // delimited by ^
        $startarray = explode("^", $start);
        $endarray = explode("^", $end);
        // get file information for all items in the arrays, so that they will match the information in $pages
        foreach($startarray as &$startitem)
            $startitem = pathinfo(str_replace("\n", "", _ROOT . $startitem));
        foreach($endarray as &$enditem)
            $enditem = pathinfo(str_replace("\n", "", _ROOT . $enditem));
        moveArrayItems($pages, $startarray, 0);
        moveArrayItems($pages, $endarray, count($pages));
        fclose($order);
    }

    // move array of items to a specific position in sequence, so that the last will end up in that position, with the other items having been moved in a direction dependent on where in the array they are being moved to.  In the cases that are relevant, the $start array must have the first item as the item which should have the highest index at the end of the process, while the $end array must have the first item as the item which should have the lowest final index
    function moveArrayItems(&$array, $items, $position){
        foreach ($items as $item){
            moveArrayItem($array, $item, $position);
        }
    }
    
    // move a single item to a specific position in the array
    function moveArrayItem(&$array, $item, $position){
        $pos = array_search($item, $array);
        // if ($pos) doesn't work for index 0
        if(in_array($item, $array)){
            // remove one value from the array at the starting position
            $removed = array_splice($array, $pos, 1);
            // replace that value at the finishing position, without removing any elements
            array_splice($array, $position, 0, $removed);
        }
    }
?>



