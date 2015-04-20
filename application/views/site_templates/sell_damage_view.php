<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 4/20/15
 * Time: 3:29 PM
 */
?>
<style type="text/css">
    #items_input_box ul{width: 100%;list-style: none; margin: 0;}
    #items_input_box ul li ul.items-table-header{background-color: #a9dba9;}
    #items_input_box ul li ul li{display: inline-block;padding: 0 4px; width: 100px; text-align: center;}
    #items_input_box ul li ul li:first-child{width: 200px; text-align: left;}
    #items_input_box ul li:last-child{font-weight: bold; text-align: right; color: #356635;}
    #items_input_box ul li ul{border-bottom: 1px #9acc9a solid;}
    #items_input_box ul li ul.items-table-header{text-align: center;}
    #items_input_box ul ul li input[type="number"]{width: 90px; margin-top: 8px;}
    #items_input_box ul li span{display: inline-block!important; border-bottom: 3px #356635 double; }
</style>
<h3><?=$pageTitle?></h3>
<?=$output?>

<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");

        $(".form-content form div.buttons-box").prepend(
            '<div class="form-button-box">'
            + '<input type="button" value="Back to List" class="ui-input-button" id="back-to-list">'
            + '</div>'
        );
        $("input#back-to-list").click(function(){
            window.location = "<?php echo $cancel;?>";
        });
    });
</script>