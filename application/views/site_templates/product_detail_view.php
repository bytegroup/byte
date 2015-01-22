
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

    <h3>View Products</h3>    
    <hr style="margin: 5px 0"/>
   <!-- <div id="errormsg"><?/*=$errormsg*/?></div>-->
    <div class="row-fluid">
        <div class="span12">
            <table width="100%">
                <thead><tr>
                    <th align="left">Item name</th><th align="left">Product Code</th><th align="left" >Status</th>
                    <th align="left" >Received Date</th>
                </tr></thead>
                <tbody data-bind="foreach: items">
                    <tr>
                        <td ><input  disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" type="text" value="" id="itemName" name="itemName" data-bind="value:itemName" /></td>
                        <td ><input disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" value="" id="productCode" name="productCode" type="text"  data-bind="value: productCode" /></td>
                        <td ><input  disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" type="text" value="" id="status" name="status" data-bind="value:status" /></td>
                        <td ><input disabled="disabled" style="width:150px;border:none;background:none;background-color:transparent;" value="" id="receiveDate" name="receiveDate" type="text"  data-bind="value: receiveDate" /></td>

                    </tr>    
                </tbody>
            </table>

        </div>    
    </div>
    <br/>
    <br/>
    
    <br clear="all"/>
    <div class="control-group">
        <label class="control-label"><div class="loading" id="vcardload"></div></label>
        <div class="controls">
            <button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;"><i class="icon-fast-forward"></i> Back</button>
        </div>
    </div>
</form>
<div id="result" style="display:none" class="span12"></div>    


  <script type="text/javascript" src="<?=$base_url.ASSETS_FOLDER?>js/jquery.validate.js"></script>
  <script type="text/javascript">
  // Class to represent a row in the seat reservations grid




  // Overall viewmodel for this screen, along with initial state
function ReceiveViewModel() {

    var self = this;

    self.items = ko.observableArray([]);
    self.availableItems = ko.observableArray([]);
   // self.status = ko.observable();
   // self.productCode = ko.observable();
   //self.itemName = ko.observable();
   // self.receiveDate=ko.observable();
    //self.budgetHeads = ko.observableArray();
   // self.budgetTitle=ko.observable();
   // self.budgetId=ko.observable();
    // Computed data


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


        $('#submit').attr('disabled', 'disabled');

    }

}

  function addItem( initialItem) {
      var self = this;
      self.status = initialItem.status;
      self.productCode = initialItem.productCode;
      self.itemName = initialItem.itemName;
      self.receiveDate =initialItem.receiveDate;

  }


var vm = new ReceiveViewModel();


$(document).ready(function(){
   /* productInfo = $.parseJSON('<?/*=$jsonProductDetail*/?>');
    vm.status = productInfo[0].status;
    vm.productCode = productInfo[0].productCode;
    vm.itemName = productInfo[0].itemName;
    vm.receiveDate = productInfo[0].receiveDate;*/

    vm.availableItems = <?=$jsonProductDetail?>;

    for(j=0;j<vm.availableItems.length;j++){
        vm.items.push(new addItem(vm.availableItems[j]));
    }


    vm.errors = ko.validation.group(vm);
    ko.applyBindings(vm);

   /* $("#filesubmit").click(function(e){
        $("#load").show();
        $("#filesubmit").attr('disabled','true');
    });*/
});

     
</script>
