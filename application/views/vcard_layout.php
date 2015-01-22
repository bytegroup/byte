<?php /*Only for Home page*/ ?>

<?php
include(TEMPLATES_FOLDER."header.html");
include(TEMPLATES_FOLDER."breadcrumb.html");
?>
<!-- START CONTENT -->
    <div class="container">
      <div class="row-fluid">
          <!-- sidebar -->
          <?php 
          include(TEMPLATES_FOLDER."sidebar_main.html");
          ?>
          <!-- side bar ends -->
    <!-- inner main container -->
        <div class="span9">
        <?php 
        include(SITE_TEMPLATES.$body_template);
        ?>
        </div><!-- span9 -->    

<!-- END CONTENT -->
<?php
include(TEMPLATES_FOLDER."footer.html");
?>
