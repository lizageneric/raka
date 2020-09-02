<ul class="page-breadcrumb breadcrumb">
  <li><?php echo link_to(TEXT_EXT_EXPORT_TEMPLATES,url_for('ext/templates/export_templates'))?><i class="fa fa-angle-right"></i></li>
  <li><?php echo $template_info['entities_name'] ?><i class="fa fa-angle-right"></i></li>
  <li><?php echo $template_info['name'] ?><i class="fa fa-angle-right"></i></li>
  <li><?php echo TEXT_EXT_INFO_BLOCKS ?></li>
</ul>

<p><?php echo TEXT_EXT_EXPORT_TEMPLATES_BLOCK_TIP ?></p>

<?php echo button_tag(TEXT_BUTTON_ADD,url_for('ext/templates_docx/blocks_form','templates_id=' . $template_info['id'])) ?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
<thead>
  <tr>
    <th><?php echo TEXT_ACTION ?></th>
    <th><?php echo TEXT_INSERT ?></th>        
    <th><?php echo TEXT_ENTITY ?></th>
    <th width="100%"><?php echo TEXT_FIELD ?></th>
    <th><?php echo TEXT_SORT_ORDER ?></th>                    
  </tr>
</thead>
<tbody>

<?php 
$blocks_query = db_query("select b.*, f.name, f.entities_id, f.type as field_type,f.configuration as field_configuration from app_ext_items_export_templates_blocks b, app_fields f, app_entities e where b.parent_id=0 and block_type='parent' and b.fields_id=f.id and b.templates_id = " . $template_info['id'] . " and f.entities_id=e.id order by b.sort_order, b.id");

if(db_num_rows($blocks_query)==0) echo '<tr><td colspan="6">' . TEXT_NO_RECORDS_FOUND. '</td></tr>';

while($blocks = db_fetch_array($blocks_query))
{    
    $block_settings = new settings(filter_var($blocks['settings'],FILTER_SANITIZE_STRING));
?>
<tr>
  <td style="white-space: nowrap;"><?php echo button_icon_delete(url_for('ext/templates_docx/blocks_delete_confirm','id=' . filter_var($blocks['id'],FILTER_SANITIZE_STRING) . '&templates_id=' . $template_info['id'])) . ' ' . button_icon_edit(url_for('ext/templates_docx/blocks_form','id=' . filter_var($blocks['id'],FILTER_SANITIZE_STRING) . '&templates_id=' . $template_info['id'])) ?></td>
  <td><?php echo '<input value="${' . filter_var($blocks['id']) . '}" readonly="readonly" class="form-control input-small select-all">' ?></td>
  <td><?php echo $app_entities_cache[filter_var($blocks['entities_id'],FILTER_SANITIZE_STRING)]['name'] ?></td>
  <td><?php 
      $cfg = new fields_types_cfg(filter_var($blocks['field_configuration'],FILTER_SANITIZE_STRING));
      
      $field_name = fields_types::get_option(filter_var($blocks['field_type'],FILTER_SANITIZE_STRING), 'name',filter_var($blocks['name'],FILTER_SANITIZE_STRING));
      
      //check if subentity
      if(filter_var($blocks['field_type'])=='fieldtype_id' and $app_entities_cache[filter_var($blocks['entities_id'],FILTER_SANITIZE_STRING)]['parent_id']==$template_info['entities_id'])
      {
          filter_var($blocks['field_type'],FILTER_SANITIZE_STRING) = 'fieldtype_entity';
          $field_name = TEXT_LIST_RELATED_ITEMS;
      }
      
      switch(filter_var($blocks['field_type'],FILTER_SANITIZE_STRING))
      {
          case 'fieldtype_created_by':
          case 'fieldtype_entity':
          case 'fieldtype_entity_ajax':
          case 'fieldtype_related_records':
          case 'fieldtype_entity_multilevel':
          case 'fieldtype_users':
          case 'fieldtype_users_ajax':
              if(in_array($cfg->get('display_as'),['dropdown']) or filter_var($blocks['field_type'],FILTER_SANITIZE_STRING)=='fieldtype_created_by')
              {
                  $field_name = '<a href="' . url_for('ext/templates_docx/entity_blocks','templates_id=' . $template_info['id'] . '&parent_block_id=' . filter_var($blocks['id'],FILTER_SANITIZE_STRING)) . '"><i class="fa fa-list"></i> ' . $field_name . '</a>';
              }
              elseif($block_settings->get('display_us')=='table')
              {
                  $field_name = '<a href="' . url_for('ext/templates_docx/table_blocks','templates_id=' . $template_info['id'] . '&parent_block_id=' . filter_var($blocks['id'],FILTER_SANITIZE_STRING)) . '"><i class="fa fa-list"></i> ' . $field_name . '</a>';
                  
                  $field_name .= '
                        <table>
                            <tr>
                                <td>' . TEXT_EXT_ROWS_NUMBER. ': </td>
                                <td><input value="${' . filter_var($blocks['id'],FILTER_SANITIZE_STRING) . ':count}" readonly="readonly" class="form-control  select-all" style="width: 150px;"></td>
                                <td><input value="${' . filter_var($blocks['id']) . ':count_text}" readonly="readonly" class="form-control select-all" style="width: 150px;"></td>
                            </tr>
                        </table>
                    ';
              }
              elseif($block_settings->get('display_us')=='table_list')
              {
                  $field_name = '<a href="' . url_for('ext/templates_docx/table_list_blocks','templates_id=' . $template_info['id'] . '&parent_block_id=' . filter_var($blocks['id'],FILTER_SANITIZE_STRING)) . '"><i class="fa fa-list"></i> ' . $field_name . '</a>';
                  
                  $field_name .= '
                        <table>
                            <tr>
                                <td>' . TEXT_EXT_ROWS_NUMBER. ': </td>
                                <td><input value="${' . filter_var($blocks['id'],FILTER_SANITIZE_STRING) . ':count}" readonly="readonly" class="form-control  select-all" style="width: 150px;"></td>
                                <td><input value="${' . filter_var($blocks['id'],FILTER_SANITIZE_STRING) . ':count_text}" readonly="readonly" class="form-control select-all" style="width: 150px;"></td>
                            </tr>
                        </table>
                    ';
              }
              
              break;
      }
      
      if(strlen($block_settings->get('number_in_words')))
      {
          $field_name .= '
                        <table>
                            <tr>
                                <td>' . TEXT_EXT_NUMBER_IN_WORDS. ': </td>
                                <td><input value="${' . filter_var($blocks['id'],FILTER_SANITIZE_STRING) . ':' . $block_settings->get('number_in_words') . '}" readonly="readonly" class="form-control input-small select-all"></td>                                
                            </tr>
                        </table>
                    ';
      }
            
      echo $field_name ;
      ?>
  </td>
  <td><?php echo htmlentities($blocks['sort_order']) ?></td>
</tr>  

<?php    
}
?>

</tbody>
</table>
</div>

<?php echo '<a href="' . url_for('ext/templates/export_templates') . '" class="btn btn-default"><i class="fa fa-angle-left" aria-hidden="true"></i> ' . TEXT_BUTTON_BACK . '</a>';?>