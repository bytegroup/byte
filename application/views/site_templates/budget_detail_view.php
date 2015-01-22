
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

<form class="div-vcard form-horizontal" id="budgetform" name="budgetform" action='<?=$base_url.IT_MODULE_FOLDER?>budget/save_Budget/<?=$editId?>' method="post">
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
        <label class="control-label" for="budgetTitle">Budget Title</label>
        <div class="controls">
            <input type="text" value="" class="required" id="budgetTitle" name="budgetTitle" data-bind="value:$root.title" />         </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="budgetYear">Budget Year</label>
        <div class="controls">
            <select id="budgetYear"  class="required" name="budgetYear" data-bind="options: $root.budgetYears, optionsValue: 'budgetYearVal',
            optionsText: 'budgetYearText',   value: $root.budgetYear,optionsCaption: 'Choose...'""></select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="budgetQuantity">Budget Quantity</label>
        <div class="controls">
            <input type="text" value="" class="required" id="budgetQuantity" name="budgetQuantity" data-bind="value:$root.budgetQuantity" />         </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="budgetDescription">Description</label>
        <div class="controls">
            <textarea type="text" id="budgetDescription" name="budgetDescription" data-bind="value:$root.description"></textarea>
        </div>
    </div>

    <!--<h3>Add Category</h3>
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span12">
            <select data-bind="options: $root.availableItems,  optionsText: 'categoryName',  value:selectedItem"></select>
            <button data-bind="click: addNewItem, enable: items.length < 5">Add Category</button>
            <div style="margin-top: 40px;"></div>
            <div style="height: 200px; width:350px;float:left;" class="gridStyle" data-bind="koGrid: gridOptions"></div>-->
    <!--<div style="height: 144px; width:200px;float:left;padding:28px;" class="selectItems">
        <ul data-bind="foreach: subItems">
            <li>
                <input type="checkbox" data-bind="checked: $data.selected" />
                Category Name: <span data-bind="text: $data.categoryName"></span>
            </li>
        </ul>
    </div>-->
    <!--</div>

</div>-->

    <br clear="all"/>
    <div class="control-group">
        <label class="control-label"><div class="loading" id="vcardload"></div></label>
        <div class="controls">
            <button type="button" class="btn btn-success" id="submit" onclick="window.location='<?php echo $cancel_issue;?>';">Cancel</button>
        </div>
        <div class="controls">
            <button type="button"  class="btn btn-success" id="submit" data-bind="click: save"><i class="icon-fast-forward"></i>Save</button>
        </div>
    </div>
</form>
<div id="result" style="display:none" class="span12"></div>


<script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>

<script type="text/javascript">


function BudgetViewModel() {

    //"use sctrict"

    var self = this;
    var tplEditable = '<div><input type="text" data-bind="attr: { \'class\': \'kgCellInput colt\' + $index()}, value: $parent.entity[$data.field]" /></div>';
    var deleteCellTemplate = '<div data-bind="" id="MyTemplateID" ><a style="margin-left:18px;" data-bind="click: function(data, event) { vm.removeItem(data, event); } "> remove</a></div>';
    //<input type="text" data-bind="attr: { 'class': 'kgCellText colt' + $index()}, value: $data.getProperty($parent)"/>
    self.items = ko.observableArray([

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
        columnDefs: ko.observableArray( [{field: 'categoryId', displayName: 'Id',  width: '18%' },
            {field: 'categoryName', displayName: 'Name', width: '27%' },
            {field: 'budgetQuantity', displayName: 'Quantity', cellTemplate: tplEditable, width: '25%' },
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

    var Years = function(budgetYearText, budgetYearVal) {
        this.budgetYearText = budgetYearText;
        this.budgetYearVal = budgetYearVal;
    };
    var years=[];
    for (i = new Date().getFullYear(); i < 2100; i++)
    {
        years.push(new Years(i,i)); //years+=','+i;
    }

    self.budgetYears=ko.observableArray(years);
    self.budgetYear=ko.observable();
    self.budgetQuantity=ko.observable();

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
        self.items.push({categoryName:self.selectedItem().categoryName, budgetQuantity:1,categoryId:self.selectedItem().categoryId});
    }

    self.removeItem = function(d,e) {
        self.items.remove(self.selectedListItem.entity)
    }

    self.save = function(){
        var valid = $("#budgetform").valid();
        if(!valid){
            alert("Errors in input");
            return;
        }

        // return false;
        $("#result").hide();
        $('#submit').attr('disabled', 'disabled');

        $.post('<?=$base_url.IT_MODULE_FOLDER?>budget/save_Budget/<?=$editId?>',
            {
                data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    title:self.title, // undefined
                    description:self.description,
                    Items:ko.toJSON(self.items),
                    subItems:ko.toJSON(self.subItems),
                    budgetYear:self.budgetYear,
                    budgetQuantity:self.budgetQuantity,
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
var vm = new BudgetViewModel();
var edit = <?=$edit?>

    $(document).ready(function(){

        vm.organizations = <?=$jsonOrganization?>;
        vm.departments = <?=$jsonDepartment?>;
        vm.availableItems = <?=$jsonItemMaster?>;

        vm.subItems = <?=$childItems?>;
        //  vm.budgetYears=[2014->2014,2015->2015,2016->2015,2017->2015];

        //vm.budgetYear=budgetYearsInfo[0].organizationName
        //vm.organizationName = itemInfo[0].organizationName;
        /*  for (i = new Date().getFullYear(); i > 1900; i--)
         {
         $('#budgetYear').append($('<option />').val(i).html(i));
         }*/
        if(edit){

            budget = $.parseJSON('<?=$jsonBudget?>');

            itemsObj = '<?php echo $jsonBudgetDetail?>';
            console.log(itemsObj);
            for(var i1=0;i1<itemsObj.length;i1++){
                for(j1=0;j1<vm.availableItems.length;j1++){
                    if(itemsObj[i1].categoryId == vm.availableItems[j1].categoryId)
                        vm.items.push({categoryName: itemsObj[i1].itemMaster[0].categoryName, budgetQuantity: itemsObj[i1].budgetQuantity,categoryId:itemsObj[i1].categoryId});
                }//{itemMasterName: "Tiancum", budgetQuantity: 43, categoryId:"1"}
            }
            if(budget[0].budgetTitle!=null&&budget[0].budgetTitle!='undefined')
                vm.title = budget[0].budgetTitle;
            else vm.title = '';
            if(budget[0].budgetDescription!=null&&budget[0].budgetDescription!='undefined')
                vm.description = budget[0].budgetDescription;
            else vm.description = '';

            vm.organizationId = budget[0].organizationId;
            vm.departmentId = budget[0].departmentId;

            subItemsBudget = $.parseJSON('<?=$jsonBudgetSubItem?>');
            for(var i=0;i<subItemsBudget.length;i++){
                for(j=0;j<vm.subItems.length;j++){
                    if(subItemsBudget[i].categoryId == vm.subItems[j].categoryId)
                        vm.subItems[j].selected = true;
                    else
                        vm.subItems[j].selected = false;
                }
            }
        }
        vm.errors = ko.validation.group(vm);
        ko.applyBindings(vm);
        $("#budgetform").validate({
            submitHandler: function(){
                $("#result").hide();
                $("#vcardload").show();
                $('#submit').attr('disabled', 'disabled');
                var formData = $("#vcardform").serialize();
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "<?=$base_url.IT_MODULE_FOLDER.'budget/save_Budget'?>",
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