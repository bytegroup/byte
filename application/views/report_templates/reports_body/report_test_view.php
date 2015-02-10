<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 4:38 PM
 */
?>

<h3><?php echo $pageTitle; ?></h3>
<?php
$rows= $data;
?>
<table id="example" class="display" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Title</th>
            <th>Req. No.</th>
            <th>Date</th>
            <th>Req. For</th>
            <th>Company</th>
            <th>Description</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>Title</th>
            <th>Req. No.</th>
            <th>Date</th>
            <th>Req. For</th>
            <th>Company</th>
            <th>Description</th>
        </tr>
    </tfoot>

    <tbody>
    <?php foreach($rows as $row): ?>
        <tr>
            <td><?php echo $row->requisitionTitle;?></td>
            <td><?php echo $row->requisitionNumber;?></td>
            <td><?php echo $row->requisitionCreateDate;?></td>
            <td><?php echo $row->requisitionFor;?></td>
            <td><?php echo $row->companyId;?></td>
            <td><?php echo $row->requisitionDescription;?></td>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>


<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseReport").removeClass("in").addClass("in");

        $('#example').dataTable();
    });
</script>