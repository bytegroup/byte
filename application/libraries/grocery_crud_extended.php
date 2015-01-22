<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 11/28/14
 * Time: 2:46 PM
 */

///In the class mentioned below.. add a variable ... callback_read_field
class Grocery_CRUD extends grocery_CRUD_States
{

    protected $callback_read_field = array();

//And a function the accept the callbacks
    /**
     *
     * Used to bypass the default formatting and allow user to achieve custom formatting for the view of a field
     * @param string $field
     * @param mixed $callback
     */
    public function callback_read_field($field, $callback = null)
    {
        $this->callback_read_field[$field] = $callback;
        return $this;
    }


//Now update this function to manage the field outputs using callbacks if they are defined for the same
    protected function get_read_input_fields($field_values = null)
    {
        $read_fields = $this->get_read_fields();

        $this->field_types = null;
        $this->required_fields = null;

        $read_inputs = array();
        foreach ($read_fields as $field) {
            if (!empty($this->change_field_type)
                && isset($this->change_field_type[$field->field_name])
                && $this->change_field_type[$field->field_name]->type == 'hidden'
            ) {
                continue;
            }
            $this->field_type($field->field_name, 'readonly');
        }

        $fields = $this->get_read_fields();
        $types = $this->get_field_types();

        $input_fields = array();

        foreach ($fields as $field_num => $field) {
            $field_info = $types[$field->field_name];

            if (isset($field_info->db_type) && ($field_info->db_type == 'tinyint' || ($field_info->db_type == 'int' && $field_info->db_max_length == 1))) {
                $field_value = $this->get_true_false_readonly_input($field_info, $field_values->{$field->field_name});
            } else {
                $field_value = !empty($field_values) && isset($field_values->{$field->field_name}) ? $field_values->{$field->field_name} : null;
            }
            if (!isset($this->callback_read_field[$field->field_name])) {
                $field_input = $this->get_field_input($field_info, $field_value);
            } else {
                $primary_key = $this->getStateInfo()->primary_key;
                $field_input = $field_info;
                $field_input->input = call_user_func($this->callback_read_field[$field->field_name], $field_value, $primary_key, $field_info, $field_values);
            }

            switch ($field_info->crud_type) {
                case 'invisible':
                    unset($this->read_fields[$field_num]);
                    unset($fields[$field_num]);
                    continue;
                    break;
                case 'hidden':
                    $this->read_hidden_fields[] = $field_input;
                    unset($this->read_fields[$field_num]);
                    unset($fields[$field_num]);
                    continue;
                    break;
            }

            $input_fields[$field->field_name] = $field_input;
        }

        return $input_fields;
    }

    protected function get_read_fields()
    {
        if($this->read_fields_checked === false)
        {
            $field_types = $this->get_field_types();
            if(!empty($this->read_fields))
            {
                foreach($this->read_fields as $field_num => $field)
                {
                    if(isset($this->display_as[$field]))
                        $this->read_fields[$field_num] = (object)array('field_name' => $field, 'display_as' => $this->display_as[$field]);
                    else
                        $this->read_fields[$field_num] = (object)array('field_name' => $field, 'display_as' => $field_types[$field]->display_as);
                }
            }
            else
            {
                $this->read_fields = array();
                foreach($field_types as $field)
                {
                    //Check if an unset_read_field is initialize for this field name
                    if($this->unset_read_fields !== null && is_array($this->unset_read_fields) && in_array($field->name,$this->unset_read_fields))
                        continue;

                    if(!isset($field->db_extra) || $field->db_extra != 'auto_increment')
                    {
                        if(isset($this->display_as[$field->name]))
                            $this->read_fields[] = (object)array('field_name' => $field->name, 'display_as' => $this->display_as[$field->name]);
                        else
                            $this->read_fields[] = (object)array('field_name' => $field->name, 'display_as' => $field->display_as);
                    }
                }
            }

            $this->read_fields_checked = true;
        }
        return $this->read_fields;
    }

}