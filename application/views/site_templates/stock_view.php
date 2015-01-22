<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/31/14
 * Time: 2:56 PM
 */
?>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>