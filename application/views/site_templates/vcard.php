
<script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>

<style>
    table td{width: 20%;}
    div.home-icons ul{display: inline; float: left; list-style: none;}
    div.home-icons li{line-height: 40px; float: left; padding: 10px 45px;text-align: center;}
    div.home-icons a:hover{
        text-decoration: none;
    }
    div.home-icons a:hover img{
        filter: brightness(90%);
        -webkit-filter: brightness(90%);
        -moz-filter: brightness(90%);
        -o-filter: brightness(90%);
        -ms-filter: brightness(90%);
    }

</style>

<h3 style="border-bottom: 1px #0088cc solid; color: #0088cc;"><?php echo $pageTitle;?></h3>
<div class="home-icons">
    <?php //var_dump($permissions);?>
    <ul>
        <?php

            foreach($permissions as $key=>$val){ ?>
                <?php if ($val=='Admin'):?>
                <li>
                    <a id="" href="<?php echo base_url(ADMIN_FOLDER);?>"><img src="<?php echo base_url(ICONS.strtolower($val).'.gif'); ?>" width="60" height="60" border="0">
                    <br><?php echo $val;?>
            </a>
                </li>
                <?php elseif ($val=='Staff'):?>
                    <li>
                    <a id="" href="<?php echo base_url(ADMIN_FOLDER.strtolower($val));?>"><img src="<?php echo base_url(ICONS.strtolower($val).'.gif'); ?>" width="60" height="60" border="0">
                        <br><?php echo "Employee Management";?>
                    </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a id="" href="<?php echo base_url(strtolower($key));?>"><img src="<?php echo base_url(ICONS.strtolower($key).'.gif'); ?>" width="60" height="60" border="0">
                            <br><?php echo $val;?>
                        </a>
                    </li>
                <?php endif ?>

       <?php     }

        ?>
        <?php /* if(isset($this->my_session->permissions['canViewHEDMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'HED.gif'); ?>" width="60" height="60" border="0">
                <br>HED
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewDMDDMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'DMDD.gif'); ?>" width="60" height="60" border="0">
                <br>DMDD
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewOEMMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'OEM.gif'); ?>" width="60" height="60" border="0">
                <br>OEM
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewIT-InventoryMenu'])){ ?><li>
            <a id="" href="<?php echo base_url().IT_MODULE_FOLDER?>item_master"><img src="<?php echo base_url(ICONS.'IT.gif'); ?>" width="60" height="60" border="0">
                <br>IT Inventory
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewALMSMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'LMS.gif'); ?>" width="60" height="60" border="0">
                <br>LMS
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewSystemControlMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'SYSTEMCONTROL.gif'); ?>" width="60" height="60" border="0">
                <br>Access Control System
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewCallLogMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'CALL.gif'); ?>" width="60" height="60" border="0">
                <br>Call Log
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewReceiptMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'RECEIPT.gif'); ?>" width="60" height="60" border="0">
                <br>Central Receipt
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewSecurityControlMenu'])){ ?><li>
            <a id="" href=""><img src="<?php echo base_url(ICONS.'SECURITY.gif'); ?>" width="60" height="60" border="0">
                <br>Security Management
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewAdmin-SetupMenu'])){ ?><li>
            <a id="" href="<?php echo base_url().ADMIN_FOLDER?>home"><img src="<?php echo base_url(ICONS.'ADMIN.gif'); ?>" width="60" height="60" border="0">
                <br>Admin
            </a>
        </li><?php } ?>
        <?php if(isset($this->my_session->permissions['canViewAdmin-SetupMenu'])){ ?><li>
            <a id="" href="<?php echo base_url().ADMIN_FOLDER?>staff"><img src="<?php echo base_url(ICONS.'ADMIN.gif'); ?>" width="60" height="60" border="0">
                <br>Staff
            </a>
        </li><?php } */?>
    </ul>
</div>

<script type="text/javascript">
  
  
</script>
