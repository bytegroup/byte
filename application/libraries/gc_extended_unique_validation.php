<?php
/**
 * Created by PhpStorm.
 * User: mizanur
 * Date: 1/29/15
 * Time: 12:57 PM
 *
 * Extension for checking unique validation in group of data(rows).
 * function
 */
?>
<?php
class GC_Extended_unique_validation extends grocery_CRUD
{
    protected $_unique_field_in_group = array();

    private function in_add($field){
        $edit_fields= $this->get_add_fields();
        foreach($edit_fields as $edit_field):
            if(in_array($field, (array)$edit_field))return true;
        endforeach;
        return false;
    }
    private function in_edit($field){
        $edit_fields= $this->get_edit_fields();
        foreach($edit_fields as $edit_field):
            if(in_array($field, (array)$edit_field))return true;
        endforeach;
        return false;
    }

    public function unique_field_in_group()
    {
        $args = func_get_args();

        if(isset($args[0]) && is_array($args[0]))
        {
            $args = $args[0];
        }

        $this->_unique_field_in_group = $args;

        return $this;
    }

    protected function db_insert_validation()
    {
        $validation_result = (object)array('success'=>false);

        $field_types = $this->get_field_types();
        $unique_fields = $this->_unique_field_in_group;
        $add_fields = $this->get_add_fields();

        if(!empty($unique_fields))
        {
            $form_validation = $this->form_validation();

            foreach($unique_fields as $field_group)
            {
                list($field_name, $group_name)= explode('/', $field_group);

                if($this->in_add($field_name) && $this->in_add($group_name))
                {
                    $form_validation->set_rules( $field_name,
                        $field_types[$field_name]->display_as,
                        'is_unique_in_group['.$this->basic_db_table.','.$field_name.','.$group_name.']');
                }
            }

            if(!$form_validation->run())
            {
                $validation_result->error_message = $form_validation->error_string();
                $validation_result->error_fields = $form_validation->_error_array;

                return $validation_result;
            }
        }
        return parent::db_insert_validation();
    }

    protected function db_update_validation()
    {
        $validation_result = (object)array('success'=>false);

        $field_types = $this->get_field_types();
        $unique_fields_in_group = $this->_unique_field_in_group;
        $edit_fields = $this->get_edit_fields();

        if(!empty($unique_fields_in_group))
        {
            $form_validation = $this->form_validation();

            $form_validation_check = false;

            foreach($unique_fields_in_group as $field_group)
            {
                list($field_name, $group_name)= explode('/', $field_group);

                if( $this->in_edit($field_name) && $this->in_edit($group_name))
                {
                    //return $validation_result;
                    $state_info = $this->getStateInfo();
                    $primary_key = $this->get_primary_key();
                    $field_name_value = $_POST[$field_name];

                    $ci = &get_instance();
                    $previous_field_name_value =
                        $ci->db->where($primary_key,$state_info->primary_key)
                            ->get($this->basic_db_table)->row()->$field_name;

                    if(!empty($previous_field_name_value) && $previous_field_name_value != $field_name_value) {
                        $form_validation->set_rules( $field_name,
                            $field_types[$field_name]->display_as,
                            'is_unique_in_group['.$this->basic_db_table.','.$field_name.','.$group_name.']');

                        $form_validation_check = true;
                    }
                }
            }

            if($form_validation_check && !$form_validation->run())
            {
                $validation_result->error_message = $form_validation->error_string();
                $validation_result->error_fields = $form_validation->_error_array;

                return $validation_result;
            }
        }
        return parent::db_update_validation();
    }

}