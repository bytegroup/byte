<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/5/14
 * Time: 12:43 PM
 */
?>
<style type="text/css">
    #permissions_input_box ul{list-style: none;}
    li.header-permission span h5{display: inline-block;}
    span.sub-permission{margin-left: 20px;}
</style>

    <h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#collapseAdmin").removeClass("in").addClass("in");

        $(".form-content form div.buttons-box").prepend(
            '<div class="form-button-box">'
               + '<input type="button" value="Back to List" class="ui-input-button" id="cancel-permissions">'
           + '</div>'
        );
        $("input#cancel-permissions").click(function(){
            window.location = "<?php echo $cancel;?>";
        });

        $('li.header-permission input[type="checkbox"]').each(function(){
            var currentBox= $(this);
            var currentBoxValue= currentBox.val();
            if(currentBox.is(":checked"))$('li.header-permission ul.'+currentBoxValue).show();
            else $('li.header-permission ul.'+currentBoxValue).hide();
        });

        $('li.header-permission input[type="checkbox"]').change(function(){
            var currentBox= $(this);
            var currentBoxValue= currentBox.val();
            if(currentBox.is(":checked")){
                $('li.header-permission ul.'+currentBoxValue).show();
                $('li.header-permission ul.'+currentBoxValue+ ' input[type="checkbox"]').each(function(){
                    $(this).prop('checked', true);
                });
            }
            else {
                $('li.header-permission .'+currentBoxValue).hide();
                $('li.header-permission ul.'+currentBoxValue+ ' input[type="checkbox"]').each(function(){
                    $(this).prop('checked', false);
                });
            }
        });
    });
</script>