<?php

switch($app_module_action)
{
  case 'save':
       $timer_cfg_query = db_query("select * from app_ext_timer_configuration where entities_id='" . db_input(filter_var($_POST['entities_id'],FILTER_SANITIZE_STRING)) . "'");
       if($timer_cfg = db_fetch_array($timer_cfg_query))
       {
         $sql_data = array('users_groups' => (is_array($_POST['users_groups']) ? implode(',',$_POST['users_groups']):''));
         
         db_perform('app_ext_timer_configuration',$sql_data,'update',"id='" . db_input($timer_cfg['id']) . "'");
       }
       else
       {
         $sql_data = array('entities_id'  => $_POST['entities_id'],
                           'users_groups' => (is_array($_POST['users_groups']) ? implode(',',$_POST['users_groups']):''));
                           
         db_perform('app_ext_timer_configuration',$sql_data);
       }
       
      exit();
    break;
}