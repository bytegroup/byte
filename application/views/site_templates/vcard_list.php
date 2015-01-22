<div class="span12">
    <div class="span10">
        <span class="label label-info">Total Entries : <?=$num_rows?></span>
    </div>    
</div>
<table class="table table-bordered table-striped table-hover" style="width: 100%">
    <thead>
        <tr>
            <th>SI.</th>            
            <th>Name</th>            
            <th>Organ.</th>
            <th>Job Title</th>
            <th>Phone</th>            
            <th>Email</th>
            <th>Address</th> 
            <th colspan="2">Created On</th> 
        </tr>
    </thead>
    <tbody>
        <?php
        if($query != false){
            $sort = 1;
            $si =($si != null || $si != "")?$si+1:1;
            foreach ($query->result() as $row)
            { ?>
        <tr>
            <td width="20px"><?=$si++?></td>            
            <td><small><?=$row->prefix?> <?=$row->firstName.' '.$row->lastName?><?=$mname = (trim($row->middleName) != "")?', '.$row->middleName:""?>, <?=$row->suffix?></small></td>
            <td><small><?=$row->organization?></small></td>
            <td><small><?=$row->title?></small></td>
            <td>                
                <small><b>Office:<br /></b><?=$row->telephoneOffice?><br />
                <b>Home: </b><br /><?=$row->telephoneHome?><br />
                <b>Mobile: </b><br /><?=$row->mobile?><br />
                <b>Fax: </b><br /><?=$row->fax?><small>
            </td>            
            <td><small><?=$row->email?></small></td>
            <td>                
                <small><?=$row->street?>, <?=$row->city?>, <?=$row->state.' '.$row->zip?>, <?=$row->country?></small>
            </td>
            <td><?=date("Y-m-d H:i:s", $row->creationDtTm)?></td>
            <td width="90px">
                <form target="_blank" method="get" action="<?=$base_url.ADMIN_FOLDER.'vcard/download/'?>">
                <label>Vcard Version</label>
                <select name="version" id="version" class="span9">
                    <option value="2.1">2.1</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select> 
                <input type="hidden" name="userid" value="<?=$row->userId?>" />
                <button class="btn btn-primary btn-mini" type="submit">
                    <i class="icon-download icon-white"></i> Get VCF
                </button>
                </form>
            </td>
        </tr>
        <?php
            }
        }    
        ?>
    </tbody>
    
</table>
<?php
    if($query)
    {
    ?>
<div class="pagination pagination-centered" style="margin: 0px">
    <ul>
        <?=$this->pagination->create_links()?>
    </ul>
</div>
    <?    
    }
    ?>
    