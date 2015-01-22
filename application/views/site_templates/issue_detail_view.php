
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
<form class="div-vcard form-horizontal" id="issueform" name="issueform" action="<?=$base_url.IT_MODULE_FOLDER?>issue/save_Issue" method="post">
    <!--<div class="control-group">
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

    </div>-->
    <div class="control-group">
        <label class="control-label" for="department">Department</label>
        <div class="controls">
            <select id="department"  name="department" data-bind="options: $root.departments, optionsValue: 'departmentId', optionsText: 'departmentName', value: $root.issueDepartmentId, optionsCaption: 'Choose...'"></select>
        </div>

    </div>
    <div class="control-group">
        <label class="control-label" for="user">User</label>
        <div class="controls">
            <select id="user"  name="user" data-bind="options: $root.users, optionsValue: 'userId',
            optionsText: function(userId) {  return userId.firstName +'  '+ userId.lastName  },
             value: $root.issueUserId, optionsCaption: 'Choose...'"></select> <!--'+ userId.middleName?'null':'' +'-->
        </div>

    </div>
    <div class="control-group">
        <label class="control-label" for="vendors">Vendor</label>
        <div class="controls">
            <input id="vendor" style="border:none;background:none;background-color:transparent;" type="text" disabled="disabled" name="vendor" data-bind="value: $root.vendorsName"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="issueDescription">Description</label>
        <div class="controls">
            <textarea type="text" id="issueDescription" name="issueDescription" data-bind="value:$root.issueDescription"></textarea>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="stockQuantity">Stock Quantity</label>
        <div class="controls">
            <input disabled="disabled" style="border:none;background:none;background-color:transparent;" type="text" value="" id="stockQuantity" name="stockQuantity" data-bind="value:$root.stockQuantity" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="issueQuantity">Issue Quantity</label>
        <div class="controls">
            <input  style="width:150px;background:none;background-color:transparent;" id="issueQuantity" name="issueQuantity" type="number"  data-bind="value: issueQuantity" /><!--onblur="checkNum(this);-->
        </div>
    </div>

    <h3>View Products</h3>
    <hr style="margin: 5px 0"/>
    <div class="row-fluid">
        <div class="span12">
            <table width="75%" id="productitems">
                <thead><tr>
                    <th align="left">Item name</th><th align="left">Product Code</th><th align="left" style="padding-left:30px;">Select</th>
                </tr></thead>
                <tbody data-bind="foreach: itemsDetail">
                <tr>
                    <td ><input disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" type="text" value="" id="itemName" name="itemName" data-bind="value:itemName" /></td>
                    <td ><input disabled="disabled" style="width:100px;border:none;background:none;background-color:transparent;" type="text" value="" id="productCode" name="productCode" data-bind="value: productCode" />
                    </td>
                    <td><input style="width:150px;background:none;background-color:transparent;" type="checkbox"  id="issued" name="issued"  data-bind="checked: issued" onclick="checkParentIssue( this);"/><!--onclick="checkParentIssue( productCode);"-->
                        <input style="width:150px;background:none;background-color:transparent;" type="hidden" value="" id="stockDetailId" name="stockDetailId"  data-bind="value: stockDetailId" />
                    </td>
                    <td ><input style="width:150px;background:none;background-color:transparent;" type="text" value="" id="prevIssueId" name="prevIssueId" data-bind="value: prevIssueId"  />
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>



    
    <br clear="all"/>
    <div class="control-group">
        <label class="control-label"><div class="loading" id="vcardload"></div></label>
        <div class="controls">
            <button type="button" class="btn btn-success" id="submit" onclick="window.location='<?php echo $cancel_issue;?>';">Cancel</button>
        </div>
        <div class="controls">
            <button type="button" class="btn btn-success" id="submit" data-bind="click:save"><i class="icon-fast-forward"></i> Save</button>
        </div>
    </div>
</form>
<div id="result" style="display:none" class="span12"></div>


<div id="issueDiv" style="display:none;" class="ui-dialog-titlebar-close">
    <iframe name="uploadFrame" style="display: none"></iframe>
    <h2>Select Parent Issue </h2>
    <form class="forms" id="issueform" method="post" target="uploadFrame" action="" enctype="multipart/form-data">
        <hr style="margin: 5px 0"/>
        <div class="row-fluid">
            <div class="span12">
                <table width="75%" >
                    <thead><tr>
                        <th align="left">Item name</th><th align="left">Product Code</th><th align="left" style="padding-left:10px;">Select</th>
                    </tr></thead>
                    <tbody data-bind="foreach: previousIssueitems">
                    <tr>
                        <td ><input disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" type="text" value="" id="prevItemName" name="prevItemName" data-bind="value:prevItemName" /></td>
                        <td ><input disabled="disabled" style="width:100px;border:none;background:none;background-color:transparent;" type="text" value="" id="prevProductCode" name="prevProductCode" data-bind="value: prevProductCode" />
                        </td>
                        <td><input style="width:50px;background:none;background-color:transparent;" type="radio"  id="prevIssued" name="prevIssued"  data-bind="checked: prevIssued" onclick="setPrevIssue( this);" /><!--onclick="checkParentIssue( productCode);"-->
                        </td>
                        <td><input style="width:150px;background:none;background-color:transparent;" type="hidden" value="" id="issueId" name="issueId"  data-bind="value: issueId" />
                        </td>

                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </form>
</div>



  <script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>
  <script type="text/javascript">
    console.log("tttest");

  var thirdColumn=null;
  var prevIssuedId=null;
  function setPrevIssue(elem) {

      var currentRowPrev = elem.parentNode.parentNode;
      var secondColumnPrev = currentRowPrev.getElementsByTagName("td")[3];
      //thirdColumn.children[0].text =secondColumnPrev.children[0].value;
      $(thirdColumn).find('input').val(secondColumnPrev.children[0].value);
      $(thirdColumn).find('input').trigger('change');
     // console.log($(thirdColumn).html());
      //vm.itemsDetail.removeAll();
      /*for(j=0;j<vm.availableItemsDetail.length;j++){
          vm.itemsDetail.push(new addItemDetail(vm.availableItemsDetail[j]));
      }*/
      //vm.prevIssueId = secondColumnPrev.children[0].value;
  }

  function checkParentIssue(elem) {

          var currentRow = elem.parentNode.parentNode;
          var secondColumn = currentRow.getElementsByTagName("td")[1];
          var productCode= secondColumn.children[0].value;
          var productCode= productCode.substr(0,productCode.lastIndexOf("/"));

           thirdColumn = currentRow.getElementsByTagName("td")[3];

         // alert("My text is: " + productCode );
      if (elem.checked==true){
      $.post('<?=$base_url.IT_MODULE_FOLDER?>issue/check_Parent_Issue/',
          {
              data:
              {
                  productCode:productCode

              }
          }
          ,
          function(data){
             // $('#submit').removeAttr("disabled");
              jsonData = $.parseJSON( data );
              if(jsonData.success == false){

               return false;
               }
               else if(jsonData.success == true){
                  var array = JSON.parse(jsonData.jsonPrevIssue);
                  for (var x in vm.previousIssueitems) {
                      vm.previousIssueitems.pop();
                  }

                  for(j=0;j<array.length;j++){
                      vm.previousIssueitems.push(new addPrevItem(array[j]));
                  }

                $("#issueDiv").dialog({ minWidth: 600, buttons: [ { text: "Ok", click: function() {
                    //vm.prevIssueId=thirdColumn.children[0].value;
                    $( this ).dialog( "close" ); } } ,
                    { text: "Cancel", click: function() {
                    thirdColumn.children[0].value =null;
                        $( this ).dialog( "close" ); } } ] });

                  //fourthColumn.children[0].value=issuedId;

               }
          })
      }
  }


  // Class to represent a row in the seat reservations grid
  



  function addItemDetail(initialItem) {
      var self = this;
      self.productCode = initialItem.productCode;
      self.stockDetailId = initialItem.stockDetailId;
      self.issued = ko.observable();
      //if(thirdColumn!==null){
     //  self.prevIssueId = ko.observable(thirdColumn.children[0].value);
     // }else{
          self.prevIssueId = ko.observable();
    //  }
     self.itemName = initialItem.itemName;
      //self.setValue= function() {
      //   self.prevIssueId(self.prevIssueId());
     // };



  }


  function addPrevItem(initialItem) {
      var self = this;
      self.prevProductCode = initialItem.productCode;
      self.issueId = initialItem.issueId;
      self.prevIssued = ko.observable();
      self.prevItemName = initialItem.itemName;

  }

  // Overall viewmodel for this screen, along with initial state
function IssueViewModel() {
    var self = this;
    self.total = ko.observable();

    self.availableItemsDetail = ko.observableArray([]);
    self.itemsDetail = ko.observableArray([]);

    self.previousIssue = ko.observableArray([]);
    self.previousIssueitems = ko.observableArray([]);

    self.organizationName = ko.observableArray();
    self.departmentName = ko.observableArray();
    self.vendors = ko.observableArray([]);
    self.departments = ko.observableArray([]);
    self.users = ko.observableArray([]);
    self.issueDescription = ko.observable();
    self.organizationId = ko.observable();
    self.departmentId = ko.observable();
    self.vendorId = ko.observable();
    self.quotationId = ko.observable();
    self.stockQuantity = ko.observable();
    self.stockId = ko.observable();
    self.itemMasterId = ko.observable();
    self.vendorsName = ko.observable();
    self.stockDetailIds=ko.observableArray();


    self.issueQuantity = ko.observable();

    //self.setValuess = function() {
      //  e.prevIssueId(thirdColumn.children[0].value);
       // e.prevIssueId[this.selectedProperty()](this.currentValue());
    //}
    self.save = function(){                        

        var stockQuantity = $("#stockQuantity").val();
        var issueQuantity = $("#issueQuantity").val();

        if(parseInt(stockQuantity) < parseInt(issueQuantity)){
            alert("Issue quantity must be less than or equal to stock quantity");
            return;
        }

        $("#result").hide();  
        $('#submit').attr('disabled', 'disabled');
        $.post('<?=$base_url.IT_MODULE_FOLDER?>issue/save_Issue/<?=$editId?>',
        {
            data:
                {
                    organizationId:self.organizationId,
                    departmentId:self.departmentId,
                    vendorId:self.vendorId,
                    quotationId:self.quotationId,
                    issueDepartmentId:self.issueDepartmentId,
                    issueUserId:self.issueUserId,
                    issueDescription:self.issueDescription,
                    issueQuantity:self.issueQuantity,
                    stockId:self.stockId,
                    stockQuantity:self.stockQuantity,
                    itemMasterId:self.itemMasterId,
                    Items:ko.toJSON(self.itemsDetail),
                    edit: edit
                }
        }                                    
        , 
        function(data){
            $('#submit').removeAttr("disabled");
            jsonData = $.parseJSON( data );
            /*if(jsonData.success == false){
                $("#result").html("Could not Save. Please try again later.");                    
                $("#result").fadeIn("slow");
                return false;                                
            }
            else if(jsonData.success == true){
                alert("Saved Successfully");
               // $("#issueDiv").show();
               // $("#quotationIdFile").val(jsonData.result);
                $("#redirectUrl").val(jsonData.url);
                //window.location = jsonData.url;                   
            }       */
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
var vm = new IssueViewModel();
var edit = <?=$edit?>;


$(document).ready(function(){
    requisitionInfo = $.parseJSON('<?=$jsonQuotationInfo?>');   
    vm.organizationName = requisitionInfo[0].organizationName;
    vm.departmentName = requisitionInfo[0].departmentName;
    vm.organizationId = requisitionInfo[0].organizationId;
    vm.departmentId = requisitionInfo[0].departmentId;
    vm.quotationId = requisitionInfo[0].quotationId;
    vm.vendorId=requisitionInfo[0].vendorsId;
    vm.vendors = <?=$jsonVendor?>;
    vm.departments = <?=$jsonDepartment?>;
    vm.users = <?=$jsonUser?>;
    vm.availableItems = <?=$jsonItemMaster?>;
    vm.availableItemsDetail = <?=$jsonItemDetail?>;
    stockInfo = $.parseJSON('<?=$jsonItemMaster?>');
    vm.stockQuantity= stockInfo[0].stockQuantity;
    vm.stockId= stockInfo[0].stockId;
    vm.itemMasterId=stockInfo[0].itemMasterId;
    vm.vendorsName=stockInfo[0].vendorsName;

    if(<?=$jsonPrevIssue?>!==null){
        vm.previousIssue  = <?=$jsonPrevIssue?>;
    }



    if(!edit){
        for(j=0;j<vm.availableItemsDetail.length;j++){
            vm.itemsDetail.push(new addItemDetail(vm.availableItemsDetail[j]));
        }
    }



    vm.errors = ko.validation.group(vm);
    ko.applyBindings(vm);

});


     
</script>
