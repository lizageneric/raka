<?php 
	if(isset($_GET['path']))
	{
		$path_info = items::parse_path(filter_var($_GET['path'],FILTER_SANITIZE_STRING));
		$current_path = filter_var($_GET['path'],FILTER_SANITIZE_STRING);
		$current_entity_id = $path_info['entity_id'];
		$current_item_id = true; // set to true to set off default title
		$current_path_array = $path_info['path_array'];
		$app_breadcrumb = items::get_breadcrumb($current_path_array);
	
		$app_breadcrumb[] = array('title'=>$reports['name']);
	
		require(component_path('items/navigation'));
	}
?>

<h3 class="page-title"><?php echo $reports['name'] ?></h3>

<?php 

require(component_path('ext/graphicreport/view'));