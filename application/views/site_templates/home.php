<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">User Information</th>
        <th style="text-align: center">Profile Image</th>
    </tr>
    <tr>
        <td class="td" width="180px"><label>Full Name</label></td>
        <td class="td">
             <?=ucfirst($uinfo->firstName.' '.$uinfo->lastName)?>
        </td>
        <td rowspan="5" class="td" width="200px" align="center">
            <div id="picture">
                <?php if($uinfo->profilePicture!== '') { ?>
                    <img style="width: 200px; height: 220px" src="<?=$base_url.PROFILE_IMG.$uinfo->profilePicture ?>" width="100%" alt="<?=ucfirst($uinfo->firstName.''.$uinfo->lastName)?>" />
                <?php } else {if($uinfo->gender==="Male") {?>
                    <img style="width: 200px; height: 200px" src="<?=$base_url.PROFILE_IMG.'no_img_male.gif' ?>" width="100%" alt="<?=ucfirst($uinfo->firstName.''.$uinfo->lastName)?>" />
                <?php }else {?>
                    <img style="width: 200px; height: 200px" src="<?=$base_url.PROFILE_IMG.'no_img_female.png' ?>" width="100%" alt="<?=ucfirst($uinfo->firstName.''.$uinfo->lastName)?>" />
                <?php } } ?>
            </div>
        </td>
    <tr>
        <td class="td"><label>Email</label></td>
        <td class="td"><?=$uinfo->email?></td>
    </tr>

    <tr>
        <td class="td"><label>Phone</label></td>
        <td class="td"><?=$uinfo->phone?></td>
    </tr>
    <tr>
        <td class="td"><label>Mobile</label></td>
        <td class="td"><?=$uinfo->mobile?></td>
    </tr>

    <tr>
        <td class="td"><label>Organization</label></td>
        <td class="td"><?=$uinfo->organizationName?></td>
    </tr>
    <tr>
        <td class="td"><label>Department</label></td>
        <td class="td"><?=$uinfo->departmentName?></td>
    </tr>
    <tr>
        <td class="td"><label>Designation</label></td>
        <td class="td"><?=$uinfo->designationName?></td>
    </tr>
    <tr>
        <td class="td"><label>Present Address</label></td>
        <td class="td"><?=$uinfo->presentAddress?></td>
    </tr>
    <tr>
        <td class="td"><label>Permanent Address</label></td>
        <td class="td"><?=$uinfo->permanentAddress?></td>
    </tr>

</table>

<script language="JavaScript">        
       
	$(document).ready(function(e){
    	$("#collapseAdmin").removeClass("in").addClass("in");
	});       
       
</script>
