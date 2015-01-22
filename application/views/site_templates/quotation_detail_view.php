
<style>
    label.error { float: none; color: red; padding-left: .5em;display: inline-block }
    .div-vcard:after {
        background-color: #F5F5F5;
        border: 1px solid #DDDDDD;
        border-radius: 4px 0 4px 0;
        color: #9DA0A4;
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
    .form-horizontal .controls {
        margin-left: 216px;
    }
    .form-horizontal .control-label {
        float: left;
        padding-top: 5px;
        text-align: left;
        width: 170px;
    }
</style>
<h3><?=$pageTitle?></h3>
<form class="div-vcard form-horizontal" id="quotationform" name="quotationform" action="<?=$base_url.IT_MODULE_FOLDER?>quotation/save_Quotation" method="post">
    <div class="control-group">
        <label class="control-label" for="organization">Organization</label>
        <div class="controls">
            <input disabled="disabled" style="border:none;background:none;background-color:transparent;" type="text" value="" id="organizationName" name="organizationName" data-bind="value:$root.organizationName" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="department">Department</label>
        <div class="controls">
            <input disabled="disabled" style="border:none;background:none;background-color:transparent;" type="text" value="" id="departmentName" name="departmentName" data-bind="value:$root.departmentName" />
		
        </div>

    </div>
    <div class="control-group">
        <label class="control-label" for="vendors">Vendor</label>
        <div class="controls">
            <select id="vendor"  class="required" name="vendor" data-bind="options: $root.vendors, optionsValue: 'vendorsId', optionsText: 'vendorsName', value: $root.vendorId"></select>           
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="quotationDescription">Description</label>
        <div class="controls">
            <textarea type="text" id="quotationDescription" name="quotationDescription" data-bind="value:$root.description"></textarea>
        </div>
    </div>
    
    <h3>View Products</h3>    
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span12">
            <table width="75%">
                <thead><tr>
                    <th align="left">Item name</th><th align="left">Ordered Quantity</th><th align="left" style="padding-left:30px;">Price</th>
                </tr></thead>
                <tbody data-bind="foreach: items">
                    <tr>
                        <td ><input disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" type="text" value="" id="itemName" name="itemName" data-bind="value:itemName" /></td>
                        <td ><input disabled="disabled" style="width:100px;border:none;background:none;background-color:transparent;" type="text" value="" id="orderedQuantity" name="orderedQuantity" data-bind="value: orderedQuantity" />
                        </td>
                        <td>BDT <input class="required number" style="width:150px;background:none;background-color:transparent;" type="text" value="" id="quotationPrice" name="quotationPrice" type="number" onblur="checkNum(this);" data-bind="value: quotationPrice" />
                        </td>
                        
                    </tr>    
                </tbody>
            </table>
            <div style="padding-left:350px;">
                <h5>
                    Total: BDT<input disabled="disabled" style="width:100px;border:none;background:none;background-color:transparent;" type="text" value="" id="total" name="total" data-bind="value:totalSurcharge()" />
                </h5>
            </div>
        </div>    
    </div>
    <br/>
    <br/>
    
    <br clear="all"/>
    <div class="control-group">
        <label class="control-label"><div class="loading" id="vcardload"></div></label>
        <div class="controls">
            <button type="button" class="btn btn-success" id="submit" data-bind="click:save"><i class="icon-fast-forward"></i> Save</button>
            <button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;"><i class="icon-fast-forward"></i> Back</button>
        </div>

    </div>
</form>
<div id="result" style="display:none" class="span12"></div>    
<div id="uploadDiv" style="display:none;">
    <iframe name="uploadFrame" style="display: none"></iframe>
    <h2>Upload Quotation</h2>
    <form class="forms" id="fileform" method="post" target="uploadFrame" action="<?=$base_url.IT_MODULE_FOLDER.'quotation/upload_picture'?>" enctype="multipart/form-data">            
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
                <input type="hidden" id="quotationIdFile" name="quotationId" value="<?=$editId?>" />
                <input type="hidden" id="reqId" name="reqId" value="<?=$requisitionId?>" />
                <input type="hidden" id="redirectUrl" name="redirectUrl" value="<?=$base_url.IT_MODULE_FOLDER?>quotation/index/<?=$requisitionId?>" />
                <input type="hidden" name="old_file" id="old-file" value="<?=$oldfile?>" />
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

  <script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>
  <script type="text/javascript">
  // Class to represent a row in the seat reservations grid
  
function addItem(price, initialItem) {
    var self = this;
    self.orderedQuantity = initialItem.orderedQuantity;
    self.quotationPrice = ko.observable(price);
    self.itemName = initialItem.itemName;
    self.itemMasterId  = ko.observable(initialItem.itemMasterId);
    self.itemMaster = ko.observable(initialItem);
}
  
  // Overall viewmodel for this screen, along with initial state
function QuotationViewModel() {
    var self = this;
    self.total = ko.observable();
    self.availableItems = ko.observableArray([]);
    self.items = ko.observableArray([]);
    self.organizationName = ko.observableArray();
    self.departmentName = ko.observableArray();
    self.vendors = ko.observableArray([]);
    self.description = ko.observable();
    self.organizationId = ko.observable();
    self.departmentId = ko.observable();
    self.vendorId = ko.observable();
    // Computed data

    self.totalSurcharge = ko.computed(function() {
        var p = 0;
        $.each(self.items(), function() { 
            p += eval(this.quotationPrice());

        })
        return p;
    });
    self.save = function(){                        
        var valid = $("#quotationform").valid();
        if(!valid){
            alert("Errors in input");
            return;
        }
        $("#result").hide();  
        $('#submit').attr('disabled', 'disabled');
        $.post('<?=$base_url.IT_MODULE_FOLDER?>quotation/save_Quotation/<?=$editId?>', 
        {
            data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    vendorId:self.vendorId,
                    requisitionId:<?=$requisitionId?>,
                    description:self.description,
                    Items:ko.toJSON(self.items),
                    edit: edit
                }
        }                                    
        , 
        function(data){
            $('#submit').removeAttr("disabled");
            jsonData = $.parseJSON( data );
            if(jsonData.success == false){                         
                $("#result").html("Could not Save. Please try again later.");                    
                $("#result").fadeIn("slow");
                return false;                                
            }
            else if(jsonData.success == true){
                alert("Saved Successfully");
                $("#uploadDiv").show();
                $("#quotationIdFile").val(jsonData.result);
                $("#redirectUrl").val(jsonData.url);
                //window.location = jsonData.url;                   
            }                    
        })
    }
}	

function checkNum(elem){
    var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
    var str = $('.number').val();
    if(numberRegex.test(str)) {
        return true;
    }
    else{
        alert("please enter valid input");
        elem.value = "";
        elem.focus();
    }
}
var vm = new QuotationViewModel();
var edit = <?=$edit?>;
if(edit)
    $("#uploadDiv").show();

$(document).ready(function(){
    requisitionInfo = $.parseJSON('<?=$jsonQuotationInfo?>');   
    vm.organizationName = requisitionInfo[0].organizationName;
    vm.departmentName = requisitionInfo[0].departmentName;
    vm.organizationId = requisitionInfo[0].organizationId;
    vm.departmentId = requisitionInfo[0].departmentId;
    vm.vendors = <?=$jsonVendor?>;
    vm.availableItems = <?=$jsonItemMaster?>;
    if(!edit){
        for(j=0;j<vm.availableItems.length;j++){	
            vm.items.push(new addItem(0,vm.availableItems[j]));
        }
    }
    if(edit){
        for(j=0;j<vm.availableItems.length;j++){	
            vm.items.push(new addItem(vm.availableItems[j].quotationPrice,vm.availableItems[j]));
        }
        if(requisitionInfo[0].quotationDescription!=null&&requisitionInfo[0].quotationDescription!='undefined')
            vm.description = requisitionInfo[0].quotationDescription;
        else vm.description = '';
        vm.vendorId = requisitionInfo[0].vendorsId;
    }

    vm.errors = ko.validation.group(vm);
    ko.applyBindings(vm);

    $("#filesubmit").click(function(e){
        $("#load").show();               
        $("#filesubmit").attr('disabled','true');               
    });
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
    }
    else if(data.success == false){
        $("#result2").html(data.msg);
        $("#result2").fadeIn("slow");
        $('html,body').animate({
        scrollTop: $("#result2").offset().top},
        'slow');                        
    }
}
     
</script>
