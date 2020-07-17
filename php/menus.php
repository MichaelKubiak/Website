<?php

    function getfiles($path){
        $out = array();
        foreach (glob("$path/*") as $filename){
            $p = pathinfo($filename);
            $out[] = $p;
        }
        return $out;
    }

    function getFileLists($path = _ROOT, $i = 0, $exts = []){
        
        if ($i >10)
            return;
        $pages = array();
        $new = false;
        foreach (getfiles($path) as $page){
            if (!isset($page['extension'])){
                $next = getFileLists("$path/$page[filename]", $i+1);
                if ($next != null){
                    array_push($pages, $next);
                    $new = true;
                }

            }
            else{
                foreach ($exts as $ext){
                    if ($page['extension'] == $ext){
                        
                        array_push($pages, $page);
                        $new = true;
                    }
                }
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
        reorderArray($pages);
        $width = 100/count($pages);
        $splittitle = explode("/", $current);
        $title = $splittitle[count($splittitle)-1];
        echo "<div class='title screenwidth'>$title</div>";
        echo "<nav class='screenwidth'>";
        foreach ($pages as $page){
            if ($page['extension']){
                $name = getMetaName($page['dirname'], $page);
                if ($name == str_replace([".", "/"], "", $current))
                    $style = "menu menu-this";
                else
                    $style = "menu menu-other";
                
                echo "<a class='$style' href='$page[dirname]/$page[basename]' style='min-width:$width%'>$name <br><span class=hidden>&#8964</span></a>";
            }else{
                $heading = str_replace([".","/"], "", $page[0]['dirname']);
                echo "<div onmouseenter=dropdown($heading) onmouseout=closeMenu($heading) class='menu menu-other' style='min-width:$width%'>$heading<br>&#8964";
                echo "<div id='$heading' class='dropdown_content'>";
                foreach ($page as $subpage){
                    if ($subpage['extension']){
                        $name = getMetaName($subpage['dirname'], $subpage);
                        if ($name == explode("/", $current)[count(explode("/", $current))-1])
                            $style = "ddchild menu-this";
                        else
                            $style = "ddchild menu-other";
                        echo "<a onmouseenter=dropdown($heading) onmouseexit=closeMenu($heading) class='$style'' href='$subpage[dirname]/$subpage[basename]'>$name</a>";
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



