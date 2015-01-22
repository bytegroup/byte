<?php
include(TEMPLATES_FOLDER."header.html");
?>
<!-- START CONTENT -->
    <div class="container-fluid">
      <div class="row-fluid">          
    <!-- inner main container -->
        <div class="span12">
            <table cellpadding="0" cellspacing="1" border="0" align="center" style="width:400px">
                <tr>
                    <td style="text-align: center"><h1 class="well">Back Office Panel</h1></td>
                </tr>    
            </table>
            <form class="forms" id="loginform" action="<?=$base_url."home/login"?>" method="post">
            <table cellpadding="0" cellspacing="1" border="0" align="center" style="width:400px" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <td colspan="2"  style="text-align: center"><h3><?php echo $pageTitle; ?></h3></td>
                </tr>
                </thead>
                <tr>
                    <td class="td" width="100px"><strong>User Name</strong></td>
                    <td class="td">
                        <input type="text" name="username" class="input"/>
                    </td>
                </tr>
                <tr>
                    <td class="td"><strong>Password</strong></td>
                    <td class="td">
                        <input type="password" name="password" class="input"/>
                    </td>
                </tr>
                <!--<tr>
                    <td class="td"><strong>Organization</strong></td>
                    <td class="td">
                        <select name="organizationId"><?php //$orgList ?></select>
                    </td>
                </tr>-->
                <tr>
                    <td class="td"></td>
                    <td class="td">
                        <button type="submit" name="login" class="btn btn-success">
                            <i class="icon-user"></i> Log In
                        </button> or <a href="#" id="fp">?Forgot Password</a> &nbsp;
                        <span class="small-text" style="color: #990000;"><?=$this->my_session->getLoginError()?></span>
                    </td>
                </tr>
            </table>
            </form>
        </div><!-- span9 -->    

<!-- END CONTENT -->
<?php
include(TEMPLATES_FOLDER."footer.html");
?>