<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 11/26/14
 * Time: 7:20 PM
 */
?>
<style type="text/css">#profilePicture_input_box img{width: 200px; height: 250px;}</style>

    <h3><?php echo $pageTitle?></h3>
    <?=$output?>
    <!-- Code to handle the server response (see test.php) -->

    <script language="JavaScript">
        $(document).ready(function(){
            $("#collapseStaff").removeClass("in").addClass("in");

            <?php if($state=="read"){ ?>
            var element = $('#field-profilePicture').find('a');
            element.html('<img src="'+element.attr('href')+'" style="width: 70px; height: 70px;" />');
            <?php } ?>

            var companyURL    ='<?php echo base_url(ADMIN_FOLDER)?>/staff/ajax_get_company/';
            var departmentURL ='<?php echo base_url(ADMIN_FOLDER)?>/staff/ajax_get_department/';
            $('#field-organizationId').change(function(){
                filterOptions('organizationId', 'companyId', companyURL);
                filterOptions('companyId', 'departmentId', departmentURL);
            });
            $('#field-companyId').change(function(){
                filterOptions('companyId', 'departmentId', departmentURL);
            });
        });

        function filterOptions(source, target, url){
            var ID= $('#field-' + source).val();
            var $el = $('#field-' + target);
            if(ID==null || ID=='')ID=0;
            $('#'+source+'_input_box').append('<img src="<?php echo base_url()?>ajax-loader.gif" border="0" id="'+source+'_ajax_loader" class="dd_ajax_loader" style="display: none;">');
            $('#'+source+'_ajax_loader').show();
            $el.empty();
            $.post(
                url + ID,
                {},
                function(data){
                    $el.append('<option value=""></option>');
                    if(data != null){
                        $.each(data, function(key, val) {
                            $el.append($('<option></option>')
                                .attr('value', val.value).text(val.property));
                        });
                    }
                },
                'json'
            )
                .fail(function() {
                    //alert( "error" );
                })
                .always(function() {
                    $('#'+source+'_ajax_loader').hide();
                    $el.chosen().trigger('liszt:updated');
                });
        }
    </script>




<?php  /*
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
}  */
?>