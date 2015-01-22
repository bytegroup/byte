
<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>
<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
        <?php
        if($state == "add" || $state == "edit"){ ?>
        	$(document).ready(function(e){
				//$("#gender_input_box select[name='gender']").chosen();
				$("#collapseAdmin").removeClass("in").addClass("in");
            });
        <?php
        }else{
        ?>
			$(document).ready(function(e){
    			$("#collapseAdmin").removeClass("in").addClass("in");

			});
		<?php
        }
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


