<div class="shareds form">
<?php echo $uniform->create('Shared');?>
	<div id="setup">
		<fieldset class="blockLabels">
 			<legend><?php __('Edit Shared Content');?></legend>
 			<?php echo $this->element('admin' . DS . 'shareds' . DS . 'form'); ?>
		</fieldset>
	</div>
	<div class="ctrlHolder buttonHolder">
		<?php echo $html->link(__('<< List Shared Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
		<?php echo $uniform->submit('Save & List Shared Content', array('div' => false, 'name' => 'saveList')); ?>
		<?php echo $uniform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
	</div>
<?php echo $uniform->end();?>
</div>