<style>
    label.error { float: none; color: red; padding-left: .5em;display: inline-block }
    .div-vcard:after {
        background-color: #F5F5F5;
        border: 1px solid #DDDDDD;
        border-radius: 4px 0 4px 0;
        color: #9DA0A4;
        content: "vCard";
        font-size: 12px;
        font-weight: bold;
        left: -1px;
        padding: 3px 7px;
        position: absolute;
        top: -1px;
    }
    
    form.div-vcard {
        padding-bottom: 19px;
    }
    .div-vcard {
        background-color: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 4px 4px 4px 4px;
        margin: 15px 0;
        padding: 39px 19px 14px;
        position: relative;
    }
</style>
<form class="div-vcard form-horizontal" id="vcardform" name="vcardform" action="<?=$base_url.ADMIN_FOLDER?>vcard/process_vcard" method="post">
    <div class="control-group">
        <label class="control-label" for="prefix">Prefix</label>
        <div class="controls">
            <input type="text" id="prefix" name="prefix" data-original-title="Mr, Mrs, Miss..." data-placement="right" rel="tooltip">            
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="firstname">First Name</label>
        <div class="controls">
            <input onkeyup="updateDisplayName();" onblur="updateDisplayName();" type="text" value="" class="required" id="firstname" name="firstname" data-original-title="First Name" data-placement="right" rel="tooltip" >            
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="middlename">Middle Name</label>
        <div class="controls">
            <input type="text" id="middlename" name="middlename" data-original-title="Middle Name"  data-placement="right" rel="tooltip">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="lastname">Last Name</label>
        <div class="controls">
            <input onkeyup="updateDisplayName();" onblur="updateDisplayName();" type="text" class="required" id="lastname" name="lastname" data-original-title="Last Name" data-placement="right" rel="tooltip">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="displayname">Display Name</label>
        <div class="controls">
            <input type="text" id="displayname" class="required" name="displayname" data-original-title=" Combination of First, Middle and Last Name"  data-placement="right" rel="tooltip">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="suffix">Suffix</label>
        <div class="controls">
            <input type="text" id="suffix" name="suffix" data-original-title="MIEE, MBCS, MSc, PhD ..."  data-placement="right" rel="tooltip">            
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="organization">Organization</label>
        <div class="controls">
            <input type="text" id="organization" class="required" name="organization" data-original-title="Your Company Name"  data-placement="right" rel="tooltip">            
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="title">Job Title</label>
        <div class="controls">
            <input type="text" id="title" class="required" name="title" data-original-title="Job Title eg. CEO ..."  data-placement="right" rel="tooltip">            
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="telephoneoffice">Phone(Office)</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="telephoneoffice" name="telephoneoffice" data-original-title="88 0172 1315437" class="input-large required" maxlength="20"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-briefcase"></i></span> 
                <!--<input type="text" id="telephoneoffice[]" name="telephoneoffice[]" data-original-title="NNNN" class="input-mini" maxlength="4"  data-placement="right" rel="tooltip">
                <input type="text" id="telephoneoffice[]" name="telephoneoffice[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-briefcase"></i></span>-->                 
            </div>                       
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="telephonehome">Phone(Home)</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="telephonehome" name="telephonehome" data-original-title="88 0172 1315437" class="input-large" maxlength="20"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-home"></i></span> 
                <!--<input type="text" id="telephonehome[]" name="telephonehome[]" data-original-title="NNNN" class="input-mini" maxlength="4"  data-placement="right" rel="tooltip">
                <input type="text" id="telephonehome[]" name="telephonehome[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-home"></i></span>-->
            </div>                       
        </div>
    </div>    
    <div class="control-group">
        <label class="control-label" for="mobile">Mobile</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="mobile" name="mobile" data-original-title="88 0172 1315437" class="input-large" maxlength="20"  data-placement="right" rel="tooltip"> 
                <!--<input type="text" id="mobile[]" name="mobile[]" data-original-title="NNNN" class="input-mini" maxlength="4"  data-placement="right" rel="tooltip">
                <input type="text" id="mobile[]" name="mobile[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10"  data-placement="right" rel="tooltip">-->
            </div>    
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="fax">Fax</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="fax" name="fax" data-original-title="88 0172 1315437" class="input-large" maxlength="20" data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-print"></i></span> 
                <!--<input type="text" id="fax[]" name="fax[]" data-original-title="NNNN" class="input-mini" maxlength="4" data-placement="right" rel="tooltip">
                <input type="text" id="fax[]" name="fax[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10" data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-print"></i></span>-->
            </div>    
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="website">Website</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">http://</span><input type="text" id="website" name="website" data-original-title="www.mywebsite.com"  data-placement="right" rel="tooltip">                                 
            </div>    
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="email">Email</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on"><i class="icon-envelope"></i></span><input class="required email" type="text" id="email" name="email" data-original-title="example@example.com"  data-placement="right" rel="tooltip">                                 
                <span class="help-block">
                    <span class="label label-info">Email will be used to save your information for your further usage.</span>
                </span>
            </div>    
        </div>
    </div>    
    <h3>Address Office</h3>    
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span6">
            <label>Street</label>
            <textarea id="street" name="street" class="span7 required"></textarea> 
            <label>City</label>
            <input type="text" id="city" name="city" class="input-xlarge required">
            <label>Country</label>
            <input type="text" id="country" name="country" class="input-xlarge required">
        </div>        
        <div class="span6">   
            <label>Zip</label>
            <input type="text" id="zip" name="zip" class="input-small required required"> 
            <label>State</label>
            <input type="text" id="state" name="state" class="input-xlarge">
        </div>
    </div>
    <br />
    <h3>Address Home</h3>    
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span6">
            <label>Street</label>
            <textarea id="streethome" name="streethome" class="span7"></textarea>            
            <label>City</label>
            <input type="text" id="cityhome" name="cityhome" class="input-xlarge">
            <label>Country</label>
            <input type="text" id="countryhome" name="countryhome" class="input-xlarge">
        </div>        
        <div class="span6">   
            <label>Zip</label>
            <input type="text" id="ziphome" name="ziphome" class="input-small"> 
            <label>State</label>
            <input type="text" id="statehome" name="statehome" class="input-xlarge">
        </div>
    </div>
    <br />
    <h3>Other Options</h3>    
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span4">
            <label>Vcard Version</label>
            <select name="version" id="version">
                <option value="2.1">2.1</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>    
        </div>    
        <div class="span4">
            <label>QR Image Size</label>
            <input type="text" class="span2 number" name="qrsize[]" id="qrsize1" max="500" /> X <input type="text" class="span2 number" name="qrsize[]" id="qrsize2" max="500" />  
            <span class="help-block">
                <span class="label label-info">eg. 300x 300.</span> <br />
                <span class="label label-info">Maximum Pixels allowed -- 300000px(WxL)</span>            
            </span>           
        </div>        
        <div class="span4">   
            <label>Image Format</label>
            <select name="imageformat" id="imageformat">
                <option value="jpg">JPG</option>
                <option value="png">PNG</option>                
            </select>
        </div>        
    </div>
    <br clear="all"/>
    <div class="control-group">
        <label class="control-label"><div class="loading" id="vcardload"></div></label>
        <div class="controls">
            <input type="hidden" value="0" name="userId" id="userId" />
            <button type="submit" class="btn btn-success" id="submit"><i class="icon-fast-forward"></i> Generate</button>
        </div>
    </div>
</form>

<div id="result" style="display:none" class="span12"></div>    
<div class="span12" id="imageDiv" style="display:none">    
    <div class="span5">
        <p align="center">
            <img src="" alt="myvcard" id="vcardImage" class="img-polaroid"><br />
            <span class="label label-info">Right Click and Save Image As</span>
        </p>
    </div>
    <div class="span7">        
        <textarea id="vdata" style="width: 350px; height: 320px;"></textarea> 
    </div>
</div>

  <script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>
  <script type="text/javascript">
      function updateDisplayName()
      {
          $("#displayname").val($("#firstname").val()+" "+$("#lastname").val());
      }
  $(document).ready(function(){   
      $("#vcardform input").hover(function(e){
         $(this).tooltip('show'); 
      });
      
      $("#vcardform").validate({
          submitHandler: function(){
            $("#result").hide();  
            $("#vcardload").show();
            $('#submit').attr('disabled', 'disabled');           
           var formData = $("#vcardform").serialize();
            $.ajax({
                type: "POST",
                cache: false,
                url: "<?=$base_url.ADMIN_FOLDER.'vcard/process_vcard'?>",
                data: formData,
                dataType: 'json',
                /*timeout : 60000,*/
                success : function(data){
                    $("#vcardload").hide();
                    $('#submit').removeAttr("disabled");
                    if(data.success == false){                         
                        $("#result").html(data.msg);                    
                        $("#result").fadeIn("slow");                                
                    }
                    else if(data.success == true){
                        $("#vcardImage").attr("src",data.src);
                        $("#imageDiv").show();
                        $("#vdata").html(data.vd);     
                        if(data.newUserId > 0)
                        {
                            $("#userId").val(data.newUserId);
                        }
                    }                        
                },
                error : function(e){
                    $("#vcardload").hide();
                    $('#submit').removeAttr("disabled");
                    alert("There was an error. Please try again later.");                        
                }
            });
            return false;
          }          
      });      
   
  
  });
  
  
</script>
