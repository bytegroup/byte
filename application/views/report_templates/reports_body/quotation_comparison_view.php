<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/17/15
 * Time: 12:46 PM
 */
?>
<style type="text/css">
    .dataTable td{border-left: 1px #ddddff solid;}
    .dataTable th{border-left: 1px #ddddff solid;}
    .dataTable th:last-child{border-right: 1px #ddddff solid;}
    .dataTable td:last-child{border-right: 1px #ddddff solid;}
    .dataTable thead tr th{border-top:0px #000 solid; text-align: center;}
    .dataTable tfoot tr th{border-bottom:1px #000 solid;}
    /*.dataTable thead tr th.comparison-header{background-image: none; background-color: #9acc9a; }*/
    .dataTable tfoot input {  width: 100%;  padding: 3px;  box-sizing: border-box;  }
</style>
<div class="" style="width: auto; height: auto; border: 0px #999 solid; float: right">
    <div class="form-button-box">
        <input type="button" value="Back to Requisition List" onclick="window.location='<?php echo $backToList; ?>'" class="ui-input-button" id="">
    </div>
</div>
<h3><?php echo $pageTitle; ?></h3>
<?php
$rows= $data;
?>
<table id="report-table" class="display" width="100%" cellspacing="0">
    <thead>
    <?php
    foreach($metadata as $title=>$value){ ?>
        <tr>
            <th colspan="2"><?php echo $title;?></th>
            <td colspan="<?php echo count($headers)-2;?>"><?php echo $value;?></td>
        </tr>
    <?php } ?>

    <tr bgcolor="#333 ">
        <th colspan="4"></th>
        <?php foreach($vendors as $vendor){?>
            <th colspan="9" class="comparison-header"><?php echo $vendor; ?></th>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach($headers as $header){?><th class="report-header"><?php echo $header;?></th><?php } ?>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <?php foreach($headers as $header){?><th><?php echo $header;?></th><?php } ?>
    </tr>
    </tfoot>

    <tbody>
    <?php foreach($rows as $fields): ?>
        <tr>
            <?php foreach($fields as $field){?>
                <td><?php echo $field;?></td>
            <?php } ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
<script type="text/javascript" src="<?php echo $table_js;?>"></script>
<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseReport").removeClass("in").addClass("in");

        $('div.DTTT_container a#excelDownload').click(function(){
            window.location= '<?php echo base_url(REPORT_FOLDER.'quotation_comparison/get_excel/'.$requisitionId);?>';
        });
    });

</script>