
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
<form class="div-vcard form-horizontal" id="billform" name="billform" action="http://localhost/ocl-backoffice/it-inventory/bill/add_Bill" method="post">
    <div class="control-group">
        <label class="control-label" for="organization">Organization</label>
        <div class="controls">
            <select id="organization"  class="required" name="organization" data-bind="options: $root.organizations, optionsValue: 'organizationId', optionsText: 'organizationName', value: $root.organizationId"></select>           
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="department">Department</label>
        <div class="controls">
            <select id="department"  class="required" name="department" data-bind="options: $root.departments, optionsValue: 'departmentId', optionsText: 'departmentName', value: $root.departmentId"></select>           
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="vendors">Vendor</label>
        <div class="controls">
            <select id="vendor"  class="required" name="vendor" data-bind="options: $root.vendors, optionsValue: 'vendorsId', optionsText: 'vendorsName', value: $root.vendorId"></select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="itemMasterId">Item</label>
        <div class="controls">
            <select id="itemMasterId"  class="required" name="itemMasterId" data-bind="options: $root.jsonItemMaster, optionsValue: 'itemMasterId', optionsText: 'itemName', value: $root.itemMasterId"></select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="billNo">Bill No</label>
        <div class="controls">
            <input type="text" value="" class="required" id="billNo" name="billNo" data-bind="value:$root.billNo" />         </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="budgetId">Budget Head</label>
        <div class="controls">
            <select id="budgetId"  class="required" name="budgetId" data-bind="options: $root.budgetHeads, optionsValue: 'budgetId',
                                optionsText: 'budgetTitle',   value: $root.budgetId,optionsCaption: 'Choose...'""></select>
    </div>
    </div>


    <!--<div class="control-group">
        <label class="control-label" for="billYear">Bill Year</label>
        <div class="controls">
            <select id="billYear"  class="required" name="billYear" data-bind="options: $root.billYears, optionsValue: 'billYearVal',
            optionsText: 'billYearText',   value: $root.billYear,optionsCaption: 'Choose...'""></select>
        </div>
    </div>-->

     <div class="control-group">
            <label class="control-label" for="type">Bill Type</label>
            <div class="controls">
                <input type="text" value="" class="required" id="type" name="type" data-bind="value:$root.type" />         </div>
     </div>


    <div class="control-group">
        <label class="control-label" for="billAmount">Bill Amount</label>
        <div class="controls">
            <input type="number" value="" class="required" id="billAmount" name="billAmount" data-bind="value:$root.billAmount" />         </div>
    </div>

        <div class="control-group">
            <label class="control-label" for="receiveDate">Receive Date</label>
            <div class="controls">
                <div class="form-input-box" id="dob_input_box">
                    <input id="receiveDate" name="receiveDate" type="date" value=""   data-bind="datePicker: receiveDate" >

                </div>
            </div>
        </div>

        <div class="control-group" >
            <label class="control-label" for="billingDate">Billing Date</label>
            <div class="controls">
                <div class="form-input-box" id="dob_input_box">
                    <input id="billingDate" name="billingDate" type="date" value=""   data-bind="datePicker: billingDate" >

                </div>
            </div>
        </div>

        <div class="control-group" >
            <label class="control-label" for="accSubmitDate">Account Submit Date</label>
            <div class="controls">
                <div class="form-input-box" id="dob_input_box">
                    <input id="accSubmitDate" name="accSubmitDate" type="date" value=""   data-bind="datePicker: accSubmitDate" >

                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="paymentType">Payment Type</label>
            <div class="controls">
                <input type="text" value="" class="required" id="paymentType" name="paymentType" data-bind="value:$root.paymentType" />         </div>
        </div>
        <div class="control-group" >
            <label class="control-label" for="paymentDate">Payment Date</label>
            <div class="controls">
                <div class="form-input-box" id="dob_input_box">
                    <input id="paymentDate" name="paymentDate" type="date" value=""   data-bind="datePicker: paymentDate" >

                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="user">Checked By</label>
            <div class="controls">
                <select id="user"  name="user" data-bind="options: $root.users, optionsValue: 'userId',
        optionsText: function(userId) {  return userId.firstName +'  '+ userId.lastName  },
         value: $root.checkedById, optionsCaption: 'Choose...'"></select> <!--'+ userId.middleName?'null':'' +'-->
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="paidById">Paid By</label>
            <div class="controls">
                <select id="paidById"  name="paidById" data-bind="options: $root.users, optionsValue: 'userId',
        optionsText: function(userId) {  return userId.firstName +'  '+ userId.lastName  },
         value: $root.paidById, optionsCaption: 'Choose...'"></select> <!--'+ userId.middleName?'null':'' +'-->
            </div>
        </div>
    <div class="control-group">
        <label class="control-label" for="billDescription">Description</label>
        <div class="controls">
            <textarea type="text" id="billDescription" name="billDescription" data-bind="value:$root.description"></textarea>
        </div>
    </div>
    

    <br clear="all"/>
    <div class="control-group">
        <label class="control-label"><div class="loading" id="vcardload"></div></label>
        <div class="controls">
            <button type="button"  class="btn btn-success" id="submit" data-bind="click:save"><i class="icon-fast-forward"></i>Save</button>
        </div>
    </div>
</form>
<div id="result" style="display:none" class="span12"></div>    


<script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>


<script type="text/javascript">

    //Overall viewmodel for this screen, along with initial state
    function BillViewModel() {
        var self = this;
        var tplEditable = '<div><input type="text" data-bind="attr: { \'class\': \'kgCellInput colt\' + $index()}, value: $parent.entity[$data.field]" /></div>';
        var deleteCellTemplate = '<div data-bind="" id="MyTemplateID" ><a style="margin-left:18px;" data-bind="click: function(data, event) { vm.removeItem(data, event); } "> remove</a></div>';
        //<input type="text" data-bind="attr: { 'class': 'kgCellText colt' + $index()}, value: $data.getProperty($parent)"/>
        self.items = ko.observableArray([

        ]);

        self.vendors = ko.observableArray([]);
        self.vendorId = ko.observable();
        self.billNo=ko.observable();
        self.availableItems = ko.observableArray([]);
        self.selectedItem = ko.observable();
        
        self.subItems = ko.observableArray([]);
        self.checked = ko.observableArray([]);
        
        self.organizations = ko.observableArray([]);
        self.departments = ko.observableArray([]);
        self.title = ko.observable();
        self.description = ko.observable();
        self.organizationId = ko.observable();
        self.departmentId = ko.observable();
        self.selectedListItem = ko.observableArray([]);
        this.mySelections = ko.observableArray([]);

        self.budgetHeads = ko.observableArray();
        self.budgetId=ko.observable();
        self.type=ko.observable();
        self.billAmount=ko.observable();

        self.receiveDate=ko.observable();
        self.billingDate=ko.observable();
        self.accSubmitDate=ko.observable();
        self.paymentDate=ko.observable();
        self.paymentType=ko.observable();
        self.users = ko.observableArray([]);
        self.checkedById=ko.observable();
        self.paidById=ko.observable();
        self.itemMasterId=ko.observable();
        self.jsonItemMaster=ko.observableArray([]);


        var Years = function(billYearText, billYearVal) {
            this.billYearText = billYearText;
            this.billYearVal = billYearVal;
        };
        var years=[];
        for (i = new Date().getFullYear(); i < 2100; i++)
        {
            years.push(new Years(i,i)); //years+=','+i;
        }
        self.billYears=ko.observableArray(years);
        self.billYear=ko.observable();
        self.billQuantity=ko.observable();
        
        self.selectedList = function (rowItem, event) {
            self.selectedListItem = rowItem;
            var itemId = rowItem.entity.categoryId;
            //get child items 
            if(itemId == '4'){ 
                $(".selectItems").show();
            }
            else{
                $(".selectItems").hide();
            }
            
        }
       
        self.addNewItem = function() {
            var i = 0;
            var ilength = self.items().length;
            if(self.items().length>0){
                while(i<ilength){	
                    var t = self.items();
                    var oid = t[i].categoryId;
                    if(oid == self.selectedItem().categoryId){
                        return;
                    }
                    else{
                        i++;
                    }
                }
            }
            self.items.push({categoryName:self.selectedItem().categoryName, billQuantity:1,categoryId:self.selectedItem().categoryId});
        }
        
        self.removeItem = function(d,e) { 
            self.items.remove(self.selectedListItem.entity) 
        }
	  
        self.save = function(){                        
            var valid = $("#billform").valid();
            if(!valid){
                alert("Errors in input");
                return;
            }
            
           // return false;
            $("#result").hide();  
            $('#submit').attr('disabled', 'disabled');
            $.post('<?=$base_url.IT_MODULE_FOLDER?>bill/save_Bill/<?=$editId?>',
            {
            	data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    vendorId:self.vendorId,
                    itemMasterId:self.itemMasterId,
                    title:self.title, // undefined
                    description:self.description,
                    Items:ko.toJSON(self.items),
                    subItems:ko.toJSON(self.subItems),
                    billYear:self.billYear,
                    billNo:self.billNo,
                    budgetId:self.budgetId,
                    type:self.type,
                    billAmount:self.billAmount,
                    receiveDate:self.receiveDate,
                    billingDate:self.billingDate,
                    accSubmitDate:self.accSubmitDate,
                    paymentDate:self.paymentDate,
                    paymentType:self.paymentType,
                    paidById:self.paidById,
                    checkedById:self.checkedById,

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
                    window.location = jsonData.url;                   
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
    var vm = new BillViewModel();
    var edit = <?=$edit?>

    $(document).ready(function(){   
    vm.organizations = <?=$jsonOrganization?>;
	vm.departments = <?=$jsonDepartment?>;
	vm.availableItems = <?=$jsonItemMaster?>;
    vm.jsonItemMaster=<?=$jsonItemMaster?>;
    vm.vendors = <?=$jsonVendor?>;
    vm.subItems = <?=$childItems?>;
    vm.budgetHeads=<?=$jsonBudgetHead?>;
    vm.users = <?=$jsonUser?>;


	if(edit){
            bill = $.parseJSON('<?=$jsonBill?>');
            itemsObj = $.parseJSON('<?=$jsonBillDetail?>');
            for(var i=0;i<itemsObj.length;i++){
                for(j=0;j<vm.availableItems.length;j++){	
                    if(itemsObj[i].categoryId == vm.availableItems[j].categoryId)
                        vm.items.push({categoryName: itemsObj[i].itemMaster[0].categoryName, billQuantity: itemsObj[i].billQuantity,categoryId:itemsObj[i].categoryId});
                }//{itemMasterName: "Tiancum", billQuantity: 43, categoryId:"1"}
            }
            if(bill[0].billTitle!=null&&bill[0].billTitle!='undefined')
                vm.title = bill[0].billTitle;
            else vm.title = '';
            if(bill[0].billDescription!=null&&bill[0].billDescription!='undefined')
                vm.description = bill[0].billDescription;
            else vm.description = '';

            vm.organizationId = bill[0].organizationId;
            vm.departmentId = bill[0].departmentId;
            
            subItemsBill = $.parseJSON('<?=$jsonBillSubItem?>');
            for(var i=0;i<subItemsBill.length;i++){
                for(j=0;j<vm.subItems.length;j++){	
                    if(subItemsBill[i].categoryId == vm.subItems[j].categoryId)
                        vm.subItems[j].selected = true;
                    else
                        vm.subItems[j].selected = false;
                }
            }
        }
        vm.errors = ko.validation.group(vm);
	ko.applyBindings(vm);
	$("#billform").validate({
            submitHandler: function(){
                $("#result").hide();  
                $("#vcardload").show();
                $('#submit').attr('disabled', 'disabled');           
                var formData = $("#vcardform").serialize();
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "<?=$base_url.IT_MODULE_FOLDER.'bill/save_Bill'?>",
                    data: formData,
                    dataType: 'json',
                    /*timeout : 60000,*/
                    success : function(data){
                        $("#vcardload").hide();
                        $('#submit').removeAttr("disabled");
                        if(data.success == false){                         
                            $("#result").html(data.msg);                    
                            $("#result").fadeIn("slow");
                            return false;                                
                        }
                        else if(data.success == true){
                            $("#vcardImage").attr("src",data.src);
                            $("#vdata").html(data.vd);     
                            return false;
                        }                        
                    },
                    error : function(e){
                        $("#vcardload").hide();
                        $('#submit').removeAttr("disabled");
                        alert("There was an error. Please try again later.");
                        return false;                        
                    }
                });
                return false;
            }          
        });      
    });
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
  
</script>
