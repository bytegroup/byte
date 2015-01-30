<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/29/14
 * Time: 12:47 PM
 */
?>

<h3><?=$pageTitle?></h3>
<?=$output?>


<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
        $(".form-content form div.buttons-box").prepend(
            '<div class="form-button-box">'
            + '<input type="button" value="Back to Receive" class="ui-input-button" id="cancel-bill">'
            + '</div>'
        );
        $("input#cancel-bill").click(function(){
            window.location = "<?php echo $cancelURL;?>";
        });
    });
</script>