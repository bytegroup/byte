<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/4/15
 * Time: 12:32 PM
 */
?>
<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        //var today=
        if($('#field-serviceAgreementStartDate').val()==='')$('#field-serviceAgreementStartDate').datepicker('setDate', 'today');
        //if($('#field-serviceEndDate').val()==='')$('#field-serviceEndDate').datepicker('setDate', 'today');
        $("#field-serviceAgreementAmount").numeric();
        $("#field-serviceAgreementType").change(function(){
            if($(this).val()==='Normal'){
                $('#field-serviceAgreementStartDate').val('').prop('disabled', true);
                $('#field-serviceAgreementEndDate').val('').prop('disabled', true);
                $('#field-serviceAgreementAmount').val('').prop('disabled', true);
            }else{
                $('#field-serviceAgreementStartDate').datepicker('setDate', 'today').prop('disabled', false);
                $('#field-serviceAgreementEndDate').val('').prop('disabled', false);
                $('#field-serviceAgreementAmount').val('').prop('disabled', false);
            }
        });
    });
</script>