<?php

$obj = array();

if(isset($_GET['id']))
{
  $obj = db_find('app_ext_track_changes',filter_var($_GET['id'],FILTER_SANITIZE_STRING));  
}
else
{
  $obj = db_show_columns('app_ext_track_changes');
  
  $obj['menu_icon'] = 'fa-file-text-o';
  $obj['keep_history'] = 30;
  $obj['rows_per_page'] = 20;
  $obj['color_insert'] = '#5cb85c';
  $obj['color_comment'] = '#5bc0de';
  $obj['color_update'] = '#f0ad4e';
}