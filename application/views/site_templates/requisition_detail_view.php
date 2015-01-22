
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
<form class="div-vcard form-horizontal" id="requisitionform" name="requisitionform" action="http://localhost/ocl-backoffice/it-inventory/requisition/add_Requisition" method="post">
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
        <label class="control-label" for="requisitionTitle">Requisition Title</label>
        <div class="controls">
            <input type="text" value="" class="required" id="requisitionTitle" name="requisitionTitle" data-bind="value:$root.title" />         </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="requisitionDescription">Description</label>
        <div class="controls">
            <textarea type="text" id="requisitionDescription" name="requisitionDescription" data-bind="value:$root.description"></textarea>
        </div>
    </div>
    
    <h3>Add Products</h3>    
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span12">
            <select data-bind="options: $root.availableItems,  optionsText: 'itemName',  value:selectedItem"></select>
            <button data-bind="click: addNewItem, enable: items.length < 5">Add item</button>
            <div style="margin-top: 40px;"></div>
            <div style="height: 200px; width:350px;float:left;" class="gridStyle" data-bind="koGrid: gridOptions"></div>
            <!--<div style="height: 144px; width:200px;float:left;padding:28px;" class="selectItems">
                <ul data-bind="foreach: subItems">
                    <li>
                        <input type="checkbox" data-bind="checked: $data.selected" />
                        Item Name: <span data-bind="text: $data.itemName"></span>
                    </li>
                </ul>
            </div>-->
        </div>
<!--        <div class="gridStyle" data-bind="koGrid: gridOptions"></div>-->
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

<!--<div data-bind="kgCell: $cell" id="MyTemplateID" >
    <a data-bind="click: function(data, event) { vm.removeItem(data, event); } ">remove</a>
</div>-->
<!--<div data-bind="kgCell: $cell"> 
<select data-mini="true" data-bind="options: $cellValue, event : { change : function(data, event) { mymodel.gridCallBack(data, event, $cell, $cellValue); } } "> <option value="undefined">Selecione...</option> 
</select> 
</div>-->
<!--<script type="text/html" id="deleteCellTemplate">   
    <div data-bind="kgCell: { value: 'delete' }"> 
       <button class="btn">Delete</button> 
    </div> 
</script>-->

<script type="text/javascript">
    //Overall viewmodel for this screen, along with initial state
    function RequistionViewModel() {
        var self = this;
        var tplEditable = '<div><input type="text" data-bind="attr: { \'class\': \'kgCellInput colt\' + $index()}, value: $parent.entity[$data.field]" /></div>';
        var deleteCellTemplate = '<div data-bind="" id="MyTemplateID" ><a style="margin-left:18px;" data-bind="click: function(data, event) { vm.removeItem(data, event); } "> remove</a></div>';
        //<input type="text" data-bind="attr: { 'class': 'kgCellText colt' + $index()}, value: $data.getProperty($parent)"/>
        self.items = ko.observableArray([
            /*{itemName: "Tiancum", orderedQuantity: 43, itemMasterId:"4"}*/
        ]);
        self.gridOptions = {
            data: self.items,
            selectedItems: self.mySelections,
            displaySelectionCheckbox: false,
            multiSelect: false,
            showGroupPanel: false,
            showColumnMenu: false,
            showFilter: false,
            maintainColumnRatios: true,
            afterSelectionChange: function(rowItem, event){self.selectedList(rowItem, event);} , 
            columnDefs: ko.observableArray( [{field: 'itemMasterId', displayName: 'Item Id',  width: '18%' },
                                            {field: 'itemName', displayName: 'Name', width: '27%' },
                                            {field: 'orderedQuantity', displayName: 'Quantity', cellTemplate: tplEditable, width: '25%' },
                                            {field: '',  cellTemplate: deleteCellTemplate, width: '25%' }])
        };

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
        
        self.selectedList = function (rowItem, event) {
            self.selectedListItem = rowItem;
            var itemId = rowItem.entity.itemMasterId;
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
                    var oid = t[i].itemMasterId;
                    if(oid == self.selectedItem().itemMasterId){
                        return;
                    }
                    else{
                        i++;
                    }
                }
            }
            self.items.push({itemName:self.selectedItem().itemName, orderedQuantity:1,itemMasterId:self.selectedItem().itemMasterId});
        }
        
        self.removeItem = function(d,e) { 
            self.items.remove(self.selectedListItem.entity) 
        }
	  
        self.save = function(){                        
            var valid = $("#requisitionform").valid();
            if(!valid){
                alert("Errors in input");
                return;
            }
            
           // return false;
            $("#result").hide();  
            $('#submit').attr('disabled', 'disabled');
            $.post('<?=$base_url.IT_MODULE_FOLDER?>requisition/save_Requisition/<?=$editId?>', 
            {
            	data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    title:self.title, // undefined
                    description:self.description,
                    Items:ko.toJSON(self.items),
                    subItems:ko.toJSON(self.subItems),
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
        /*self.setData = function(orderinfo, products){
            for(prop in orderinfo)
                self[prop] = orderinfo[prop];
        }*/			
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
    var vm = new RequistionViewModel();
    var edit = <?=$edit?>

    $(document).ready(function(){   
        vm.organizations = <?=$jsonOrganization?>;
	vm.departments = <?=$jsonDepartment?>;
	vm.availableItems = <?=$jsonItemMaster?>;
        
        vm.subItems = <?=$childItems?>;
        
	if(edit){
            requisition = $.parseJSON('<?=$jsonRequisitions?>');
            itemsObj = $.parseJSON('<?=$jsonRequisitionsDetail?>');  
            for(var i=0;i<itemsObj.length;i++){
                for(j=0;j<vm.availableItems.length;j++){	
                    if(itemsObj[i].itemMasterId == vm.availableItems[j].itemMasterId)
                        vm.items.push({itemName: itemsObj[i].itemMaster[0].itemName, orderedQuantity: itemsObj[i].orderedQuantity,itemMasterId:itemsObj[i].itemMasterId});
                }//{itemMasterName: "Tiancum", orderedQuantity: 43, itemMasterId:"1"}
            }
            if(requisition[0].requisitionTitle!=null&&requisition[0].requisitionTitle!='undefined')
                vm.title = requisition[0].requisitionTitle;
            else vm.title = '';
            if(requisition[0].requisitionDescription!=null&&requisition[0].requisitionDescription!='undefined')
                vm.description = requisition[0].requisitionDescription;
            else vm.description = '';

            vm.organizationId = requisition[0].organizationId;
            vm.departmentId = requisition[0].departmentId;
            
            subItemsRequistion = $.parseJSON('<?=$jsonRequisitionsSubItem?>');  
            for(var i=0;i<subItemsRequistion.length;i++){
                for(j=0;j<vm.subItems.length;j++){	
                    if(subItemsRequistion[i].itemMasterId == vm.subItems[j].itemMasterId)
                        vm.subItems[j].selected = true;
                    else
                        vm.subItems[j].selected = false;
                }
            }
        }
        vm.errors = ko.validation.group(vm);
	ko.applyBindings(vm);
	$("#requisitionform").validate({
            submitHandler: function(){
                $("#result").hide();  
                $("#vcardload").show();
                $('#submit').attr('disabled', 'disabled');           
                var formData = $("#vcardform").serialize();
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "<?=$base_url.IT_MODULE_FOLDER.'requisition/save_Requisition'?>",
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
  
  
</script>
