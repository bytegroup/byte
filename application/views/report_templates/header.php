<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/9/15
 * Time: 7:08 PM
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle; ?> .:::. Back-Office Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo $base_url.ASSETS_FOLDER; ?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $base_url.ASSETS_FOLDER; ?>style.css" rel="stylesheet">
    <?php echo $css; ?>
    <?php /*
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach;*/ ?>

    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
        .nav-list > li > a {
            padding: 3px;
        }
        .accordion-heading .accordion-toggle {
            display: block;
            padding: 8px 5px;
        }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
     <!-- <script src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>-->
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">

    <link rel="stylesheet" href="<?php echo $base_url.REPORT_ASSETS;?>css/jquery.dataTables.css"/>
    <link rel="stylesheet" href="<?php echo $base_url.REPORT_ASSETS;?>css/jquery.dataTables_themeroller.css"/>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <link rel="stylesheet" href="http://cdn.datatables.net/plug-ins/f2c75b7247b/integration/jqueryui/dataTables.jqueryui.css"/>
    <link rel="stylesheet" href="<?php echo $base_url.REPORT_ASSETS;?>TableTools/css/dataTables.tableTools.css"/>

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/plug-ins/f2c75b7247b/integration/jqueryui/dataTables.jqueryui.js"></script>
    <script type="text/javascript" src="<?php echo $base_url.REPORT_ASSETS;?>TableTools/js/dataTables.tableTools.js"></script>
    <script type="text/javascript" src="<?php echo $base_url.REPORT_ASSETS;?>tableToExcel.js"></script>

    <script type="text/javascript" src="<?php echo $base_url.ASSETS_FOLDER;?>js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url.ASSETS_FOLDER;?>js/jquery.numeric.min.js"></script>

    <?php /*foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; */?>
    <?php echo $js;?>

    <meta http-equiv="expires" content="-1" />
    <meta http-equiv= "pragma" content="no-cache" />
    <meta name='robots' content='all' />
    <meta name='author' content='DSi' />
    <meta name='description' content='Music Generator' />
</head>
<body>
