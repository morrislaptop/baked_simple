<div class="snippets form">
<?php echo $advform->create('Snippet');?>
	<div id="setup">
		<fieldset class="blockLabels">
 			<legend><?php __('Add Snippet Content');?></legend>
 			<?php echo $this->element('snippets' . DS . 'form'); ?>
		</fieldset>
	</div>
	<div class="ctrlHolder buttonHolder">
		<?php echo $html->link(__('<< List Snippet Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
		<?php echo $advform->submit('Save & List Snippet Content', array('div' => false, 'name' => 'saveList')); ?>
		<?php echo $advform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
	</div>
<?php echo $advform->end();?>
</div>