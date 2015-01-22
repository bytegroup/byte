
<style type="text/css">
    input, textarea:disabled {
    	background:#fff;
        border:none;	
    }
    
</style> 
<script>
$(document).ready(function(e){
    $('#testing').click(function(){
        $.post('http://localhost/ocl-backoffice/messages/testEntry', null, function(data){
            alert(data);
        })
    });
 });   
</script>
<button id="testing">test</button>
<div class='ui-widget-content ui-corner-all datatables'>
    <h3 class="ui-accordion-header ui-helper-reset ui-state-default form-title">
        <div class='floatL form-title-left'>
            View Message
        </div> 		
        <div class='floatR'>
            <a href="<?=$base_url?>messages/" class='btn' >
                Back to list			
            </a>
        </div>
        <div class='clear'></div>
    </h3>
    <div class='form-content form-div'>
        <form action="#" method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data" accept-charset="utf-8">		
            <div>
            	<div>
                    <div class='form-field-box even' id="messageReceiverId_field_box">
                        <div class='form-display-as-box' id="messageReceiverId_display_as_box">
                            Sender :
                        </div>
                    <div class='form-input-box' id="messageReceiverId_input_box">
                        <input disabled="disabled" id='field-messageReceiverId'  name='messageReceiverId' class='chosen-select' style='width:300px' value='<?=$messageInfo[0]->senderName?>' />
                    </div>
                    <div class='clear'></div>	
                </div>	                
                <div>
                    <div class='form-field-box even' id="messageReceiverId_field_box">
                        <div class='form-display-as-box' id="messageReceiverId_display_as_box">
                            Receiver :
                        </div>
                    <div class='form-input-box' id="messageReceiverId_input_box">
                        <input disabled="disabled" id='field-messageReceiverId'  name='messageReceiverId' class='chosen-select' style='width:300px' value='<?=$messageInfo[0]->userName?>' />
                    </div>
                    <div class='clear'></div>	
                </div>
                <div class='form-field-box odd' id="messageSubject_field_box">
                    <div class='form-display-as-box' id="messageSubject_display_as_box">
                        Subject :
                    </div>
                    <div class='form-input-box' id="messageSubject_input_box">
                        <textarea disabled="disabled" id='field-messageSubject' name='messageSubject' ><?=$messageInfo[0]->messageSubject?></textarea>							 		</div>
                    <div class='clear'></div>	
                </div>
                <div class='form-field-box even' id="messageBody_field_box">
                    <div class='form-display-as-box' id="messageBody_display_as_box">
                        Message Body :
                    </div>
                    <div class='form-input-box' id="messageBody_input_box">
                        <textarea disabled="disabled" id='field-messageBody' name='messageBody' class='texteditor' ><?=$messageInfo[0]->messageBody?></textarea>					 		</div>
                    <div class='clear'></div>	
                </div>
                <div class='form-field-box odd' id="organizationId_field_box">
                    <div class='form-display-as-box' id="organizationId_display_as_box">
                        Organization :
                    </div>
                    <div class='form-input-box' id="organizationId_input_box">
                        <input disabled="disabled" id='field-organizationId' name='organizationId' type='text' value='<?=$messageInfo[0]->organizationName?>' class='numeric' maxlength='20' />
                    </div>
                    <div class='clear'></div>	
                </div>
                <div class='form-field-box even' id="messageCreated_field_box">
                    <div class='form-display-as-box' id="messageCreated_display_as_box">
                        Creation Date :
                    </div>
                    <div class='form-input-box' id="messageCreated_input_box">
                        <input disabled="disabled" id='field-messageCreated' name='messageCreated' type='text' value='<?=$messageInfo[0]->messageCreated?>' maxlength='19' class='' /> 
                    </div>
                    <div class='clear'></div>	
                </div>
                <div class='form-field-box odd' id="messageOpened_field_box">
                    <div class='form-display-as-box' id="messageOpened_display_as_box">
                        Status :
                    </div>
                    <div class='form-input-box' id="messageOpened_input_box">
                        <input disabled="disabled" id='field-messageCreated' name='messageCreated' type='text' value='<?=$messageInfo[0]->messageOpened?>' maxlength='19' class='' /> 			
                    </div>
                    <div class='clear'></div>	
                </div>
            </div>	
            <div class='buttons-box'>
                <div class='form-button-box'>
                    <a href="<?=$base_url?>messages/" class='btn' >Back to list</a>
                </div>			
                <div class='clear'></div>	
            </div>
    	</form>	
    </div>
</div>