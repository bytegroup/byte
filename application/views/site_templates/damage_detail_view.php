
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
        margin-left: 0px;
        display: inline-block;
    }
    .form-horizontal .control-label {
        float: left;
        padding-top: 5px;
        text-align: left;
        width: 170px;
    }
</style>
<h3><?=$pageTitle?></h3>
<form class="div-vcard form-horizontal" id="damageform" name="damageform" action="<?=$base_url.IT_MODULE_FOLDER?>damage/save_Damage" method="post">
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
            <input id="vendor" style="border:none;background:none;background-color:transparent;" type="text" disabled="disabled" name="vendor" data-bind="value: $root.vendorsName"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="details">Details</label>
        <div class="controls">
            <textarea type="text" id="details" name="details" data-bind="value:$root.details"></textarea>
        </div>
    </div>



    <div class="control-group">
        <label class="control-label" for="damageType">Damage Type</label>
        <div class="controls" >
            <input type="radio" id="damageType" name="damageType" value="Repairable" data-bind="checked: damageType">Repairable
            <input type="radio" id="damageType" name="damageType" value="Permanent"  checked data-bind="checked: damageType">Permanent
        </div>
    </div>

    <div class="control-group" id="divWarrantyEndDate">
        <label class="control-label" for="warrantyEndDate">Warranty End Date</label>
        <div class="controls">
            <div class="form-input-box" >
                <input id="warrantyEndDate" disabled="disabled" name="warrantyEndDate" type="text" value=""   data-bind="value: $root.warrantyEndDate" >

        </div>
    </div>
<br>


    <div class="control-group">
        <label class="control-label" for="stockQuantity">Stock Quantity</label>
        <div class="controls">
            <input disabled="disabled" style="border:none;background:none;background-color:transparent;" type="text" value="" id="stockQuantity" name="stockQuantity" data-bind="value:$root.stockQuantity" />
        </div>
    </div>

      <div class="control-group">
            <label class="control-label" for="issueQuantity">Issue Quantity</label>
            <div class="controls">
                <input disabled="disabled" style="border:none;background:none;background-color:transparent;" type="text" value="" id="issueQuantity" name="issueQuantity" data-bind="value:$root.issueQuantity" />
            </div>
      </div>

    <div class="control-group" id="divDamageQuantity">
        <label class="control-label" for="damageQuantity">Damage Quantity</label>
        <div class="controls">

            <input id="damageQuantity" style="background:none;background-color:transparent;" type="text"  name="damageQuantity" data-bind=" value: $root.damageQuantity">
        </div>
    </div>

    <div class="control-group" id="damageDate">
        <label class="control-label" for="damageDate">Damage Date</label>
        <div class="controls">
            <div class="form-input-box" >
                <input id="damageDate"  name="damageDate" type="date" value=""   data-bind="value: $root.damageDate" >

        </div>
    </div>
        <br>
    <div class="control-group">
        <label class="control-label" for="user">Checked By</label>
        <div class="controls">
            <select id="user"  name="user" data-bind="options: $root.users, optionsValue: 'userId',
        optionsText: function(userId) {  return userId.firstName +'  '+ userId.lastName  },
         value: $root.checkedById, optionsCaption: 'Choose...'"></select> <!--'+ userId.middleName?'null':'' +'-->
    </div>

<br>
        <div class="control-group">
            <label class="control-label" for="remarks">Remarks</label>
            <div class="controls">
                <textarea type="text" id="remarks" name="remarks" data-bind="value:$root.remarks"></textarea>
            </div>
        </div>
    
    <br clear="all"/>
    <div class="control-group">
        <label class="control-label"><div class="loading" id="vcardload"></div></label>
        <div class="controls">
            <button type="button" class="btn btn-success" id="cancel" onclick="window.location='<?php echo $cancel_issue;?>';">Cancel</button>
        </div>
        <div class="controls">
            <button type="button" class="btn btn-success" id="submit" data-bind="click:save"><i class="icon-fast-forward"></i> Save</button>
        </div>
    </div>
</form>
<div id="result" style="display:none" class="span12"></div>    


  <script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>
  <script type="text/javascript">
  // Class to represent a row in the seat reservations grid


function addItem(price, initialItem) {
    var self = this;
    self.orderedQuantity = initialItem.orderedQuantity;
    self.quotationPrice = initialItem.quotationPrice;//ko.observable(price);
    self.itemName = initialItem.itemName;
    self.itemMasterId  = ko.observable(initialItem.itemMasterId);
    self.itemMaster = ko.observable(initialItem);
}

  // Overall viewmodel for this screen, along with initial state
function DamageViewModel() {

    var self = this;
   // self.total = ko.observable();
   // self.availableItems = ko.observableArray([]);
   // self.items = ko.observableArray([]);
    //self.organizationName = ko.observableArray();
    //self.departmentName = ko.observableArray();
   // self.vendors = ko.observableArray([]);
    self.organizationName = ko.observable();
    self.departmentName = ko.observable();
    //self.vendors = ko.observable();
   // self.description = ko.observable();
    self.organizationId = ko.observable();
    self.departmentId = ko.observable();
    self.vendorsId = ko.observable();
   // self.quotationId = ko.observable();
    self.vendorsName = ko.observable();
    //self.damageType = ko.observable('Partial');
   // self.warranty = ko.observable('No');
    self.warrantyEndDate=ko.observable();
    self.damageDate=ko.observable();
    self.damageQuantity=ko.observable();
    self.checkedById=ko.observable();
    self.damageType = ko.observable('Repairable');
    self.users = ko.observableArray([]);
    self.itemMasterId=ko.observable();
    self.stockId=ko.observable();
    self.stockQuantity=ko.observable();
    self.issueOrStock=ko.observable();
    self.issueQuantity=ko.observable();
    self.issueId=ko.observable();
    // Computed data

   /* self.totalSurcharge = ko.computed(function() {
        var p = 0;
        $.each(self.items(), function() { 
            p += eval(this.quotationPrice);

        })
        return p;
    });*/
    self.save = function(){                        
       /* var valid = $("#damageform").valid();
        if(!valid){
            alert("Errors in input");
            return;
        }*/
        var stockQuantity = $("#stockQuantity").val();
        var damageQuantity = $("#damageQuantity").val();
        var issueQuantity = $("#issueQuantity").val();
        if(issueQuantity==""){
            if(stockQuantity<damageQuantity){
                alert("Damage quantity must be less than or equal to stock quantity");
                return;
            }
        }else{
            if(issueQuantity<damageQuantity){
                alert("Damage quantity must be less than or equal to issue quantity");
                return;
            }
        }
        $("#result").hide();  
        $('#submit').attr('disabled', 'disabled');
        $.post('<?=$base_url.IT_MODULE_FOLDER?>damage/save_Damage/<?=$editId?>',
        {
            data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    vendorsId:self.vendorsId,
                    description:self.description,
                    damageType:self.damageType,
                    Items:ko.toJSON(self.items),
                    damageDate:self.damageDate,
                    checkedById:self.checkedById,
                    damageQuantity:self.damageQuantity,
                    stockQuantity:self.stockQuantity,
                    details:self.details,
                    remarks:self.remarks,
                    itemMasterId:self.itemMasterId,
                    stockId:self.stockId,
                    warrantyEndDate:self.warrantyEndDate,
                    issueOrStock:self.issueOrStock,
                    issueQuantity:self.issueQuantity,
                    issueId:self.issueId,
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
               // $("#uploadDiv").show();
               // $("#quotationIdFile").val(jsonData.result);
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
var vm = new DamageViewModel();
var edit = <?=$edit?>;
if(edit)
    $("#uploadDiv").show();

$(document).ready(function(){


    itemInfo = $.parseJSON('<?=$jsonItemMaster?>');
    vm.organizationName = itemInfo[0].organizationName;
    vm.departmentName = itemInfo[0].departmentName;
    vm.vendorsName=itemInfo[0].vendorsName;
    vm.warrantyEndDate=itemInfo[0].warrantyEndDate;
    vm.itemMasterId=itemInfo[0].itemMasterId;
    vm.organizationId=itemInfo[0].organizationId;
    vm.departmentId=itemInfo[0].departmentId;
    vm.vendorsId=itemInfo[0].vendorsId;
    vm.stockId=itemInfo[0].stockId;
    vm.stockQuantity=itemInfo[0].stockQuantity;
    vm.issueQuantity=itemInfo[0].issueQuantity;
    vm.issueId=itemInfo[0].issueId;


    vm.users = <?=$jsonUser?>;
    var issueOrStock = '<?php echo $issueOrStock; ?>';

    vm.issueOrStock=issueOrStock;
    //vm.organizationId = itemInfo[0].organizationId;
    //vm.departmentId = itemInfo[0].departmentId;
    //$("#divWarrantyEndDate").hide();
   /* if(!edit){
        for(j=0;j<vm.availableItems.length;j++){	
            vm.items.push(new addItem(0,vm.availableItems[j]));
        }
    }*/

    /*if(edit){
        for(j=0;j<vm.availableItems.length;j++){	
            vm.items.push(new addItem(vm.availableItems[j].damagePrice,vm.availableItems[j]));
        }
        if(requisitionInfo[0].damageDescription!=null&&requisitionInfo[0].damageDescription!='undefined')
            vm.description = requisitionInfo[0].damageDescription;
        else vm.description = '';
        vm.vendorId = requisitionInfo[0].vendorId;
    }*/

    vm.errors = ko.validation.group(vm);
    ko.applyBindings(vm);

   /* $("#filesubmit").click(function(e){
        $("#load").show();               
        $("#filesubmit").attr('disabled','true');               
    });*/
});

  ko.bindingHandlers.dateString = {
      init : function(element, valueAccessor) {
          //attach an event handler to our dom element to handle user input
          element.onchange = function(){
              var value = valueAccessor();//get our observable
              //set our observable to the parsed date from the input
              value(moment(element.value).toDate());
          };
      },
      update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
          var value = valueAccessor();
          var valueUnwrapped = ko.utils.unwrapObservable(value);
          if (valueUnwrapped) {
              element.value = moment(valueUnwrapped).format('L');
          }
      }
  };

  ko.bindingHandlers.datetimepicker = {
      init: function (element, valueAccessor, allBindingsAccessor) {

          ko.utils.registerEventHandler(element, "change", function () {
              var observable = valueAccessor;
              try {
                  observable($(element).datetimepicker("getDate"));//****
              }
              catch(ex) {}
          });

          //handle disposal (if KO removes by the template binding)
          ko.utils.domNodeDisposal.addDisposeCallback(element, function () {

          });

      },
      update: function (element, valueAccessor) {
          var value = ko.utils.unwrapObservable(valueAccessor),
              current = $(element).datetimepicker("getDate");

          if (value - current !== 0) {
              $(element).datetimepicker("setDate", value);
          }
      }
  };


  ko.bindingHandlers.datePicker = {
      init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
          // Register change callbacks to update the model
          // if the control changes.
          //console.log("in init valueAccessor:"+valueAccessor);
          ko.utils.registerEventHandler(element, "change", function () {
              var value = valueAccessor();
              value(new Date(element.value));
             // console.log("in init element.value:"+element.value);
          });
      },
      // Update the control whenever the view model changes
      update: function (element, valueAccessor, allBindingsAccessor, viewModel) {

          var value = valueAccessor();
          ko.utils.unwrapObservable(value);


      }
  };



     
</script>
