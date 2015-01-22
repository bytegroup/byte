<?php /*Inner pages*/ ?>

<?php
include(TEMPLATES_FOLDER."header.html");
include(TEMPLATES_FOLDER."breadcrumb.html");
?>
<!-- START CONTENT -->
    <div class="container-fluid">
      <div class="row-fluid">
          <!-- sidebar -->
          <?php 
          include(TEMPLATES_FOLDER."sidebar_left.php");
          ?>
          <!-- side bar ends -->
    <!-- inner main container -->
        <div class="span10">
        <?php 
        include(SITE_TEMPLATES.$body_template);
        ?>
        </div><!-- span9 -->    

<!-- END CONTENT -->
<?php
include(TEMPLATES_FOLDER."footer.html");
?>