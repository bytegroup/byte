<form class="forms" name="facultyform" id="form1" action="" method="post">
<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">Information of <?=ucfirst($uinfo['firstName'].' '.$uinfo['lastName'])?></th>                
    </tr>
    <tr>
        <td class="td"><label>User Name</label></td>
        <td class="td">
            <?=$uinfo['userName']?>             
        </td>
    </tr>
    <tr>
        <td class="td"><label>First Name</label></td>
        <td class="td">
            <?=$uinfo['firstName']?>
        </td>
    </tr>
    <tr>
        <td class="td"><label>Last Name</label></td>
        <td class="td">
            <?=$uinfo['lastName']?>
        </td>
    </tr>
    <tr>
        <td class="td"><label>Middle Name</label></td>
        <td class="td">
            <?=$uinfo['middleName']?>
        </td>
    </tr>            
    <tr>
        <td class="td"><label>Gender</label></td>
        <td class="td">
            <?=ucfirst($uinfo['gender'])?>             
        </td>
    </tr>
    <tr>
        <th colspan="2">Contact Information</th>                
    </tr>
    <tr>
        <td class="td"><label>Email</label></td>
        <td class="td">
            <input type="text" name="email" id="email" class="input-long" value="<?=$uinfo['email']?>" />
        </td>
    </tr>
    <tr>
        <td class="td"><label>Phone</label></td>
        <td class="td">
            <input type="text" name="phone" id="phone" class="input-long" value="<?=$uinfo['phone']?>" />
        </td>
    </tr>    
    <tr>
        <td class="td"><label>Address</label></td>
        <td class="td">
            <?=$uinfo['address']?>
        </td>
    </tr>                                               
    <tr>
        <td class="td"><div class="loading" id="load"></div></td>
        <td class="td">
            <input type="hidden" name="action" value="edit_profile" />
            <button type="submit" id="submit" class="btn btn-primary" name="edit_profile">
               <i class="icon-edit icon-white"></i> Edit Information
            </button>
        </td>
    </tr>
    <tr>
        <td class="td"></td>
        <td class="td">
            <div id="result" class="xhrresult"></div> 
        </td>
    </tr>
</table>                     
</form>

<h2>Change Password</h2>
<form class="forms" id="form2" method="post" action="">
<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">All fields are mandatory</th>                
    </tr>
    <tr>
        <td class="td" width="200px"><label>Old Password</label></td>
        <td class="td">
            <input type="password" name="oldpassword" class="input-long" />
        </td>
    </tr>
    <tr>
        <td class="td"><label>New Password</label></td>
        <td class="td">
            <input type="password" name="newpassword" class="input-long" />
        </td>
    </tr>
    <tr>
        <td class="td"><label>Confirm Password</label></td>
        <td class="td">
            <input type="password" name="confirmpassword" class="input-long" />
        </td>
    </tr>
    <tr>
        <td class="td"><div class="loading" id="loading"></div></td>
        <td class="td">
            <input type="hidden" name="action" value="edit_password" />                    
            <button type="submit" id="submit" class="btn btn-primary" name="change_password">
                <i class="icon-edit icon-white"></i> Change Password</button>
        </td>
    </tr>
    <tr>
        <td class="td"></td>
        <td class="td">
            <div id="result2" class="xhrresult"></div> 
        </td>
    </tr>
</table>    
</form>

<script type="text/javascript">
    $(document).ready(function(e){ 
		
		$("#collapseTwo").removeClass("in").addClass("in"); 
		$("#collapseOne").removeClass("in");    
		                    
        $('#form1').submit(function(e){
            $("#load").show();
            $("#submit").attr('disabled','true');
            var formData = $("#form1").serialize();
            $.ajax({
                type: "POST",
                cache: false,
                url	: "<?=$base_url.ADMIN_FOLDER.'profile/edit_profile'?>",
                data: formData,
                dataType: 'json',
                timeout : 10000,
                reset : false,                    
                success : function(data){
                    if(data.errorState == true){
                        $("#load").hide();
                        $("#submit").removeAttr('disabled');
                        $("#result").html(data.errors);
                        $("#result").fadeIn("slow");
                        $('html,body').animate({
                            scrollTop: $("#result").offset().top},
                        'slow');
                    }else if(data.success == true){
                        $("#load").hide();
                        $("#submit").removeAttr('disabled');
                        $("#result").html(data.msg);
                        $("#result").fadeIn("slow");
                        $('html,body').animate({
                            scrollTop: $("#result").offset().top},
                        'slow');                            
                    }                       
                },
                error : function(e){
                    $("#load").hide();
                    $("#submit").removeAttr('disabled');
                    alert("There was an error. Please try again later."); 
                    return false;
                }
            });
            return false;
        });

        $('#form2').submit(function(e){
            $("#loading").show();
            $("#submit").attr('disabled','true');
            var formData = $("#form2").serialize();
            $.ajax({
                type: "POST",
                cache: false,
                url	: "<?=$base_url.ADMIN_FOLDER.'profile/edit_password'?>",
                data: formData,
                dataType: 'json',
                timeout : 10000,
                reset : false,                    
                success : function(data){
                    if(data.errorState == true){
                        $("#loading").hide();
                        $("#submit").removeAttr('disabled');
                        $("#result2").html(data.errors);
                        $("#result2").fadeIn("slow");
                        $('html,body').animate({
                            scrollTop: $("#result2").offset().top},
                        'slow');
                    }else if(data.success == true){
                        $("#loading").hide();
                        $("#submit2").removeAttr('disabled');
                        $("#result2").html(data.msg);
                        $("#result2").fadeIn("slow");
                        $('#facultyform2')[0].reset();
                        $('html,body').animate({
                            scrollTop: $("#result2").offset().top},
                        'slow');                            
                    }                       
                },
                error : function(e){
                    $("#loading").hide();
                    $("#submit").removeAttr('disabled');
                    alert("There was an error. Please try again later."); 
                    return false;
                }
            });
            return false;
        });
    });

</script>    

