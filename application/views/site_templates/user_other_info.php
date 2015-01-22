<!--<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">User Information</th>        
    </tr>
    <tr>
        <td class="td" width="180px"><label>Full Name</label></td>
        <td class="td">
             <?=ucfirst($uinfo->firstName.' '.$uinfo->lastName)?>
        </td>        
    </tr>    
    <tr>
        <td class="td"><label>Date of Birth</label></td>
        <td class="td"><?=$uinfo->dob?></td>
    </tr>
    <tr>
        <td class="td"><label>Gender</label></td>
        <td class="td"><?=$uinfo->gender?></td>
    </tr>    
</table>-->
<div class="demo" style="padding-left: 0px; padding-right: 10px;">
    <div id="tabs" style="width: 100%;">
        <ul>
            <li><a href="<?=$base_url.ADMIN_FOLDER.'users/user_picture/'.$uinfo->userId?>"><span>User Picture</span></a></li>
        </ul>                                    
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(e){
        $(function() {
            $( "#tabs" ).tabs({
                cache: true,
                ajaxOptions: {
                    error: function( xhr, status, index, anchor ) {
                        $( anchor.hash ).html(
                                "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                                "Sorry for the inconvenience." );
                    },
                    success: function(data, textStatus) {
                    }
                },
                spinner: '<img src="<?=base_url().ASSETS_FOLDER?>images/small-loading.gif" />'
            });
        });
		  
    });    
</script>