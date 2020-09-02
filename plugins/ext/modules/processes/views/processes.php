<h3 class="page-title"><?php echo TEXT_EXT_PROCESSES ?></h3>

<p><?php echo TEXT_EXT_PROCESSES_DESCRIPTION ?></p>

<div class="row">
  <div class="col-md-9">
		<?php 
			echo button_tag(TEXT_EXT_BUTTON_ADD_PROCESS,url_for('ext/processes/form')) . ' ' .
					 button_tag(TEXT_EXT_BUTTONS_GROUPS,url_for('ext/processes/buttons_groups'),false,array('class'=>'btn btn-default')) . ' ' .
					 button_tag('<i class="fa fa-sitemap"></i> ' . TEXT_FLOWCHART,url_for('ext/processes/processes_flowchart'),false,array('class'=>'btn btn-default')) 
		?>
  </div>
  <div class="col-md-3">
    <?php echo form_tag('processes_filter_form',url_for('ext/processes/processes','action=set_processes_filter')) ?>
      <?php echo select_tag('processes_filter',entities::get_choices_with_empty(),$processes_filter,array('class'=>'form-control  chosen-select','onChange'=>'this.form.submit()')) ?>
    </form>
  </div>
</div>  

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
<thead>
  <tr>
    <th><?php echo TEXT_ACTION ?></th>    
    <th><?php echo TEXT_ID ?></th>
    <th><?php echo TEXT_REPORT_ENTITY ?></th>        
    <th width="100%"><?php echo TEXT_NAME ?></th>
    <th><?php echo TEXT_EXT_PROCESS_BUTTON_TITLE ?></th>                    
    <th><?php echo TEXT_COMMENT ?></th>    
    <th><?php echo TEXT_IS_ACTIVE ?></th>
    <th><?php echo TEXT_SORT_ORDER ?></th>            
  </tr>
</thead>
<tbody>
<?php if(db_count('app_ext_processes')==0) echo '<tr><td colspan="8">' . TEXT_NO_RECORDS_FOUND. '</td></tr>'; ?>
<?php  
	$where_sql = '';
	
	if($processes_filter>0)
	{
		$where_sql .= " and p.entities_id='" . db_input($processes_filter) . "'";
	}
	
  $processes_query = db_query("select p.*, e.name as entities_name from app_ext_processes p, app_entities e where e.id=p.entities_id {$where_sql} order by p.sort_order, e.name, p.name");
  while($v = db_fetch_array($processes_query)):
?>
  <tr>
    <td style="white-space: nowrap;"><?php echo button_icon_delete(url_for('ext/processes/delete','id=' . filter_var($v['id'],FILTER_SANITIZE_STRING))) . ' ' . button_icon_edit(url_for('ext/processes/form','id=' . filter_var($v['id'],FILTER_SANITIZE_STRING))) . ' ' . button_icon(TEXT_COPY,'fa fa-files-o',url_for('ext/processes/copy','id=' . filter_var($v['id'],FILTER_SANITIZE_STRING))) . ' ' . button_icon(TEXT_BUTTON_CONFIGURE_FILTERS,'fa fa-cogs',url_for('ext/processes/filters','process_id=' . filter_var($v['id'],FILTER_SANITIZE_STRING)),false); ?></td>        
    <td><?php echo htmlentities($v['id']) ?></td>
    <td><?php echo htmlentities($v['entities_name']) ?></td>    
    <td><?php 
    	echo link_to(filter_var($v['name'],FILTER_SANITIZE_STRING),url_for('ext/processes/actions','process_id=' . filter_var($v['id'],FILTER_SANITIZE_STRING))) . ' ' . tooltip_icon(filter_var($v['notes'],FILTER_SANITIZE_STRING)); 
    	
    	$count_query = db_query("select count(*) as total from app_ext_processes_actions pa where pa.process_id='" . filter_var($v['id'],FILTER_SANITIZE_STRING) . "'");
    	$count = db_fetch_array($count_query);
    	echo tooltip_text(TEXT_EXT_COUNT_ACTIONS . ': ' . htmlentities(filter_var($count['total'],FILTER_SANITIZE_STRING)));
    	
    	?></td>
    <td><?php echo htmlentities($v['button_title']) ?></td>      	
  	<td><?php echo render_bool_value($v['allow_comments']) ?></td>  	
  	<td><?php echo render_bool_value($v['is_active']) ?></td>
  	<td><?php echo htmlentities($v['sort_order']) ?></td>
        
  </tr>
<?php endwhile?>  
</tbody>
</table>
</div>