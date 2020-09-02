<h3 class="page-title"><?php echo TEXT_EXT_LICENSE ?></h3>

<p><?php echo TEXT_EXT_LICENSE_INFO ?></p>

<?php if(!defined('CFG_PLUGIN_EXT_LICENSE_KEY')): ?>

<?php echo form_tag('key',url_for('ext/license/key','action=save'),array('class'=>'form-horizontal'))?>
<div class="form-body">  
  <div class="form-group">
  	<label class="col-md-3 control-label" ><?php echo TEXT_EXT_ENTER_LICENSE_KEY . ' <b>' . str_replace('www.','',filter_var($_SERVER['HTTP_HOST'],FILTER_VALIDATE_DOMAIN , FILTER_FLAG_HOSTNAME)) . '</b>' ?></label>
    <div class="col-md-9">	
  	  <?php echo textarea_tag('product_key','',array('class'=>'form-control input-xlarge')); ?>
      <?php echo tooltip_text(TEXT_EXT_LICENSE_KEY_INFO) ?>
    </div>			
  </div>
</div>  
<?php echo submit_tag(TEXT_BUTTON_SAVE) ?>
</form>

<?php elseif(!license::check_key()): ?>

<div class="alert alert-danger"><?php echo TEXT_EXT_PRODUCT_KEY_NOT_CORRECT . ' ' . str_replace('www.','',filter_var($_SERVER['HTTP_HOST'],FILTER_VALIDATE_DOMAIN , FILTER_FLAG_HOSTNAME)) ?></div>

<?php echo form_tag('key',url_for('ext/license/key','action=update'),array('class'=>'form-horizontal'))?>
<div class="form-body">  
  <div class="form-group">
  	<label class="col-md-3 control-label" ><?php echo TEXT_EXT_ENTER_LICENSE_KEY . ' <b>' . str_replace('www.','',filter_var($_SERVER['HTTP_HOST'],FILTER_VALIDATE_DOMAIN , FILTER_FLAG_HOSTNAME)) . '</b>' ?></label>
    <div class="col-md-9">	
  	  <?php echo textarea_tag('product_key',CFG_PLUGIN_EXT_LICENSE_KEY,array('class'=>'form-control input-xlarge')); ?>
      <?php echo tooltip_text(TEXT_EXT_LICENSE_KEY_INFO) ?>
    </div>			
  </div>
</div>  
<?php echo submit_tag(TEXT_BUTTON_SAVE) ?>
</form>

<?php else: ?>
<p class="alert alert-success"><i class="fa fa-check"></i> <?php echo TEXT_EXT_LICENSE_ACTIVE. ' <b>' . str_replace('www.','',filter_var($_SERVER['HTTP_HOST'],FILTER_VALIDATE_DOMAIN , FILTER_FLAG_HOSTNAME)) . '</b>' ?></p>
<?php endif ?>

 
