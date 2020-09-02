<?php
switch($app_module_action)
{
    case 'add_row':
        
        if($_GET['block_type']=='thead')
        {
            $sort_order_query = db_query("select (min(sort_order)-1) as sort_order from app_ext_items_export_templates_blocks where block_type='" . filter_var($_GET['block_type'],FILTER_SANITIZE_STRING) . "' and templates_id=" . filter_var($template_info['id'],FILTER_SANITIZE_STRING) . " and parent_id=" . filter_var($parent_block['id'],FILTER_SANITIZE_STRING));
        }
        else
        {
            $sort_order_query = db_query("select (max(sort_order)+1) as sort_order from app_ext_items_export_templates_blocks where block_type='" . filter_var($_GET['block_type'],FILTER_SANITIZE_STRING) . "' and templates_id=" . filter_var($template_info['id'],FILTER_SANITIZE_STRING) . " and parent_id=" . filter_var($parent_block['id'],FILTER_SANITIZE_STRING));            
        }
        
        $sort_order = db_fetch_array($sort_order_query);
                        
        $sql_data = [
        'templates_id' => filter_var($template_info['id'],FILTER_SANITIZE_STRING),
        'block_type' => filter_var($_GET['block_type'],FILTER_SANITIZE_STRING),
        'parent_id' => filter_var(_GET('parent_block_id'),FILTER_SANITIZE_STRING),
        'fields_id' => 0,
        'settings' => '',
        'sort_order' => filter_var($sort_order['sort_order'],FILTER_SANITIZE_STRING),
        ];
        
        //print_rr($_POST);
        //EXIT();
        
        if(isset($_GET['id']))
        {
            db_perform('app_ext_items_export_templates_blocks',$sql_data,'update',"id='" . db_input(filter_var($_GET['id'],FILTER_SANITIZE_STRING)) . "'");
        }
        else
        {
            db_perform('app_ext_items_export_templates_blocks',$sql_data);
        }
        
        redirect_to('ext/templates_docx/table_blocks','templates_id=' . $template_info['id'] . '&parent_block_id=' . $parent_block['id']);
        break;
    case 'delete_row':
        if(isset($_GET['id']))
        {
            export_templates_blocks::delele_block(_GET('id'));
            
            redirect_to('ext/templates_docx/table_blocks','templates_id=' . $template_info['id'] . '&parent_block_id=' . $parent_block['id']);
        }
        break;
    case 'save':
        $sql_data = [
        'templates_id' => filter_var($template_info['id'],FILTER_SANITIZE_STRING),
        'block_type' => filter_var($row_info['block_type'],FILTER_SANITIZE_STRING) . '_cell',
        'parent_id' => filter_var($row_info['id'],FILTER_SANITIZE_STRING),
        'fields_id' => (isset($_POST['fields_id']) ? filter_var($_POST['fields_id'],FILTER_SANITIZE_STRING) : 0),
        'settings' => (isset($_POST['settings']) ? json_encode(filter_var($_POST['settings'],FILTER_SANITIZE_STRING)) : ''),
        'sort_order' => filter_var($_POST['sort_order'],FILTER_SANITIZE_STRING),
        ];
        
        if(isset($_GET['id']))
        {
            db_perform('app_ext_items_export_templates_blocks',$sql_data,'update',"id='" . db_input(filter_var($_GET['id'],FILTER_SANITIZE_STRING)) . "'");
        }
        else
        {
            db_perform('app_ext_items_export_templates_blocks',$sql_data);
        }
        
        redirect_to('ext/templates_docx/extra_rows','templates_id=' . $template_info['id'] . '&parent_block_id=' . $parent_block['id'] . '&row_id=' . $row_info['id']);
        break;
    case 'delete':
        if(isset($_GET['id']))
        {
            export_templates_blocks::delele_block(_GET('id'));
            
            redirect_to('ext/templates_docx/extra_rows','templates_id=' . $template_info['id'] . '&parent_block_id=' . $parent_block['id'] . '&row_id=' . $row_info['id']);
        }
        break;
        
    case 'get_field_settings':
        $field_query = db_query("select type from app_fields where id=" . _POST('fields_id'));
        if(!$field = db_fetch_array($field_query))
        {
            exit();
        }
        
        if(filter_var($_GET['id'],FILTER_SANITIZE_STRING)>0)
        {
            $obj = db_find('app_ext_items_export_templates_blocks',filter_var(_GET('id'),FILTER_SANITIZE_STRING));
            $settings = new settings(filter_var($obj['settings'],FILTER_SANITIZE_STRING));
        }
        else
        {
            $settings = new settings('');
        }
        
        $html = '';
        
        switch($field['type'])
        {
            case 'fieldtype_input_numeric':
            case 'fieldtype_input_numeric_comments':
            case 'fieldtype_formula':
            case 'fieldtype_js_formula':
            case 'fieldtype_mysql_query':
            case 'fieldtype_ajax_request':
                $html = '
                  <div class="form-group settings-list">
                    <label class="col-md-3 control-label" for="fields_id">' . tooltip_icon(TEXT_NUMBER_FORMAT_INFO) . TEXT_NUMBER_FORMAT . '</label>
                    <div class="col-md-9">' .  input_tag('settings[number_format]',$settings->get('number_format',CFG_APP_NUMBER_FORMAT),['class'=>'form-control input-small input-masked','data-mask'=>'9/~/~']) . '</div>
                  </div>
                  <div class="form-group settings-list">
                    <label class="col-md-3 control-label" for="fields_id">' . TEXT_PREFIX . '</label>
                    <div class="col-md-9">' .  input_tag('settings[content_value_prefix]',$settings->get('content_value_prefix',''),['class'=>'form-control input-medium']) . '</div>
                  </div>
                        
                  <div class="form-group settings-list">
                    <label class="col-md-3 control-label" for="fields_id">' .  TEXT_SUFFIX . '</label>
                    <div class="col-md-9">' .  input_tag('settings[content_value_suffix]',$settings->get('content_value_suffix',''),['class'=>'form-control input-medium']) . '</div>
                  </div>
                  ';
                        
                break;
        }
        
        echo $html;
        
        exit();
        
        break;
}