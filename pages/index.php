<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <meta charset="UTF-8">
        <title>Michael Kubiak</title>
        <meta name="Homepage" content="" id="name"/>
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <?php include("../php/menus.php")?>
    </head>
    <body>
        This is a message

        <div>
            
            <?php 
                echo json_encode(getMenuLists(), JSON_HEX_TAG), "<br>";
            ?>
            <?php getCurrentPage(); ?>
            
        </div>
    </body>
</html>