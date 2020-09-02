<?php echo ajax_modal_template_header(TEXT_INFO) ?>

<?php echo form_tag('templates_form', url_for('ext/templates_docx/table_list_blocks','templates_id=' . filter_var(_GET('templates_id'),FILTER_SANITIZE_STRING) . '&action=save' . (isset($_GET['id']) ? '&id=' . filter_var($_GET['id'],FILTER_SANITIZE_STRING):'')  . '&parent_block_id=' . $parent_block['id']),array('class'=>'form-horizontal')) ?>

<div class="modal-body">
  <div class="form-body">
  
<?php

$cfg = new fields_types_cfg($parent_block['field_configuration']);

//print_rr($parent_block);

$settings = new settings($obj['settings']);

?>  

<ul class="nav nav-tabs">
  <li class="active"><a href="#tab_value" data-toggle="tab"><?php echo TEXT_VALUE ?></a></li>   
  <li><a href="#tab_heading"  data-toggle="tab"><?php echo TEXT_HEADING ?></a></li>      
</ul>

<div class="tab-content">
  <div class="tab-pane fade active in" id="tab_value"> 
             
      <div class="form-group">
      	<label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_SORT_ORDER ?></label>
        <div class="col-md-9"><?php echo input_tag('sort_order',$obj['sort_order'],['class'=>'form-control input-xsmall']) ?></div>			
      </div>
      
<?php 

switch($parent_block['field_type'])
{
    case 'fieldtype_entity':
    case 'fieldtype_entity_ajax':
    case 'fieldtype_entity_multilevel':
    case 'fieldtype_related_records':
        $field_entity_id = $cfg->get('entity_id');
        break;
    default:
        $field_entity_id = 1;
        break;
}

if($parent_block['field_type']=='fieldtype_id' and $app_entities_cache[$parent_block['entities_id']]['parent_id']==$template_info['entities_id'])
{
    $field_entity_id = $parent_block['entities_id'];
}

$choices = [];
$fields_query = fields::get_query($field_entity_id,"and f.type not in ('fieldtype_action')");
while($v = db_fetch_array($fields_query))
{
    $choices[filter_var($v['id'],FILTER_SANITIZE_STRING)] = strip_tags(fields_types::get_option(filter_var($v['type'],FILTER_SANITIZE_STRING),'name',filter_var($v['name'],FILTER_SANITIZE_STRING))) . ' (#' . filter_var($v['id'],FILTER_SANITIZE_STRING) . ')';
}
?>      
      
      <div class="form-group">
      	<label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_FIELDS ?></label>
        <div class="col-md-9"><?php echo select_tag('settings[fields][]',$choices,$settings->get('fields'),array('class'=>'form-control input-xlarge chosen-select chosen-sortable required','multiple'=>'multiple','chosen_order'=>(is_array($settings->get('fields')) ? implode(',',$settings->get('fields')) : '') )) ?>
        </div>			
      </div>
      
      <div class="form-group" id="is-heading-container">
       	<label class="col-md-3 control-label" for="display_field_names"><?php echo TEXT_DISPLAY_FIELD_NAMES ?></label>
        <div class="col-md-9">	
      	  <div class="checkbox-list"><label class="checkbox-inline"><?php echo input_checkbox_tag('settings[display_field_names]','1',array('checked'=>$settings->get('display_field_names',1))) ?></label></div>        
        </div>			
      </div> 
      
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_EXT_FONT_SIZE ?></label>
        <div class="col-md-9"><?php echo input_tag('settings[content_font_size]',$settings->get('content_font_size',''),['class'=>'form-control input-small number']) ?></div>			
      </div>
      
<?php 
$font_style_choices = [
    'bold' => '<i class="fa fa-bold" aria-hidden="true"></i>',
    'italic' => '<i class="fa fa-italic" aria-hidden="true"></i>',
    'underline' => '<i class="fa fa-underline" aria-hidden="true"></i>',
];
?>        
       
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_EXT_FONT_STYLE ?></label>
        <div class="col-md-9"><?php echo select_checkboxes_button('settings[content_font_style]',$font_style_choices,$settings->get('content_font_style','')) ?></div>			
      </div>
      
<?php 
$alignment_choices = [
    'left' => '<i class="fa fa-align-left" aria-hidden="true"></i>',
    'center' => '<i class="fa fa-align-center" aria-hidden="true"></i>',
    'right' => '<i class="fa fa-align-right" aria-hidden="true"></i>',
];
?>       
     
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_EXT_ALIGNMENT ?></label>
        <div class="col-md-9"><?php echo select_radioboxes_button('settings[content_alignment]',$alignment_choices,$settings->get('content_alignment','left')) ?></div>			
      </div>  
    
  </div>
  
  <div class="tab-pane fade" id="tab_heading">
      <div class="form-group">
      	<label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_HEADING ?></label>
        <div class="col-md-9"><?php echo input_tag('settings[heading]',$settings->get('heading'),['class'=>'form-control input-large']) ?></div>			
      </div>
      
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_WIDHT ?></label>
        <div class="col-md-9"><?php echo input_tag('settings[cell_width]',$settings->get('cell_width',''),['class'=>'form-control input-small number']) ?></div>			
      </div>
      
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_EXT_FONT_SIZE ?></label>
        <div class="col-md-9"><?php echo input_tag('settings[heading_font_size]',$settings->get('heading_font_size',''),['class'=>'form-control input-small number']) ?></div>			
      </div>
     
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_EXT_FONT_STYLE ?></label>
        <div class="col-md-9"><?php echo select_checkboxes_button('settings[heading_font_style]',$font_style_choices,$settings->get('heading_font_style','')) ?></div>			
      </div>
      
     
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_EXT_ALIGNMENT ?></label>
        <div class="col-md-9"><?php echo select_radioboxes_button('settings[heading_alignment]',$alignment_choices,$settings->get('heading_alignment','left')) ?></div>			
      </div>  
      
<?php 
$direction_choices = [
    '' => '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>',
    'BTLR' => '<i class="fa fa-long-arrow-up" aria-hidden="true"></i>',
    'TBRL' => '<i class="fa fa-long-arrow-down" aria-hidden="true"></i>',
];
?>      
      <div class="form-group settings-list">
        <label class="col-md-3 control-label" for="fields_id"><?php echo TEXT_EXT_TEXT_DIRECTION ?></label>
        <div class="col-md-9"><?php echo select_radioboxes_button('settings[heading_text_direction]',$direction_choices,$settings->get('heading_text_direction','')) ?></div>			
      </div>
      
  </div>

</div> 
  
  
  </div>
</div> 
 
<?php echo ajax_modal_template_footer() ?>

</form> 

<script>
  $(function() { 
    $('#templates_form').validate({
			submitHandler: function(form){
				app_prepare_modal_action_loading(form)
				return true;
			}
	  });    
  });
</script>