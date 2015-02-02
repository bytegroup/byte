<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/10/14
 * Time: 4:34 PM
 */
?>

<style>
    #field_HODUserId_chzn{width: 305px!important;}
    #HODUserId_input_box select{width: 305px!important;}
</style>

<h3><?=$pageTitle?></h3>
<?=$output?>

<!-- Code to handle the server response (see test.php) -->
<script language="JavaScript">
    $(document).ready(function(e){
        //console.log(state);
        $("#collapseAdmin").removeClass("in").addClass("in");

        var state= '<?php echo $state;?>';
        <?php
            if(isset($dependency))
                foreach($dependency as $dependent){ ?>
                    get_dependency_data('<?php echo $dependent['URL'];?>', '<?php echo $dependent['sourceId'];?>', '<?php echo $dependent['targetId'];?>');
        <?php } ?>


        function get_dependency_data(url, sourceId, targetId){
            var source= $('select[name="'+sourceId+'"]');
            var target= $('select[name="'+targetId+'"]');
            $('#'+sourceId+'_input_box').append(
                '<img src="<?php echo $ajaxLoaderURL; ?>" border="0" id="'+sourceId+'_ajax_loader" class="dd_ajax_loader" style="display: none;">'
            );

            if(state=='add'){$('#'+targetId+'_input_box').hide();
            target.children().remove().end();}
            source.change(function(){
                var targetTableId=this.value;
                if(!targetTableId){
                    target.find('option').remove();
                }

                $('#'+sourceId+'_ajax_loader').show();
                target.find('option').remove();
                $.getJSON(
                    url + targetTableId,
                    function(data){
                        target.append('<option value=""></option>');
                        $.each(data, function(key, val) {
                            target.append(
                                $('<option></option>').val(val.value).html(val.property)
                            );
                            $('#'+targetId+'_input_box').show();
                        });
                    }
                )
                    .always(function() {
                        $('#'+targetId+'_input_box').show();
                        source.each(function(){
                            $(this).trigger("liszt:updated");
                        });
                        target.each(function(){
                            $(this).trigger("liszt:updated");
                        });
                        $('#'+sourceId+'_ajax_loader').hide();
                    });
            });
        }
    });

</script>