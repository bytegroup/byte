<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/31/14
 * Time: 6:02 PM
 */
?>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Stock List" onclick="window.location='<?php echo $backToStockList; ?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>