<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 2:11 PM
 */
?>
<style type="text/css">
    .chzn-search{border-top: 1px #3875d7 solid;}
</style>
<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $('.chzn-search input').hide();
    });
</script>