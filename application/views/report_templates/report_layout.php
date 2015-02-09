<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/9/15
 * Time: 6:52 PM
 */
?>

<?php
include("header.php");
include("breadcrumb.php");
?>
    <!-- START CONTENT -->
<div class="container-fluid">
    <div class="row-fluid">
    <!-- sidebar -->
    <?php
    include("sidebar.php");
    ?>
    <!-- side bar ends -->
    <!-- inner main container -->
    <div class="span10">
        <?php
        include(REPORT_BODY.$body_template);
        ?>
    </div><!-- span9 -->

    <!-- END CONTENT -->
<?php
include("footer.php");
?>