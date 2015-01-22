<?php

if(isset($permissions['canViewIT-InventoryMenu'])){
    include("it-inventory.html");
}
if(isset($permissions['canViewAdmin-SetupMenu'])){
    include("admin-setup.html");
}
/*if(isset($permissions['canViewReportMenu'])){
    include("report.html");
}*/


?>

