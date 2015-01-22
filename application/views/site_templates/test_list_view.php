<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/3/14
 * Time: 8:54 PM
 */
/*
?>
<style type="text/css">
    #backgroundPopup {
        position: fixed;
        display:none;
        height:100%;
        width:100%;
    }
    #toPopup {
        background: none repeat scroll 0 0 #FFFFFF;
        border: 1px solid #ccc;
        display: none;
        font-size: 14px;
        left: 70%;
        margin-left: -402px;
        position: fixed;
        top: 10%;
        width: 750px;
        z-index: 2;
    }
</style>
 */ ?>

<h3><?php echo 'Test List'; ?></h3>
<?php echo $output ; ?>


<?php /*
<button type="button" name="test-popup" id="popup">test</button>

<a href="#" class="topopup"><button>type a name</button></a>
<div id="toPopup">
    <div>
        <?php echo $output ?>
        <button id="save">save</button>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {

        $("a.topopup").click(function() {
            loading(); // loading
            setTimeout(function(){ // then show popup, deley in .5 second
                loadPopup(); // function show popup
            }, 500); // .5 second
            return false;
        });

        function loading() {
            $("div.loader").show();
        }
        function closeloading() {
            $("div.loader").fadeOut('normal');
        }

        var popupStatus = 0; // set value

        function loadPopup() {
            if(popupStatus == 0) { // if value is 0, show popup
                closeloading(); // fadeout loading
                $("#toPopup").show(); // fadein popup div
            }
        }

        function disablePopup() {
            if(popupStatus == 1) { // if value is 1, close popup
                $("#toPopup").fadeOut("normal");
                $("#backgroundPopup").fadeOut("normal");
                popupStatus = 0;  // and set value to 0
            }
        }

        $("#save").on("click",function(){
            $("a.topopup").after("<br/>" + $("#someId").val());
            $("#toPopup").remove();
        });
    });
</script>

 */ ?>