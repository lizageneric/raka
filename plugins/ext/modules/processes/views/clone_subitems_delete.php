<?php echo ajax_modal_template_header(TEXT_HEADING_DELETE) ?>

<?php echo form_tag('login', url_for('ext/processes/clone_subitems','action=delete&id=' . _get::int('id')  . '&actions_id=' . _get::int('actions_id') . '&process_id=' . _get::int('process_id'))) ?>
<div class="modal-body">    
<?php echo TEXT_ARE_YOU_SURE?>
</div> 
<?php echo ajax_modal_template_footer(TEXT_BUTTON_DELETE) ?>

</form>   