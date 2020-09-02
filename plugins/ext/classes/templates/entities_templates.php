<?php

class entities_templates
{
  static function get_field_choices($entity_id)
  {
    $available_types = array('fieldtype_checkboxes',
                             'fieldtype_radioboxes',
                             'fieldtype_boolean',                                                           
                             'fieldtype_dropdown',
                             'fieldtype_dropdown_multiple',                                              
                             'fieldtype_input_date',
                             'fieldtype_input_datetime',
                             'fieldtype_input_numeric',
                             'fieldtype_input',
                             'fieldtype_input_url',
                             'fieldtype_textarea',
                             'fieldtype_textarea_wysiwyg',
                             'fieldtype_input_masked',
                             'fieldtype_entity',                                                                                       
                             'fieldtype_users',
                             'fieldtype_grouped_users',
    												 'fieldtype_todo_list',
                             );
    $choices = array();        
    $fields_query = db_query("select f.*, t.name as tab_name from app_fields f, app_forms_tabs t where f.type in (\"" . implode('","',$available_types). "\")  and f.entities_id='" . db_input($entity_id) . "' and f.forms_tabs_id=t.id order by t.sort_order, t.name, f.sort_order, f.name");
    while($v = db_fetch_array($fields_query))
    {        
      $choices[$v['id']] = $v['name']; 
    } 
    
    return $choices;  
  }
  
  static function render_modal_header_menu($entities_id)
  {
    $html = '';
    
    if(count($templates_list = self::get_users_templates($entities_id))>0)
    {
      $templates_html = '';
      foreach($templates_list as $v)
      {
        $templates_html .= '
          <li>
						<a href="#" onClick="use_entity_template(' . $v['id'] . ')">' . $v['name'] . '</a>
					</li>
        ';
      }
      
      $html = '      
        <div class="btn-group">
  				<button class="btn dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown"><i class="fa fa-angle-down"></i></button>
  				<ul class="dropdown-menu" role="menu">
  					' . $templates_html . '
  				</ul>
  			</div>                  
      ';
    }
    
    return $html;
  }
  
  static function render_fields_values($entities_id)
  {
    $html = ''; 
    
    if(count($templates_list = self::get_users_templates($entities_id))>0)
    {
      foreach(filter_var_array($templates_list) as $template)
      {
        $html .= '<ul class="entities_template_' . filter_var($template['id'],FILTER_SANITIZE_STRING) . '" style="display:none">';
        
        $templates_fields_query = db_query("select tf.id, tf.fields_id, tf.value, f.name, f.type from app_ext_entities_templates_fields tf, app_fields f left join app_forms_tabs t on f.forms_tabs_id=t.id  where f.id=tf.fields_id and tf.templates_id='" . db_input(filter_var($template['id'],FILTER_SANITIZE_STRING)) ."' order by t.sort_order, t.name, f.sort_order, f.name");
        while($templates_fields = db_fetch_array($templates_fields_query))
        {
          $fields_value = filter_var($templates_fields['value'],FILTER_SANITIZE_STRING);
          
          if(strlen($fields_value)>0)
          {
            switch($templates_fields['type'])
            {
              case 'fieldtype_input_date':                  
                  $fields_value = date('Y-m-d',$fields_value);
                break;
              case 'fieldtype_input_datetime':
                  $fields_value = date('Y-m-d H:i',$fields_value);
                break;
            }
          }
          
          $html .= '
            <li data-fields-id="' . filter_var($templates_fields['fields_id'],FILTER_SANITIZE_STRING) . '" data-fields-type="' . filter_var($templates_fields['type'],FILTER_SANITIZE_STRING)  . '">' . filter_var($fields_value,FILTER_SANITIZE_STRING) . '</li>
          ';
        }
        
        $html .= '</ul>';
      }
    }
    
    return $html;
  }
  
  static function get_users_templates($entities_id)
  {
    global $app_user;
    
    $templates_list = array();
    
    $templates_query = db_query("select ep.* from app_ext_entities_templates ep, app_entities e where ep.is_active=1 and e.id=ep.entities_id and ep.entities_id='" . db_input($entities_id) . "' and (find_in_set(" . $app_user['group_id'] . ",users_groups) or find_in_set(" . $app_user['id'] . ",assigned_to)) order by ep.sort_order, ep.name");
    while($templates = db_fetch_array($templates_query))
    {
      $templates_list[] = array('id'=>filter_var($templates['id'],FILTER_SANITIZE_STRING), 'name'=>filter_var($templates['name'],FILTER_SANITIZE_STRING));
    }
    
    return $templates_list;
  }
}