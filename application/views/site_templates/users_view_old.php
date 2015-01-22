<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/4/14
 * Time: 5:56 PM
 */
?>

<style type="text/css">
    a#name-selectUser{cursor: pointer;font-size: smaller;}
    #backgroundPopup {
        position: fixed;
        display:none;
        height:100%;
        width:100%;
    }
    #listPopup {
        background: none repeat scroll 0 0 #FFFFFF;
        border: 1px solid #ccc;
        display: none;
        font-size: 14px;
        left: 0%;
        margin-left: 0;
        position: fixed;
        top: 0%;
        width: 100%;
        height: 100%;
        z-index: 2;
    }
</style>

<h3><?php echo $pageTitle; ?></h3>
<div id="listPopup">
    <div id="user-list-popup" >

    </div>
    <button type="button" id="user-select-popup">save</button>
    <button type="button" id="user-cancel-popup">cancel</button>
</div>

<?php echo $output; ?>
<!-- Code to handle the server response (see test.php) -->



<?php if(isset($dd_state) && ($dd_state == 'add' || $dd_state == 'edit')){ ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#field-selectUser").append("<span></span><a id='name-selectUser'>Click to Select User</a>");
            $("#name-selectUser").click(function(){
                $.ajax({
                    url: '<?php echo base_url();?>site-admin/users/test_list/',
                    data: {
                        format: 'html'
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#listPopup").hide();
                        alert('Internal error: ' + jqXHR.responseText);
                    },
                    success: function(data) {
                        $("#listPopup").show();
                        $("#user-list-popup").html(data);
                    },
                    type: 'GET'
                });
                /*$.getJSON(
                 '<?php //echo base_url();?>site-admin/users/get_users/2',
                 function(data) {
                 alert(JSON.stringify(data));
                 })
                 .done(function(){alert('getJSON request succeeded!');})
                 .fail(function(jqXHR, textStatus, errorThrown) { alert('getJSON request failed! ' + jqXHR.responseText); })
                 .always(function() { alert('getJSON request ended!'); });
                 $("#field-userId").val(23);
                 $("#name-selectUser").text("Click to change");
                 $("#field-selectUser span").html("23  ");*/
            });
            $("#user-cancel-popup").click(function(){
                $("#listPopup").hide();
            });
        });
    </script>

<?php } ?>



<script language="JavaScript">
    <?php /*
    if($state == "add" || $state == "edit"){ ?>
        $(document).ready(function(e){
            $("#gender_input_box select[name='gender']").chosen();
            $("#collapseTwo").removeClass("in").addClass("in");
            $("#collapseOne").removeClass("in");
        });
    <?php
    }else{
    ?>
        $(document).ready(function(e){
            $("#collapseTwo").removeClass("in").addClass("in");
            $("#collapseOne").removeClass("in");
        });
    <?php
    }*/
    ?>
</script>
<?php /*
if(isset($dd_state) && ($dd_state == 'add' || $dd_state == 'edit')) {
//DONT HAVE TO EDIT THE CODE BELOW IF YOU DONT WANNA :P
echo '<script type="text/javascript">';
echo '$(document).ready(function(e) {';
for($i = 0; $i <= sizeof($dd_dropdowns)-1; $i++):
	//SET VARIABLES
	echo 'var '.$dd_dropdowns[$i].' = $(\'select[name="'.$dd_dropdowns[$i].'"]\');';
	//SET LOADING IMAGES
	if($i != sizeof($dd_dropdowns)-1) {
		echo '$(\'#'.$dd_dropdowns[$i].'_input_box\').append(\'<img src="'.$dd_ajax_loader.'" border="0" id="'.$dd_dropdowns[$i].'_ajax_loader" class="dd_ajax_loader" style="display: none;">\');';
	}

	if($i > 0 && $dd_state == 'add') {
		//HIDE ALL CHILD ITEMS
		echo '$(\'#'.$dd_dropdowns[$i].'_input_box\').hide();';
		//REMOVE CHILD OPTIONS
		echo $dd_dropdowns[$i].'.children().remove().end();';
	}
endfor;

for($i = 1; $i <= sizeof($dd_dropdowns)-1; $i++):
	//CHILD DROPDOWNS
	echo $dd_dropdowns[$i-1].'.change(function() {';
	echo 'var select_value = this.value;';
	//SHOW LOADING IMAGE
	echo '$(\'#'.$dd_dropdowns[$i-1].'_ajax_loader\').show();';
	//REMOVE ALL CURRENT OPTIONS FROM CHILD DROPDOWNS
	echo $dd_dropdowns[$i].'.find(\'option\').remove();';
	//POST TO A CUSTOM CONTROLLER ADDING OPTIONS | JSON
	echo 'var myOptions = "";';
	//GET JSON REQUEST OF STATES
	echo '$.getJSON(\''.$dd_url[$i].'\'+select_value, function(data) {';
	//APPEND RECEIVED DATA TO STATES DROP DOWN LIST
	echo $dd_dropdowns[$i].'.append(\'<option value=""></option>\');';
	echo '$.each(data, function(key, val) {';
	echo $dd_dropdowns[$i].'.append(';
	echo '$(\'<option></option>\').val(val.value).html(val.property)';
	echo ');';
	echo '});';
	//SHOW CHILD SELECTION FIELD
	echo '$(\'#'.$dd_dropdowns[$i].'_input_box\').show();';
	//MAKE SURE CITY STILL HIDDEN INCASE OF COUNTRY CHANGE
	for($x = $i+1; $x <= sizeof($dd_dropdowns)-1; $x++):
		echo '$(\'#'.$dd_dropdowns[$x].'_input_box\').hide();';
	endfor;
	//RESET JQUERY STYLE OF DROPDOWN LIST WITH NEW DATA
	echo $dd_dropdowns[$i-1].'.each(function(){';
	echo '$(this).trigger("liszt:updated");';
	echo '});';
	echo $dd_dropdowns[$i].'.each(function(){';
	echo '$(this).trigger("liszt:updated");';
	echo '});';
	//HIDE LOADING IMAGE
	echo '$(\'#'.$dd_dropdowns[$i-1].'_ajax_loader\').hide();';
	echo '});';
	echo '});';
endfor;
    echo '});';
echo '</script>';

}*/

?>

<script>

    //for getting user list against the department
    /*$(document).ready(function(e) {
     var organizationId = $('select[name="organizationId"]');
     var departmentId= $('select[name="departmentId"]');
     $('#departmentId_input_box').append('<img src="http://localhost/ocl-backoffice/ajax-loader.gif" border="0" id="departmentId_ajax_loader" class="dd_ajax_loader" style="display: none;">');
     var selectUser = $('select[name="departmentId"]');
     $('#selectUser_input_box').hide();
     selectUser.children().remove().end();
     departmentId.change(function() {
     var select_value = this.value;
     $('#departmentId_ajax_loader').show();
     selectUser.find('option').remove();
     var myOptions = "";
     $.getJSON(
     'http://localhost/ocl-backoffice/site-admin/users/get_users/'+select_value,
     function(data) {
     selectUser.append('<option value=""></option>');
     $.each(
     data,
     function(key, val) {
     selectUser.append($('<option></option>').val(val.value).html(val.property));
     });
     $('#departmentId_input_box').show();
     departmentId.each(function(){
     $(this).trigger("liszt:updated");
     });
     selectUser.each(function(){
     $(this).trigger("liszt:updated");
     });
     $('#departmentId_ajax_loader').hide();
     });
     });
     });*/
</script>


