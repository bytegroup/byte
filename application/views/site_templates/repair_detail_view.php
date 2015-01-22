
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
<form class="div-vcard form-horizontal" id="repairform" name="repairform" action="<?=$base_url.IT_MODULE_FOLDER?>repair/save_Repair" method="post">
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
        <label class="control-label" for="details">Details</label>
        <div class="controls">
            <textarea type="text" id="details" name="details" data-bind="value:$root.details"></textarea>
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
        <label class="control-label" for="repairType">Repair Type</label>
        <div class="controls" >
            <select id="repairType"  name="repairType" data-bind="options: $root.repairType, optionsValue: 'repairTypeId',
        optionsText: 'repairTypeName',
         value: $root.repairTypeId, optionsCaption: 'Choose...'"></select>
        </div>
    </div>




        <div class="control-group">
            <label class="control-label" for="repairType">Repair Vendor</label>
            <div class="controls" >
                <select id="repairType"  name="repairType" data-bind="options: $root.repairVendors, optionsValue: 'vendorsId',
        optionsText: 'vendorsName',
         value: $root.repairVendorsId, optionsCaption: 'Choose...'"></select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="damageQuantity">Stock Quantity</label>
            <div class="controls">
                <input disabled="disabled" style="border:none;background:none;background-color:transparent;" type="text" value="" id="damageQuantity" name="damageQuantity" data-bind="value:$root.damageQuantity" />
            </div>
        </div>

        <div class="control-group" id="divRepairQuantity">
            <label class="control-label" for="repairQuantity">Quantity</label>
            <div class="controls">

                <input id="repairQuantity" style="background:none;background-color:transparent;" type="text"  name="repairQuantity" data-bind=" value: $root.repairQuantity">
            </div>
        </div>

        <div class="control-group" id="repairDate">
            <label class="control-label" for="repairDate">Repair Date</label>
            <div class="controls">
                <div class="form-input-box" >
                    <input id="repairDate"  name="repairDate" type="date" value=""   data-bind="value: $root.repairDate" >

                </div>
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
function RepairViewModel() {

    var self = this;

    self.organizationName = ko.observable();
    self.departmentName = ko.observable();
    self.organizationId = ko.observable();
    self.departmentId = ko.observable();
    self.vendorsId = ko.observable();
    self.vendorsName = ko.observable();
    self.itemMasterId=ko.observable();
    self.stockId=ko.observable();

    self.repairVendors = ko.observableArray([]);
    self.repairVendorsId=ko.observable();
    self.repairType = ko.observableArray([]);
    self.repairTypeId=ko.observable();

    self.warrantyEndDate=ko.observable();
    self.repairDate=ko.observable();
    self.repairQuantity=ko.observable();
    self.damageId=ko.observable();
    self.damageQuantity=ko.observable();

    self.users = ko.observableArray([]);

    // Computed data

    /* self.totalSurcharge = ko.computed(function() {
     var p = 0;
     $.each(self.items(), function() {
     p += eval(this.quotationPrice);

     })
     return p;
     });*/
    self.save = function(){
        /* var valid = $("#repairform").valid();
         if(!valid){
         alert("Errors in input");
         return;
         }*/
        var repairQuantity = $("#repairQuantity").val();
        var damageQuantity = $("#damageQuantity").val();
        if(damageQuantity<repairQuantity){
            alert("Repair quantity must be less than or equal to damage quantity");
            return;
        }
        $("#result").hide();
        $('#submit').attr('disabled', 'disabled');
        $.post('<?=$base_url.IT_MODULE_FOLDER?>repair/save_Repair/<?=$editId?>',
            {
                data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    vendorsId:self.vendorsId,
                    itemMasterId:self.itemMasterId,
                    stockId:self.stockId,
                    repairDate:self.repairDate,
                    repairTypeId:self.repairTypeId,
                    repairQuantity:self.repairQuantity,
                    details:self.details,
                    remarks:self.remarks,
                    repairVendorsId:self.repairVendorsId,
                    warrantyEndDate:self.warrantyEndDate,
                    damageId:self.damageId,
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
var vm = new RepairViewModel();
var edit = <?=$edit?>;
if(edit)
    $("#uploadDiv").show();

$(document).ready(function(){
    // jQuery('#datepicker').datepicker();


    //vm.vendors = <?/*=$jsonVendor*/?>;
    //vendorInfo = $.parseJSON('<?/*=$jsonVendor*/?>');
    //vm.vendorsName=vendorInfo[0].vendorsName;
    // vm.availableItems = <?/*=$jsonItemMaster*/?>;

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
    vm.damageId=itemInfo[0].damageId;
    vm.damageQuantity=itemInfo[0].damageQuantity;

    vm.users = <?=$jsonUser?>;
    vm.repairVendors = <?=$jsonRepairVendors?>;
    vm.repairType= <?=$jsonRepairType?>;


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
     vm.items.push(new addItem(vm.availableItems[j].repairPrice,vm.availableItems[j]));
     }
     if(requisitionInfo[0].repairDescription!=null&&requisitionInfo[0].repairDescription!='undefined')
     vm.description = requisitionInfo[0].repairDescription;
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
