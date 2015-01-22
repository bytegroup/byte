<form class="forms" action="<?=$base_url.ADMIN_FOLDER.'user_group/update_permission/'?>" method="post" />
<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">User Group Information</th>        
    </tr>        
    <tr>
        <td class="td" width="180px"><label>Name</label></td>
        <td class="td"><?=$uginfo->groupName?></td>
    </tr>
    <tr>
        <td class="td"><label>Level</label></td>
        <td class="td"><?=$uginfo->userLevel?></td>
    </tr>
    <tr>
        <td class="td"><label>Permissions</label></td>
        <td class="td">            
            <?=$permission_checkboxes?>
        </td>
    </tr>
    <tr>
        <td class="td" style="float: right;">
            <button type="button" name="cancel_permission" class="btn btn-primary" onclick="window.location= '<?php echo $cancel_update; ?>';">
                Cancel Update
            </button>
            <div class="loading"></div>
        </td>
        <td class="td">            
            <input type="hidden" value="edit_permissions" name="action" />
            <input type="hidden" value="<?=$uginfo->groupId?>" name="ugid" />
            <button type="submit" name="edit_permission" class="btn btn-primary">
                <i class="icon-edit icon-white"></i> Update Permissions
            </button>    
        </td>
    </tr>
</table>
</form>