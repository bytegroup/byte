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
</script>
