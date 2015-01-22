<form id="approveform" action="<?=$base_url.IT_MODULE_FOLDER?>quotation/approve_Quotation">
    <table cellpadding="5" cellspacing="1" style="width: 100%" border="0">
        <tr>
            <td colspan="2"><h3>Approval</h3></td>
        </tr>
        <tr>
            <td><b>Approve</b></td>
            <td>
                <select name="approve" id="approve" value="" >
                    <option value="">Select</option>
                    <?=$appList?>
                </select>               
            </td>
        </tr>
        <tr>
            <td></td>
            <tr><td><div style="height:20px;"></div></td></tr>
            <td>
                <input type="hidden" name="action" value="approve" />
                <input type="hidden" name="saveId" value="<?=$saveId?>" />
                <input type="submit" name="submit" id="submitApprove" value="Save" />
            </td>
        </tr>
    </table>
    <br />
    <div id="result"></div>
</form>

<div id="uploadDiv" >
    <iframe name="uploadFrame" style="display: none"></iframe>
    <h2>Upload Approved Quotation</h2>
    <form class="forms" id="fileform" method="post" target="uploadFrame" action="<?=$base_url.IT_MODULE_FOLDER.'quotation/upload_approvedQuotation'?>" enctype="multipart/form-data">            
    <table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
        <tr>
            <td class="td" width="150px"><label>Select File</label></td>
            <td class="td">
                <input type="file" name="filename" id="filename" />
            </td>
        </tr>            
        <tr>
            <td class="td"><div class="loading" id="load"></div></td>
            <td class="td">
                <input type="hidden" name="action" value="upload_picture" />
                <input type="hidden" id="quotationIdFile" name="quotationId" value="<?=$saveId?>" />
                <input type="hidden" id="reqId" name="reqId" value="<?=$redirectId?>" />
                <input type="hidden" id="redirectUrl" name="redirectUrl" value="<?=$base_url.IT_MODULE_FOLDER?>quotation/index/<?=$redirectId?>" />
               <!-- <input type="hidden" name="old_file" id="old-file" value="<?/*=$qinfo[0]->quotationFileApproved */?>" />-->
                <button type="submit" id="filesubmit" class="btn btn-primary" name="photo_upload">
                    <i class="icon-upload icon-white"></i> Upload File
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
</div>
<div id="uploadDiv" >
    <iframe name="uploadWOFrame" style="display: none"></iframe>
    <h2>Upload Approved Work Order</h2>
    <form class="forms" id="fileform" method="post" target="uploadWOFrame" action="<?=$base_url.IT_MODULE_FOLDER.'quotation/upload_approvedWorkOrder'?>" enctype="multipart/form-data">            
    <table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
        <tr>
            <td class="td" width="150px"><label>Select File</label></td>
            <td class="td">
                <input type="file" name="filename" id="filename" />
            </td>
        </tr>            
        <tr>
            <td class="td"><div class="loading" id="load"></div></td>
            <td class="td">
                <input type="hidden" name="action" value="upload_picture" />
                <input type="hidden" id="quotationIdFile" name="quotationId" value="<?=$saveId?>" />
                <input type="hidden" id="reqId" name="reqId" value="<?=$redirectId?>" />
                <input type="hidden" id="redirectUrl" name="redirectUrl" value="<?=$base_url.IT_MODULE_FOLDER?>quotation/index/<?=$redirectId?>" />
                <!--<input type="hidden" name="old_file" id="old-file" value="<?/*=$qinfo[0]->workOrderFileApproved */?>" />-->
                <button type="submit" id="filesubmit" class="btn btn-primary" name="photo_upload">
                    <i class="icon-upload icon-white"></i> Upload File
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
</div>
<script>
    var saveId = '<?=$saveId?>';
    var redirectId = '<?=$redirectId?>';
    $("#approveform").submit(function(e){
       $("#submitApprove").attr("disabled","disabled");
       $("#result").html("Please Wait...");
       var formData = $("#approveform").serialize();
       $.ajax({
           type:"post",
           cache:false,
           url:"<?=$saveUrl?>"+saveId,
           data:formData,
           dataType:"json",
           success:function(data){
               $("#submitApprove").removeAttr("disabled");
               if(data.success == true){
                   location.replace("<?=$redirectUrl?>"+redirectId);
               }else{
                   $("#result").html(data.msg);
               }
           },
           error:function(){
               $("#submitApprove").removeAttr("disabled");
               $("#result").html("");
           }
       });
       return false; 
    });
    $("#filesubmit").click(function(e){
       $("#load").show();               
       $("#filesubmit").attr('disabled','true');               
    });
	
  	
    function showUploadSuccess(data){
        $("#load").hide();
        $("#filesubmit").removeAttr('disabled');
        if(data.success == true){                      
            $("#result2").html(data.msg);
            $("#result2").fadeIn("slow");
            $('#fileform')[0].reset();
            $('#old-file').val(data.picture); 
            //window.location = $("#redirectUrl").val();			
        }else if(data.success == false){
            $("#result2").html(data.msg);
            $("#result2").fadeIn("slow");
            $('html,body').animate({
                scrollTop: $("#result2").offset().top},
            'slow');                        
        }
    }
</script>
