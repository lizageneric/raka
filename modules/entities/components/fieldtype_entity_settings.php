


		<fieldset>
        <legend><?php echo TEXT_NAV_LISTING_FILTERS_CONFIG ?></legend>
        <p><?php echo TEXT_LISTING_FILTERS_CFG_INFO ?></p>
        
        <?php echo button_tag(TEXT_CONFIGURE_FILTERS,url_for('entities/entityfield_filters','fields_id=' . filter_var($fields_info['id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING)),false) ?>
        <br><br>
		</fieldset>

<div class="row">
  <div class="col-md-6">
  
    <fieldset>
        <legend><?php echo TEXT_FIELDS_IN_POPUP ?></legend>
        <p><?php echo TEXT_FIELDS_IN_POPUP_RELATED_ITEMS ?></p>
        
<div class="checkbox-list">        
<?php
  $fields_query = db_query("select f.*, t.name as tab_name from app_fields f, app_forms_tabs t where (is_heading = 0 or is_heading is null) and f.type not in ('fieldtype_action','fieldtype_parent_item_id') and  f.entities_id='" . db_input(filter_var($cfg['entity_id'],FILTER_SANITIZE_STRING)) . "' and f.forms_tabs_id=t.id order by t.sort_order, t.name, f.sort_order, f.name");
  while($v = db_fetch_array($fields_query))
  {
    echo '<label>'  . input_checkbox_tag('fields_in_popup[]',filter_var($v['id'],FILTER_SANITIZE_STRING), array('checked'=>in_array(filter_var($v['id'],FILTER_SANITIZE_STRING),explode(',',$cfg['fields_in_popup'])))). ' '. fields_types::get_option(filter_var($v['type'],FILTER_SANITIZE_STRING),'name',filter_var($v['name'],FILTER_SANITIZE_STRING)) . '</label>';
  }
?>
</div>
        
    </fieldset>
  
  </div>
</div>