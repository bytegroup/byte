<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/1/15
 * Time: 4:38 PM
 */
?>

<h3><?php echo $pageTitle; ?></h3>
<div id="report-search">
    <form id="report-search-form" action="">
        <input type="text"  name="preDate"/>
        <input type="text"  name="currentDate"/>
    </form>
</div>
<?php //echo $output; ?>



<script language="JavaScript">
    $(document).ready(function(e){
        $("#collapseReport").removeClass("in").addClass("in");
        $("#report-search-form input").click(function(){
            //alert('testes');
        });
        $("#report-search-form input").datepicker();
    });
</script>