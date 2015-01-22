<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 12:42 PM
 */
?>

<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Damage List" onclick="window.location='<?php echo $backToDamageList; ?>'" class="ui-input-button" id="">
    </div>
</div>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>