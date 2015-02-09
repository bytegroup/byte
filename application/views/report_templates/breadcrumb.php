<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/9/15
 * Time: 8:08 PM
 */
?>
<!-- top nav bar -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">SAPL &amp; OCL Back-Office</a>
            <div class="btn-group pull-right">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-user"></i> <?php echo $this->my_session->userName;?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $base_url.ADMIN_FOLDER;?>profile">Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $base_url.ADMIN_FOLDER;?>home/logout">Sign Out</a></li>
                </ul>
            </div>
            <div class="nav-collapse">
                <ul class="nav">
                    <li><a href="<?php echo $base_url;?>">Main Home</a></li>
                    <?php
                    $permission_array= $this->my_session->get_modules($this->my_session->permissions);
                    foreach($permission_array as $key=>$val){ ?>
                        <?php if ($val=='Admin'):?>
                            <li><a href="<?php echo base_url(ADMIN_FOLDER);?>"><?php echo $val;?></a></li>
                        <?php elseif ($val=='Staff'):?>
                            <li><a href="<?php echo base_url(ADMIN_FOLDER.strtolower($val));?>"><?php echo $val;?></a></li>
                        <?php else: ?>
                            <li><a href="<?php echo base_url(strtolower($key));?>"><?php echo $val;?></a></li>
                        <?php endif ?>
                    <?php   } ?>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<!-- nav bar ends -->
