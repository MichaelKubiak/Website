<?php
function startCollapsible($name){
    echo "<div id=$name class=collapsible onmouseup=collapse('$name') onmouseover=highlight('$name') onmouseout=highlight('$name')>\n";
}
?>