<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/10/14
 * Time: 9:14 PM
 */
?>

<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Vendor List" onclick="window.location='<?php echo $backToVendorList?>'" class="ui-input-button" id="">
    </div>
</div>
<h3><?=$pageTitle?></h3>
<?=$output?>



<!-- Code to handle the server response (see test.php) -->
<script language="JavaScript">

    $(document).ready(function(e){
        $("#collapseAdmin").removeClass("in").addClass("in");
    });

</script>