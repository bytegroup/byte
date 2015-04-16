<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/13/15
 * Time: 12:27 PM
 */
?>
<style type="text/css">
    div.dataTables_wrapper div.damage-process-buttons{float: left;}
    table.dataTable td:first-child{width: 20px;}
    #damage-container{width: 100%; height: auto; border: 0px #0000FF solid; float: left; color: #008200 ;}
    #damage-container h4{border-bottom: 1px #008200 solid}
    #damage-container ul{list-style: none; display: inline-block;}
    #damage-container ul li{padding: 5px; float: left;text-align: center; width: 200px;}
</style>
<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script language="JavaScript">
    $(document).ready(function(e){
        var items = {};
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $("table.dataTable input[name='check']").remove();
        $("table.dataTable button.refresh-data").remove();

        $("div.dataTables_wrapper").prepend('' +
        '<div class="damage-process-buttons">' +

        '<a href="#" class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button"> <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text">&nbsp;Damage Requisition(sell)</span> </a>' +
        '&nbsp; &nbsp;' +
        '<a href="#" class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button"> <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text">&nbsp;Add Damage Dump</span> </a>' +
        '</div>' +
        '');
        $('div.dataTables_wrapper').append(
            '' +
            '<div id="damage-container">' +
            '<h4>Selected Products:</h4>' +
            '<ul>' +
            '' +
            '</ul>' +
            '<form method="post" action="<?php echo base_url(IT_MODULE_FOLDER.'requisition_for_damage/index/add');?>">' +
            '<input type="hidden" id="damagedItems" name="damagedItems" value=""/>' +
            '</form>' +
            '</div>'
        );
        var itemCheckBox = $('input[name="damageDetailIds[]"]');
        var itemContainer= $('div#damage-container ul');
        populate_container();
        itemCheckBox.change(function(){
           populate_container();
        });

        $('.damage-process-buttons a').click(function(){
            //console.log(items);
            //console.log(JSON.stringify(items));
            $('#damage-container form input#damagedItems').val(JSON.stringify(items));
            $('#damage-container form').submit();
        });

        function populate_container(){
            itemContainer.html('');
            items = {};
            itemCheckBox.each(function(){
                if($(this).is(':checked') && !$(this).is(':disabled')){
                    var itemCode = $(this).parent().next().html();
                    items[$(this).val()]=itemCode;
                    itemContainer.append('' +
                    '<li>'+itemCode+'</li>' +
                    '');
                }
            });
        }
    });
</script>