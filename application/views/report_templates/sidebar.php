<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 2/9/15
 * Time: 7:09 PM
 */
?>
<div class="span2" id="sidebardiv">
    <div id="innernav" style="padding:19px">
        <div id="accordion2" class="accordion" style="margin-bottom:0px;padding-bottom:0px">
            <style type="text/css">
                .accordion-inner{padding: 9px 5px;}
                .nav-list{padding: 0;}
                .accordion-inner .nav-list li.divider{margin: 2px 1px;}
            </style>

            <?php
            $permission_array= $this->my_session->get_modules($this->my_session->permissions);
            foreach($permission_array as $key=>$val){
                $this->load->view(TEMPLATES_FOLDER.USERGROUP_MENU_FOLDER.strtolower($key).".html");
            }
            //$this->load->view(TEMPLATES_FOLDER.USERGROUP_MENU_FOLDER."1.html");

            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    <?php foreach($permission_array as $key=>$val):?>
                    $("#collapse<?php echo $key;?>").removeClass("in");
                    <?php endforeach; ?>
                });
            </script>
        </div>
    </div>
</div><!--/span-->
