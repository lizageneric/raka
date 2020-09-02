<h3 class="page-title"><?php echo TEXT_EXT_EXPORT_TEMPLATES ?></h3>

<p><?php echo TEXT_EXT_EXPORT_TEMPLATES_INFO ?></p>

<?php
  $where_sql = '';
  
  if($export_templates_filter>0)
  {
    $where_sql .= " and ep.entities_id='" . db_input($export_templates_filter) . "'";
  }
  
  $templates_query = db_query("select ep.*, e.name as entities_name from app_ext_export_templates ep, app_entities e where e.id=ep.entities_id " . $where_sql . " order by e.id, ep.sort_order, ep.name");
?>  


<div class="row">
  <div class="col-md-9">
    <?php echo button_tag(TEXT_BUTTON_CREATE,url_for('ext/templates/export_templates_form'),true) ?>
    <?php if(db_num_rows($templates_query)>1 and $export_templates_filter>0) echo button_tag(TEXT_SORT_ORDER,url_for('ext/templates/export_templates_sort'),true,array('class'=>'btn btn-default'))?>
  </div>
  <div class="col-md-3">
    <?php echo form_tag('templates_filter',url_for('ext/templates/export_templates','action=set_export_templates_filter')) ?>
      <?php echo select_tag('export_templates_filter',entities::get_choices_with_empty(),$export_templates_filter,array('class'=>'form-control input-large float-right','onChange'=>'this.form.submit()')) ?>
    </form>
  </div>
</div>  

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
<thead>
  <tr>
    <th><?php echo TEXT_ACTION ?></th>        
    <th><?php echo TEXT_REPORT_ENTITY ?></th>
    <th><?php echo TEXT_TYPE ?></th>
    <th width="100%"><?php echo TEXT_NAME ?></th>    
    <th><?php echo TEXT_ACCESS ?></th>    
    <th><?php echo TEXT_IS_ACTIVE ?></th>
    <th><?php echo TEXT_SORT_ORDER ?></th>    
  </tr>
</thead>
<tbody>
<?php

if(db_num_rows($templates_query)==0) echo '<tr><td colspan="6">' . TEXT_NO_RECORDS_FOUND. '</td></tr>'; 

$access_groups_cache = access_groups::get_cache();

while($templates = db_fetch_array($templates_query)):
?>
<tr>
  <td style="white-space: nowrap;"><?php 
  	echo button_icon_delete(url_for('ext/templates/export_templates_delete_confirm','id=' . filter_var($templates['id'],FILTER_SANITIZE_STRING))) . ' ' . 
  	button_icon_edit(url_for('ext/templates/export_templates_form','id=' . filter_var($templates['id'],FILTER_SANITIZE_STRING))) . ' ' . 
  	button_icon(TEXT_BUTTON_CONFIGURE_FILTERS,'fa fa-cogs',url_for('ext/templates/export_templates_filters','templates_id=' . filter_var($templates['id'],FILTER_SANITIZE_STRING)),false) . ' '. 
  	button_icon(TEXT_COPY,'fa fa-files-o',url_for('ext/templates/export_templates','action=copy&templates_id=' . filter_var($templates['id'],FILTER_SANITIZE_STRING)),false,['onClick'=>'return confirm("' . addslashes(TEXT_COPY_RECORD). '?")'])
  	?></td>
  <td><?php echo htmlentities($templates['entities_name']) ?></td>
  <td><?php echo htmlentities($templates['type']) ?></td>
  <td><?php 
  
  if($templates['type']=='html')
  {
      echo link_to(filter_var($templates['name'],FILTER_SANITIZE_STRING),url_for('ext/templates/export_templates_configure','id=' . filter_var($templates['id'],FILTER_SANITIZE_STRING)));
  }
  else
  {
      $html = link_to(filter_var($templates['name'],FILTER_SANITIZE_STRING),url_for('ext/templates_docx/blocks','templates_id=' . filter_var($templates['id'],FILTER_SANITIZE_STRING)));
      $html .= '<br><small>' . TEXT_FILENAME . ':';
      
      if(is_file(DIR_WS_TEMPLATES . filter_var($templates['filename'],FILTER_SANITIZE_STRING)))
      {
          $html .=  ' <a href="' . DIR_WS_TEMPLATES . filte_var($templates['filename'],FILTER_SANITIZE_STRING) . '">' . filter_var($templates['filename'],FILTER_SANITIZE_STRING) . '</a>';
      }
      else
      {
          $html .=  ' <span class="color-danger">' . TEXT_FILE_NOT_FOUND . '</span>';
      }
      
      $html .= '</small>';
      
      echo $html;
  }
          
  
  ?></td>  
  <td>
<?php
  if(strlen($templates['users_groups'])>0)
  {
    $users_groups = array();
    foreach(explode(',',filter_var($templates['users_groups'],FILTER_SANITIZE_STRING)) as $id)
    {
      $users_groups[] = $access_groups_cache[$id];
    }
    
    if(count($users_groups)>0)
    {        
      echo '<span style="display:block" data-html="true" data-toggle="tooltip" data-placement="left" title="' . addslashes(implode(', ',$users_groups)). '">' . TEXT_USERS_GROUPS . ' (' . count($users_groups) . ')</span>';
    }
  }
  
  if($templates['assigned_to']>0)
  {
    $assigned_to = array();
    foreach(explode(',',filter_var($templates['assigned_to'],FILTER_SANITIZE_STRING)) as $id)
    {
      $assigned_to[] = $app_users_cache[$id]['name'];
    }
    
    if(count($assigned_to)>0)
    {        
      echo '<span data-html="true" data-toggle="tooltip" data-placement="left" title="' . addslashes(implode(', ',$assigned_to)). '">' . TEXT_USERS_LIST . ' (' . count($assigned_to) . ')</span>';
    }
  }   
?>  
  </td>
  <td><?php echo render_bool_value($templates['is_active']) ?></td>
  <td><?php echo htmlentities($templates['sort_order']) ?></td>    
</tr>  
<?php endwhile ?>
</tbody>
</table>
</div>