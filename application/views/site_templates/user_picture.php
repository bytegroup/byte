<form id="otherinfoform" name="otherinfoform" action="<?=$base_url.ADMIN_FOLDER.'users/update_userinfo'?>" method="post">
<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">User Information</th>
        <th style="text-align: center">Picture</th>
    </tr>
    <tr>
        <td class="td" width="180px"><label>Full Name</label></td>
        <td class="td">
             <?=ucfirst($uinfo->firstName.' '.$uinfo->lastName)?>
        </td>
        <td rowspan="4" class="td" width="200px" align="center" style="text-align: center">
            <div id="picture">
                <img src="<?=$base_url.ASSETS_FOLDER.PHOTOS_FOLDER.$uinfo->profilePicture?>" width="200" alt="<?=ucfirst($uinfo->firstName.''.$uinfo->lastName)?>" />
            </div>
            <button id="gotoupload" class="btn btn-mini btn-primary">Upload Picture</button>
        </td>
    </tr>
    
	<tr>
        <td class="td"><label>Email</label></td>
        <td class="td"><input type="text" name="email" value="<?=$uinfo->email?>" /></td>
    </tr>
    <tr>
        <td class="td"><label>Address</label></td>
        <td class="td"><input type="text" name="address" value="<?=$uinfo->address?>" /></td>
    </tr>
    <tr>
        <td class="td"><label>Phone</label></td>
        <td class="td"><input type="text" name="phone" value="<?=$uinfo->phone?>" /></td>
    </tr>
	<tr>
        <td colspan="2"><label class="control-label"><div class="loading" id="infoload"></div><div id="result1" style="font-size:10px;font-weight: bold;"></div></label></td>
    </tr>
	<tr>
        <td colspan="2">
			<input type="hidden" name="action" value="update_userinfo" />
			<input type="hidden" name="userid" value="<?=$uinfo->userId?>" />
			<button type="button" class="btn btn-success" id="infosubmit"><i class="icon-fast-forward"></i> Save Info</button>
		</td>
    </tr>
</table>
</form>
<iframe name="uploadFrame" style="display: none"></iframe>
<h2>Upload Picture</h2>
<form class="forms" id="fileform" method="post" target="uploadFrame" action="<?=$base_url.ADMIN_FOLDER.'users/upload_picture'?>" enctype="multipart/form-data">            
<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">All fields are mandatory</th>                
    </tr>
    <tr>
        <td class="td" width="150px"><label>Select Picture</label></td>
        <td class="td">
            <input type="file" name="filename" id="filename" /> <br />(.jpg,.png,.gif <= 1 mb)<br /> Will be resized to Passport Size
        </td>
    </tr>            
    <tr>
        <td class="td"><div class="loading" id="load"></div></td>
        <td class="td">
            <input type="hidden" name="action" value="upload_picture" />
            <input type="hidden" name="userid" value="<?=$uinfo->userId?>" />
            <input type="hidden" name="old_picture" id="old-picture" value="<?=$uinfo->profilePicture?>" />
            <button type="submit" id="filesubmit" class="btn btn-primary" name="photo_upload">
                <i class="icon-upload icon-white"></i> Upload Photo
            </button>
        </td>                
    </tr>
    <tr>        
        <td class="td" colspan="2">
			<div id="result2" style="font-size:10px;font-weight: bold;"></div> 
        </td>
    </tr>
</table>    
</form>

<script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>
<script type="text/javascript">
    
	$("#infosubmit").click(function(){
		$("#result1").hide();  
        $("#infoload").show();
        $('#infosubmit').attr('disabled', 'disabled');           
        var formData = $("#otherinfoform").serialize();
        $.ajax({
            type: "POST",
            cache: false,
            url: "<?=$base_url.ADMIN_FOLDER.'users/update_userinfo'?>",
            data: formData,
            dataType: 'json',
            /*timeout : 60000,*/
            success : function(data){
                $("#infoload").hide();
                $('#infosubmit').removeAttr("disabled");
                if(data.success == false){                         
                    $("#result1").html(data.msg);                    
                    $("#result1").fadeIn("slow");                                
                }
                else if(data.success == true){
                    $("#result1").html(data.msg);                    
                    $("#result1").fadeIn("slow"); 
                }                        
            },
            error : function(e){
                $("#infoload").hide();
                $('#infosubmit').removeAttr("disabled");
                alert("There was an error. Please try again later.");                        
            }
        });
        return false;
	});
		
	function showUploadSuccess(data){
        $("#load").hide();
        $("#filesubmit").removeAttr('disabled');
        if(data.success == true){                      
            $("#result2").html(data.msg);
            $("#result2").fadeIn("slow");
            $("#picture").html('<img src="<?=$base_url.ASSETS_FOLDER.PHOTOS_FOLDER?>'+data.picture+'" width="200" />');
            $("#old-picture").val(data.picture);
            $('#fileform')[0].reset(); 
            $('html,body').animate({
                scrollTop: $("#picture").offset().top},
            'slow'); 
        }else if(data.success == false){
            $("#result2").html(data.msg);
            $("#result2").fadeIn("slow");
            $('html,body').animate({
                scrollTop: $("#result2").offset().top},
            'slow');                        
        }
    }
    $(document).ready(function(e){           
       $("#gotoupload").click(function(){
           $('html,body').animate({
                scrollTop: $("#fileform").offset().top},
            'slow');
            return false;
       });

        $("#filesubmit").click(function(e){
           $("#load").show();               
           $("#filesubmit").attr('disabled','true');               
        });
	});

</script>  