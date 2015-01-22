<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 12/15/14
 * Time: 2:45 PM
 */
?>

<h3><?php echo $pageTitle; ?></h3>
<?php echo $output; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#collapseIT-Inventory").removeClass("in").addClass("in");
    });
</script>