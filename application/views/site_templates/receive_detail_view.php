
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
<form class="div-vcard form-horizontal" id="receiveform" name="receiveform" action="<?=$base_url.IT_MODULE_FOLDER?>receive/save_Receive" method="post">
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
            <input id="vendor" style="border:none;background:none;background-color:transparent;" type="text" disabled="disabled" name="vendor" data-bind=" value: $root.vendorsName"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="receiveDescription">Description</label>
        <div class="controls">
            <textarea type="text" id="receiveDescription" name="receiveDescription" data-bind="value:$root.description"></textarea>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="receiveType">Receive Type</label>
        <div class="controls" >
            <input type="radio" id="receiveType" name="receiveType" value="Full" data-bind="checked: receiveType">Full
            <input type="radio" id="receiveType" name="receiveType" value="Partial"  checked data-bind="checked: receiveType">Partial
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="warranty">Warranty</label>
        <div class="controls">
            <input type="radio" id="warranty" name="warranty" value="Yes" data-bind="checked: warranty">Yes
            <input type="radio" id="warranty" name="warranty" value="No" checked data-bind="checked: warranty">No
        </div>
    </div>



    
    <h3>View Products</h3>    
    <hr style="margin: 5px 0"/>
   <!-- <div id="errormsg"><?/*=$errormsg*/?></div>-->
    <div class="row-fluid">
        <div class="span12">
            <table width="75%">
                <thead><tr>
                    <th align="left">Item name</th><th align="left">Receive Quantity</th><th align="left" >Price</th>
                    <th align="left" >Warranty End Date</th><th align="left" >Budget Head</th>
                </tr></thead>
                <tbody data-bind="foreach: items">
                    <tr>
                        <td ><input  disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" type="text" value="" id="itemName" name="itemName" data-bind="value:itemName" /></td>
                        <td ><input  style="width:100px;border:none;background:none;background-color:transparent;"  type="number"  value="" id="orderedQuantity" name="orderedQuantity" data-bind="value: orderedQuantity" />
                        </td>
                        <td ><input disabled="disabled" style="width:150px;" value="" id="quotationPrice" name="quotationPrice" type="number"  data-bind="value: quotationPrice" />
                        </td>
                        <td> <input id="warrantyEndDate" style="width:150px;" name="warrantyEndDate" type="date" value=""   data-bind="datePicker: warrantyEndDate" >
                        </td>
                        <td>
                            <select id="budgetId"  class="required" name="budgetId" data-bind="options: $root.budgetHeads, optionsValue: 'budgetId',
                                optionsText: 'budgetTitle',   value: budgetId,optionsCaption: 'Choose...'""></select>
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
      self.warrantyEndDate =ko.observable( initialItem.warrantyEndDate);
      self.itemMasterId  = ko.observable(initialItem.itemMasterId);
      self.itemMaster = ko.observable(initialItem);
      // self.budgetHeads = ko.observable(initialItem.budgetHeads);
      self.budgetId=ko.observable(initialItem.budgetId);
      //self.budgetTitle=ko.observable(initialItem.budgetTitle);
      self.budgetConsumedQuantity=ko.observable(initialItem.budgetConsumedQuantity);

  }

  // Overall viewmodel for this screen, along with initial state
function ReceiveViewModel() {

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
    self.vendorsId = ko.observable();
    self.quotationId = ko.observable();
    self.vendorsName = ko.observable();
    self.receiveType = ko.observable('Partial');
    self.warranty = ko.observable('No');
    self.warrantyEndDate=ko.observable();
    //self.budgetHeads = ko.observableArray();
   // self.budgetTitle=ko.observable();
   // self.budgetId=ko.observable();
    // Computed data

    self.totalSurcharge = ko.computed(function() {
        var p = 0;
        $.each(self.items(), function() { 
            p += eval(this.quotationPrice);

        })
        return p;
    });
    self.save = function(e){
       /* var valid = $("#receiveform").valid();
        if(!valid){
            alert("Errors in input");
            return;
        }*/
        $("#result").hide();

       /* alert('stop here');
        e.preventDefault();*/

        //checking existing stock received
        if(edit){
            $.post('<?=$base_url.IT_MODULE_FOLDER?>receive/checkPrevReceive/',
                {
                        prevRequisitionId:<?=$requisitionId?>,
                        prevItems:ko.toJSON(self.items)
                }
                ,
                function(data){
                   // $('#submit').removeAttr("disabled");
                     jsonData = $.parseJSON( data );
                     if(jsonData.success == false){
                         //alert(jsonData.message);
                     $("#result").html(jsonData.message);
                     $("#result").fadeIn("slow");
                     //$("#errormsg").show();
                     return false;
                     }
                     else if(jsonData.success == true){
                         //return true;
                     alert("Saved Successfully");
                     // $("#uploadDiv").show();
                     // $("#quotationIdFile").val(jsonData.result);
                    // $("#redirectUrl").val(jsonData.url);
                     window.location = jsonData.url;
                     }
                })
           // e.preventDefault();
            //return false;
           }

        $('#submit').attr('disabled', 'disabled');
        $.post('<?=$base_url.IT_MODULE_FOLDER?>receive/save_Receive/<?=$editId?>',
        {
            data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    vendorsId:self.vendorsId,
                    quotationId:self.quotationId,
                    requisitionId:<?=$requisitionId?>,
                    description:self.description,
                    receiveType:self.receiveType,
                    warranty:self.warranty,
                   warrantyEndDate:self.warrantyEndDate,
                    //budgetId:self.budgetId,
                    // budgetTitle:self.budgetTitle,
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
                //$("#errormsg").show();
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

var vm = new ReceiveViewModel();
var edit = <?=$edit?>;
if(edit)
    $("#uploadDiv").show();

$(document).ready(function(){
   // jQuery('#datepicker').datepicker();
    requisitionInfo = $.parseJSON('<?=$jsonQuotationInfo?>');   
    vm.organizationName = requisitionInfo[0].organizationName;
    vm.departmentName = requisitionInfo[0].departmentName;
    vm.organizationId = requisitionInfo[0].organizationId;
    vm.departmentId = requisitionInfo[0].departmentId;
    vm.quotationId = requisitionInfo[0].quotationId;
    vm.vendorsId = requisitionInfo[0].vendorsId;
    vm.vendors = <?=$jsonVendor?>;
    vendorInfo = $.parseJSON('<?=$jsonVendor?>');
    vm.vendorsName=vendorInfo[0].vendorsName;


    vm.budgetHeads=<?=$jsonBudgetHead?>;
    vm.availableItems = <?=$jsonItemMaster?>;
    //$("#divWarrantyEndDate").hide();
    if(!edit){
        for(j=0;j<vm.availableItems.length;j++){	
            vm.items.push(new addItem(0,vm.availableItems[j]));
        }
   }
   /* $("#divWarrantyEndDate").hide();
    $('input[name=warranty]').click(function(){
        if($(this).attr("value")=="Yes"){

            $("#divWarrantyEndDate").show();
        }
        if($(this).attr("value")=="No"){

            $("#divWarrantyEndDate").hide();
           // $("#warrantyEndDate").value=null;
            $('#warrantyEndDate').removeAttr('value');
        }

    });*/
    if(edit){
        for(j=0;j<vm.availableItems.length;j++){	
            vm.items.push(new addItem(0,vm.availableItems[j]));
        }

    }

    vm.errors = ko.validation.group(vm);
    ko.applyBindings(vm);

   /* $("#filesubmit").click(function(e){
        $("#load").show();               
        $("#filesubmit").attr('disabled','true');               
    });*/
});

  ko.bindingHandlers.datetimepicker = {
      init: function (element, valueAccessor, allBindingsAccessor) {
          //initialize datepicker with some optional options
          // var options = allBindingsAccessor().datetimepickerOptions || {};
          // $(element).datetimepicker(options);
          // console.log("----:"+$(element).datetimepicker("getDate"));
          //handle the field changing
          ko.utils.registerEventHandler(element, "change", function () {
              var observable = valueAccessor;
              try {
                  observable($(element).datetimepicker("getDate"));//****
              }
              catch(ex) {}
          });

          //handle disposal (if KO removes by the template binding)
          ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
              //$(element).datetimepicker("destroy");
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
 /* $(function()
  {
      $('.date-pick').datePicker({clickInput:true})
  });*/
/* ko.bindingHandlers.datepicker = {
      init: function (element, valueAccessor, allBindingsAccessor) {
          var options = allBindingsAccessor().datepickerOptions || {};
          $(element).datepicker(options).on("warrantyEndDate", function (ev) {
              var observable = valueAccessor();
              observable(ev.date);
          });
      },
      update: function (element, valueAccessor) {
          var value = ko.utils.unwrapObservable(valueAccessor());
          $(element).datepicker("setValue", value);
      }
  };*/

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
          //console.log("in update element.value:"+element.value.toISOString());
         /* if(value != undefined){
              console.log("in update value:"+value);
              element.value = value().toISOString();
          }*/

      }
  };

  /*ko.bindingHandlers.date = {
      init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
          ko.utils.registerEventHandler(element, 'change', function () {
              var value = valueAccessor();

              if (element.value !== null && element.value !== undefined && element.value.length > 0) {
                  value(element.value);
              }
              else {
                  value('');
              }
          });
      },
      update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
          var value = valueAccessor();
          var valueUnwrapped = ko.utils.unwrapObservable(value);

          var output = '';
          if (valueUnwrapped !== null && valueUnwrapped !== undefined && valueUnwrapped.length > 0) {
             // output = utils.formatDate(valueUnwrapped);
          }

          if ($(element).is('input') === true) {
              $(element).val(output);
          } else {
              $(element).text(output);
          }
      }
  };*/
  /*ko.bindingHandlers.moment = {
   init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
   ko.bindingHandlers.value.init(element, valueAccessor, allBindingsAccessor, viewModel);
   },
   update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
   var val = valueAccessor();

   var formatted = null;
   var date = moment(ko.utils.unwrapObservable(val));

   var format = allBindingsAccessor().format || 'MM/DD/YYYY';

   if (date && date.isValid()) {
   formatted = date.format(format);
   }

   valueAccessor()(formatted);
   ko.bindingHandlers.value.update(element, valueAccessor, allBindingsAccessor, viewModel);
   }
   };*/
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
