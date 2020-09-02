<h3 class="page-title"><?php echo TEXT_EXT_FUNNELCHART ?></h3>


<?php echo button_tag(TEXT_BUTTON_CREATE,url_for('ext/funnelchart/form')) ?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
<thead>
  <tr>
    <th><?php echo TEXT_ACTION ?></th>        
    <th><?php echo TEXT_REPORT_ENTITY ?></th>
    <th width="100%"><?php echo TEXT_NAME ?></th> 
    <th><?php echo TEXT_TYPE ?></th>   
    <th><?php echo TEXT_EXT_GROUP_BY_FIELD ?></th>    
    <th><?php echo TEXT_EXT_SUM_BY_FIELD ?></th>
    <th><?php echo TEXT_USERS_GROUPS ?></th>    
  </tr>
</thead>
<tbody>
<?php

$choices_type = ['funnel'=>TEXT_EXT_FUNNEL_CHART,'bars'=>TEXT_EXT_BARS_CAHRT,'table'=>TEXT_EXT_TABLE];

$fields_cahce = fields::get_name_cache();

$reports_query = db_query("select * from app_ext_funnelchart order by name");

if(db_num_rows($reports_query)==0) echo '<tr><td colspan="9">' . TEXT_NO_RECORDS_FOUND. '</td></tr>'; 

while($reports = db_fetch_array($reports_query)):
?>
<tr>
  <td style="white-space: nowrap;"><?php echo button_icon_delete(url_for('ext/funnelchart/delete','id=' . filter_var($reports['id'],FILTER_SANITIZE_STRING))) . ' ' . button_icon_edit(url_for('ext/funnelchart/form','id=' . filter_var($reports['id'],FILTER_SANITIZE_STRING))) ?></td>
  <td><?php echo $app_entities_cache[filter_var($reports['entities_id'],FILTER_SANITIZE_STRING)]['name'] ?></td>
  <td><?php echo link_to(filter_var($reports['name'],FILTER_SANITIZE_STRING),url_for('ext/funnelchart/view','id=' . htmlentities(filter_var($reports['id'],FILTER_SANITIZE_STRING))),array('target'=>'_blank')) ?></td>
  <td><?php echo $choices_type[filter_var($reports['type'],FILTER_SANITIZE_STRING)] ?></td>  
  <td><?php echo htmlentities($fields_cahce[filter_var($reports['group_by_field'],FILTER_SANITIZE_STRING)]) ?></td>
  <td><?php 
 		if(strlen(filter_var($reports['sum_by_field'])))
 		{
 			foreach(filter_var_array(explode(',',$reports['sum_by_field'])) as $field_id)
 			{
 				echo htmlentities($fields_cahce[$field_id]) . '<br>'; 
 			}	
 		}	
  ?></td>  
  <td>
      <?php
      if(strlen(filter_var($reports['users_groups'])))
      {	
        $users_groups_list = array();
        foreach(filter_var_array(explode(',',$reports['users_groups'])) as $users_groups_id)
        {
          $users_groups_list[] = access_groups::get_name_by_id($users_groups_id);
        }
        
        echo implode('<br>',$users_groups_list);
      }
      ?>
    </td>  
</tr>  
<?php endwhile ?>
</tbody>
</table>
</div>