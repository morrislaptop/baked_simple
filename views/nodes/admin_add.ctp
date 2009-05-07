<?php
	echo $navigation->create(
		array(
        'Content Setup' => array('action' => 'add'),
        'All Content' => array('action' => 'index'),
    	),
	    array('id' => 'sub-nav')
	);
?>
<div class="nodes form">
<?php echo $uniform->create('Node', array('class' => 'uniForm'));?>
	<div id="setup">
		<fieldset class="blockLabels">
 			<legend><?php __('Create Content');?></legend>
 			<?php echo $this->element('admin' . DS . 'nodes' . DS . 'form'); ?>
		</fieldset>
	</div>
	<div class="ctrlHolder buttonHolder">
		<?php echo $html->link(__('<< List Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
		<?php echo $uniform->submit('Save & List Content', array('div' => false, 'name' => 'saveList')); ?>
		<?php echo $uniform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
	</div>
<?php echo $uniform->end();?>
</div>