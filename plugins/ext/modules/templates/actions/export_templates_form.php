<?php

$obj = array();

if(isset($_GET['id']))
{
  $obj = db_find('app_ext_export_templates',filter_var($_GET['id'],FILTER_SANITIZE_STRING));  
}
else
{
  $obj = db_show_columns('app_ext_export_templates');
  
  if($export_templates_filter>0)
  {
    $obj['entities_id'] = $export_templates_filter;
  }
  
  $obj['is_active'] = 1;
  $obj['split_into_pages'] = 1;
}