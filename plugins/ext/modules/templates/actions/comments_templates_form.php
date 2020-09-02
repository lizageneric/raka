<?php

$obj = array();

if(isset($_GET['id']))
{
  $obj = db_find('app_ext_comments_templates',filter_var($_GET['id'],FILTER_SANITIZE_STRING));  
}
else
{
  $obj = db_show_columns('app_ext_comments_templates');
  
  if($comments_templates_filter>0)
  {
    $obj['entities_id'] = $comments_templates_filter;
  }
  
  $obj['is_active'] = 1;
}