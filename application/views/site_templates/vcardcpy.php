
<style>
    label.error { float: none; color: red; padding-left: .5em;display: inline-block }
    .div-vcard:after {
        background-color: #F5F5F5;
        border: 1px solid #DDDDDD;
        border-radius: 4px 0 4px 0;
        color: #9DA0A4;
        content: "Vcard";
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
<form class="div-vcard form-horizontal" id="vcardform" name="vcardform" action="<?=$base_url?>vcard/save_vcard" method="post">
    <div class="control-group">
        <label class="control-label" for="prefix">Prefix</label>
        <div class="controls">
            <input type="text" id="prefix" name="prefix" data-original-title="Mr, Mrs, Miss..." data-placement="right" rel="tooltip">            
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="firstname">First Name</label>
        <div class="controls">
            <input type="text" value="" class="required" id="firstname" name="firstname" data-original-title="First Name" data-placement="right" rel="tooltip" >            
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
            <input type="text" class="required" id="lastname" name="lastname" data-original-title="Last Name" data-placement="right" rel="tooltip">
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
        <label class="control-label" for="title">Title</label>
        <div class="controls">
            <input type="text" id="title" class="required" name="title" data-original-title="Job Title eg. CEO ..."  data-placement="right" rel="tooltip">            
        </div>
    </div>    
    <div class="control-group">
        <label class="control-label" for="telephonehome">Phone(Home)</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="telephonehome" name="telephonehome" data-original-title="88 0172 1315437" class="input-large required number" maxlength="14"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-home"></i></span> 
                <!--<input type="text" id="telephonehome[]" name="telephonehome[]" data-original-title="NNNN" class="input-mini" maxlength="4"  data-placement="right" rel="tooltip">
                <input type="text" id="telephonehome[]" name="telephonehome[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-home"></i></span>-->
            </div>                       
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="telephoneoffice">Phone(Office)</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="telephoneoffice" name="telephoneoffice" data-original-title="88 0172 1315437" class="input-large required number" maxlength="14"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-briefcase"></i></span> 
                <!--<input type="text" id="telephoneoffice[]" name="telephoneoffice[]" data-original-title="NNNN" class="input-mini" maxlength="4"  data-placement="right" rel="tooltip">
                <input type="text" id="telephoneoffice[]" name="telephoneoffice[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10"  data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-briefcase"></i></span>-->                 
            </div>                       
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="mobile">Mobile</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="mobile" name="mobile" data-original-title="88 0172 1315437" class="input-large required number" maxlength="14"  data-placement="right" rel="tooltip"> 
                <!--<input type="text" id="mobile[]" name="mobile[]" data-original-title="NNNN" class="input-mini" maxlength="4"  data-placement="right" rel="tooltip">
                <input type="text" id="mobile[]" name="mobile[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10"  data-placement="right" rel="tooltip">-->
            </div>    
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="fax">Fax</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on">+</span><input type="text" id="fax" name="fax" data-original-title="88 0172 1315437" class="input-large required number" maxlength="14" data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-print"></i></span> 
                <!--<input type="text" id="fax[]" name="fax[]" data-original-title="NNNN" class="input-mini" maxlength="4" data-placement="right" rel="tooltip">
                <input type="text" id="fax[]" name="fax[]" data-original-title="NNNNNNNN" class="input-small" maxlength="10" data-placement="right" rel="tooltip"><span class="add-on"><i class="icon-print"></i></span>-->
            </div>    
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="website">Website</label>
        <div class="controls">
            <div class="input-prepend input-append">
                <span class="add-on"><i class="icon-globe"></i></span><input class="url" type="text" id="website" name="website" data-original-title="http://www.mywebsite.com"  data-placement="right" rel="tooltip">                                 
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
        <div class="span3">
            <label>Street</label>
            <input type="text" id="street" name="street" class="input-xlarge required"> 
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
        <div class="span3">
            <label>Street</label>
            <input type="text" id="streethome" name="streethome" class="input-xlarge required"> 
            <label>City</label>
            <input type="text" id="cityhome" name="cityhome" class="input-xlarge required">
            <label>Country</label>
            <input type="text" id="countryhome" name="countryhome" class="input-xlarge required">
        </div>        
        <div class="span6">   
            <label>Zip</label>
            <input type="text" id="ziphome" name="ziphome" class="input-small required required"> 
            <label>State</label>
            <input type="text" id="statehome" name="statehome" class="input-xlarge">
        </div>
    </div>
    <br />
    <h3>Other Options</h3>    
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span2">
            <label>Vcard Version</label>
            <select name="version" id="version">
                <option value="2.1">2.1</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>    
        </div>    
        <div class="span2">
            <label>QR Image Size</label>
            <select name="qrsize" id="qrsize">
                <option value="180x180">Small</option>
                <option value="280x280">Medium</option>
                <option value="380x380">Large</option>
            </select>            
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
            <button type="submit" class="btn btn-success" id="submit"><i class="icon-fast-forward"></i> Generate</button>
        </div>
    </div>
</form>

<div id="imageDiv" style="display:hidden">
    <img src="" alt="myvcard" id="vcardImage" />
    <textarea id="vdata" style="width: 876px; height: 320px;"></textarea>
    <input type="text" id="rawdata" value="" />
</div>
  <script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){   
      $("#vcardform input").hover(function(e){
         $(this).tooltip('show'); 
      });
      
      //$("#submit").click(function(){
          //alert($("#version option:selected").val());
      //})
      
      $("#vcardform").validate({
          submitHandler: function(){
            $("#vcardload").show();
            $('#submit').attr('disabled', 'disabled');
           // var version = $("#version option:selected").val();
            //var vcardData = generateVcardData(version);
            //var qrText = encodeURIComponent(vcardData);
            //var qrsize = $("#qrsize option:selected").val();
            //if(vcardData)
            //{
              //  $("#vcardImage").attr("src","https://chart.googleapis.com/chart?cht=qr&chs="+qrsize+"&chl="+qrText);
               // $("#imageDiv").show();
                //$("#vdata").html(vcardData);                
            //}
            //$('#submit').removeAttr('disabled');
           // return false;
           var formData = $("#vcardform").serialize();
            $.ajax({
                type: "POST",
                cache: false,
                url: "<?=$base_url.'vcard/process_vcard'?>",
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
        
        
        
   /*function generateVcardData(version)
  {
      if(version == "2.1")
      {
          return vcardVersionTwoPone();
      }
      else if(version == "3")
      {
          return vcardVersionThree();
      }
      else if(version == "4")
      {
          return vcardVersionFour();
      }
      
      return false;
  }
  
  function vcardVersionThree()
  {
       var qrText = "BEGIN:VCARD\nVERSION:3.0\nN:"+ $("#lastname").val() +";"+ $("#firstname").val() +"\nFN:" 
                    + $("#firstname").val() + " " + $("#lastname").val() + "\nORG:" + $("#organization").val() 
                    + "\nTITLE:" + $("#title").val() + "\nTEL;TYPE=WORK,VOICE:" + $("#telephoneoffice").val() 
                    + "\nTEL;TYPE=HOME,VOICE:" + $("#telephonehome").val() + "\nTEL;TYPE=CELL,VOICE:" + $("#mobile").val() 
                   
                    + "\nADR;TYPE=WORK:;;" + $("#street").val() + ";" 
                    + $("#city").val() + ";" 
                    + $("#state").val() + ";" 
                    + $("#zip").val() + ";" 
                    + $("#country").val()
                   
                    + "\nLABEL;TYPE=WORK:;;" + $("#street").val() + "\n," 
                    + $("#city").val() + "\n," 
                    + $("#state").val() + " " 
                    + $("#zip").val() + "\n," 
                    + $("#country").val()
                
                    
                    + "\nADR;TYPE=HOME:;;" + $("#street").val() + ";" 
                    + $("#city").val() + ";" 
                    + $("#state").val() + ";" 
                    + $("#zip").val() + ";" 
                    + $("#country").val()
                   
                    + "\nLABEL;TYPE=HOME:;;" + $("#street").val() + "\n," 
                    + $("#city").val() + "\n," 
                    + $("#state").val() + " " 
                    + $("#zip").val() + "\n," 
                    + $("#country").val()
                    
                    + "\nEMAIL;TYPE=PREF,INTERNET:" + $("#email").val() 
                    + "\nURL:" + $("#website").val() 
                    + "\nREV:2012-05-29T19:52:43Z" 
                    + "\nEND:VCARD";

        
        return qrText;
  }*/
  
  
  });
  
  
</script>
