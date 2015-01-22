<style>
	.change_action_style{
		margin-top:50px;
	}
</style>
<h3><?=$pageTitle?></h3>
<?=$output?>

<div style="margin-top:20px;">
	<a class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button"  id="quotationListBtn" href="<?=IT_MODULE_FOLDER.'quotation/index'?>">
    	<span class="ui-button-icon-primary ui-icon approve_modal Aea6edafc"></span>
		<span class="ui-button-text"> Quotation List</span>
    </a>
    <a class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button"  id="quotationAddBtn" href="<?=IT_MODULE_FOLDER.'quotation/add_Quotation'?>">
    	<span class="ui-button-icon-primary ui-icon approve_modal Aea6edafc"></span>
		<span class="ui-button-text">Add a Quotation</span>
    </a>
    
    <a class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button"  id="quotationAddBtn" href="<?=IT_MODULE_FOLDER.'work_order/add_Work_Order'?>">
    	<span class="ui-button-icon-primary ui-icon approve_modal Aea6edafc"></span>
		<span class="ui-button-text">Add a Work Order</span>
    </a>
</div>
<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">        
       
	$(document).ready(function(e){
    	$("#collapseOne").removeClass("in").addClass("in");
		$("#collapseTwo").removeClass("in");
		
		

		$("a#approve_modal").click(function() {            
            var href = $(this).attr('href');
			var hrefLoc = $(this).attr('href');
            href = href.substr(href.lastIndexOf('/'));
            show_approve_info(hrefLoc);
            return false;
        });
		
		                
	});       
    function show_approve_info(href){            
         $.fancybox({
            "href" : href
         });         
    }   
</script>
