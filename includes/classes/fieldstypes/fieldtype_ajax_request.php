<?php

class fieldtype_ajax_request
{
    public $options;
    
    function __construct()
    {
        $this->options = array('title' => TEXT_FIELDTYPE_AJAX_REQUEST_TITLE);
    }
    
    function get_configuration()
    {
        $cfg = array();
               
        $cfg[TEXT_PHP_CODE][] = array('title'=>'', 'name'=>'php_code','type'=>'code','params'=>array('class'=>'form-control'));
        
        $cfg[TEXT_SETTINGS][] = array('title'=>TEXT_DEBUG_MODE, 'name'=>'debug_mode','type'=>'checkbox');
        
        $cfg[TEXT_SETTINGS][] = array('title'=>TEXT_DEFAULT_TEXT,
            'name'=>'default_text',
            'type'=>'textarea',            
            'params'=>array('class'=>'form-control'));
        
        return $cfg;
    }
    
    function render($field,$obj,$params = array())
    {
        global $app_fields_cache, $app_session_token, $app_items_form_name;
        
        $cfg =  new fields_types_cfg($field['configuration']);
        
        $html = '<div id="ajax_request_field_' . $field['id'] . '" class="form-control-static"></div>';
                        
        $ajax_request = '
            $("#ajax_request_field_' . $field['id'] . '").html("<div class=\"ajax-loading-small\"></div>");
            $("#ajax_request_field_' . $field['id'] . '").load("' .url_for("dashboard/ajax_request","field_id=" . $field['id'] . "&item_id=" . (int)$obj['id']) . '",$("#' . $app_items_form_name . '").serializeArray(),function(response, status, xhr) {
              if(response.length==0) $(this).html("' . addslashes($cfg->get('default_text')). '")    
            });
            ';
        
        $check_fields_types = [
            'fieldtype_input',
            'fieldtype_input_numeric',
            'fieldtype_input_date',
            'fieldtype_input_datetime',
            'fieldtype_input_masked',
            'fieldtype_dropdown',
            'fieldtype_checkboxes',
            'fieldtype_radioboxes',
            'fieldtype_entity',
            'fieldtype_entity_ajax',
            'fieldtype_entity_multilevel',                        
            'fieldtype_users',
            'fieldtype_users_ajax',
            'fieldtype_stages',
            'fieldtype_users_approve',
            'fieldtype_boolean_checkbox',
            'fieldtype_boolean',
        ];
        
        $html .= '<script> ' . $ajax_request;
        foreach($app_fields_cache[$field['entities_id']] as $fields)
        {            
            if(in_array($fields['type'],$check_fields_types) and strstr($cfg->get('php_code'),'[' . $fields['id'] . ']'))
            {                
                switch($fields['type'])
                {
                    case 'fieldtype_input':
                    case 'fieldtype_input_numeric':
                    case 'fieldtype_input_masked':
                        $html .= '
                            $("#fields_' . $fields['id'] . '").keyup(function(){ ' . $ajax_request . '});';
                        break;
                    case 'fieldtype_checkboxes':
                    case 'fieldtype_radioboxes':
                    case 'fieldtype_boolean_checkbox':
                    case 'fieldtype_boolean':
                        $html .= '
                            $(".field_' . $fields['id'] . '").change(function(){ ' . $ajax_request . '});';
                        break;
                    case 'fieldtype_dropdown':
                    case 'fieldtype_entity':
                    case 'fieldtype_entity_ajax':
                    case 'fieldtype_entity_multilevel':
                    case 'fieldtype_users':
                    case 'fieldtype_users_ajax':
                    case 'fieldtype_stages':
                    case 'fieldtype_users_approve':
                        $fields_cfg =  new fields_types_cfg($fields['configuration']);
                        
                        if($fields_cfg->get('display_as')=='checkboxes')
                        {
                            $html .= '
                                $(".field_' . $fields['id'] . '").change(function(){ ' . $ajax_request . '});';
                        }
                        else
                        {
                            $html .= '
                                $("#fields_' . $fields['id'] . '").change(function(){ ' . $ajax_request . '});';
                        }
                        break;
                    case 'fieldtype_input_date':
                    case 'fieldtype_input_datetime':                    
                        $html .= '
                                $("#fields_' . $fields['id'] . '").change(function(){ ' . $ajax_request . '});';
                        break;
                }
            }
        }
        
        $html .= '</script>';
        
        return $html;
    }
    
    function process($options)
    {
        return db_prepare_input($options['value']);
    }
    
    function output($options)
    {
        return $options['value'];
    }
}