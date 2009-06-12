<div class="shareds form">
<?php echo $advform->create('Shared');?>
	<div id="setup">
		<fieldset class="blockLabels">
 			<legend><?php __('Add Shared Content');?></legend>
 			<?php echo $this->element('shareds' . DS . 'form'); ?>
		</fieldset>
	</div>
	<div class="ctrlHolder buttonHolder">
		<?php echo $html->link(__('<< List Shared Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
		<?php echo $advform->submit('Save & List Shared Content', array('div' => false, 'name' => 'saveList')); ?>
		<?php echo $advform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
	</div>
<?php echo $advform->end();?>
</div>