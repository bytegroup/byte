<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/2/15
 * Time: 12:46 PM
 */
?>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>
