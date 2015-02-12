<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 12:07 PM
 */
?>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        //var today=
        if($('#field-serviceStartDate').val()==='')$('#field-serviceStartDate').datepicker('setDate', 'today');
        //if($('#field-serviceEndDate').val()==='')$('#field-serviceEndDate').datepicker('setDate', 'today');
        $("#field-serviceRate").numeric();
    });
</script>