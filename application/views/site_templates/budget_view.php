<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/29/14
 * Time: 12:09 PM
 */
?>

<h3><?=$pageTitle?></h3>
<?=$output?>


<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $("#field-budgetYear").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy'
        });
    });
</script>