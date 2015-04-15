<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/15/15
 * Time: 12:23 PM
 */
?>
<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>