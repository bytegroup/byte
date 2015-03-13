<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 3/10/15
 * Time: 1:26 PM
 */
?>
<?php
class Filter_Form{
    private $html='';
    private $fields= null;
    private $css = '';
    private $js = '';
    public function __construct() {

    }

    public function set_filter_fields($fields){
        $this->fields=$fields;
    }
    public function get_filter_form(){
        $this->init_form();
        $this->html .= '<div class="row">';
        foreach($this->fields as $field=>$type){
            $this->set_type($field, $type);
        }
        $this->html .= '</div>';
        $this->_buttons();
        $this->end_form();
        $this->set_css();
        $this->set_js();
        return $this->html;
    }

    private function set_type($field, $type){
        $data=null;
        if(is_array($type)){
            $data= $type[1];
            $type= $type[0];
        }
        switch($type){
            case 'text': $this->_text_field($field);
                break;
            case 'date': $this->_date_field($field);
                break;
            case 'select': $this->_select_field($field, $data);
                break;
            default:
                break;
        }
    }

    private function _text_field($field){
        $label= $field;
        $field=mysql_real_escape_string($field);
        $field = strtolower(str_replace(" ", "_",$field));
        $this->html .= '<div class="control-group span5">';

        $this->html .= '<label class="control-label span4" for="'.$field.'">'.$label.'</label>';
        $this->html .= '<input id="'.$field.'" name="'.$field.'" placeholder="'.$label.'" class="span7" type="text">';
        $this->html .= '</div>';

    }
    private function _date_field($field){
        $label= $field;
        $field=mysql_real_escape_string($field);
        $field = strtolower(str_replace(" ", "_",$field));
        $this->html .= '<div class="control-group span5">';

        $this->html .= '<label class="control-label span4" for="'.$field.'">'.$label.'</label>';
        $this->html .= '<input id="'.$field.'" name="'.$field.'" placeholder="'.$label.'" class="datepicker span7" type="text" maxlength="10">';
        $this->html .= '</div>';

    }
    private function _select_field($field, $options){
        $label= $field;
        $field=mysql_real_escape_string($field);
        $field = strtolower(str_replace(" ", "_",$field));
        $this->html .= '<div class="control-group span5">';

        $this->html .= '<label class="control-label span4" for="'.$field.'">'.$label.'</label>';
        $this->html .= '<select data-placeholder="Select '.$label.'" id="'.$field.'" name="'.$field.'" class="span7">';
        $this->html .= '<option></option>';
        foreach($options as $val=>$option){
            $this->html .= '<option value="'.$val.'">'.$option.'</option>';
        }
        $this->html .= '</select>';
        $this->html .= '</div>';

    }

    private function _buttons(){
        $this->html .= '<div class="control-group row pagination-centered">';

        $this->html .= '<input type="button" id="filter-button" class="btn span2" value="Filter Table"/>&nbsp;&nbsp;';
        $this->html .= '<input type="reset" id="filter-reset" class="btn span2" value="Clear"/>&nbsp;&nbsp;';
        $this->html .= '<input type="button" id="filter-excel" class="btn span2" value="Excel"/>';
        $this->html .= '</div>';
    }
    private function init_form(){
        $this->html .= '<form class="form-inline" id="filter-form">';
    }
    private function end_form(){
        $this->html .= '</form>';
    }
    private function set_css(){
        $this->css .='
        <style type="text/css">
            .row-fluid form#filter-form [class*="span"]:first-child {  margin-left: 2.564102564102564%;  }
        </style>
        ';
        $this->html .= $this->css;
    }
    private function set_js(){
        $loaderImg= '<img src="'.base_url('ajax-loader.gif').'" border="0" id= "\'+source+\'"_ajax-_loader" class="dd_ajax_loader" style="display: none;"/>';
        $this->js .= '<script type="text/javascript">';
        $this->js .= '$(document).ready(function() {
                $("#filter-form input.datepicker").datepicker({
                    changeYear: true,
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    showButtonPanel: true,
                    closeText: "Close"
                });
                $("#filter-form select").chosen({allow_single_deselect: true});
                $("#filter-form #filter-reset").click(function(){
                    $("#filter-form select").val("").trigger("chosen:updated");
                });
            });
            ';
        $this->js .= "
            function get_dependent_options(source, target, url){
                var ID= $('#' + source).val();
                var el = $('#' + target);
                if(ID==null || ID=='')ID=0;
                $('#'+source).append('$loaderImg');
                $('#'+source+'_ajax_loader').show();
                el.empty();
                $.post(
                    url + ID,
                    {},
                    function(data){
                        el.append('<option></option>');
                        if(data != null){
                            $.each(data, function(key, val) {
                                el.append($('<option></option>')
                                    .attr('value', key).text(val));
                            });
                        }
                    },
                    'json'
                )
                .fail(function() {
                    //alert( 'error' );
                })
                .always(function() {
                    $('#'+source+'_ajax_loader').hide();
                    el.chosen().trigger('chosen:updated');
                });
            }
        ";
        $this->js .= '</script>';
        $this->html .= $this->js;
    }
}
?>