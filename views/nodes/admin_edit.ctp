<?php
	$html->css('/baked_simple/css/jquery.ui.tabs', 'stylesheet', null, false);
	$javascript->link('/baked_simple/js/jquery.ui.core', false);
	$javascript->link('/baked_simple/js/jquery.ui.tabs', false);

	echo $javascript->codeBlock('
		$(function() {
			$("#tabs").tabs();
		});
	', array('inline' => false));
?>
<div class="nodes form">
	<?php echo $uniform->create('Node');?>
		<div id="tabs">
			<?php
				echo $navigation->create(
					array(
					    'Content Setup' => '#setup',
					    'Content' => '#pagecontent',
						'View' => array('action' => 'view', $this->data['Node']['id']),
					    'All Content' => array('action' => 'index'),
					),
				    array('id' => 'sub-nav')
			    );
			?>
			<div id="setup">
				<fieldset class="blockLabels">
 					<legend><?php __('Edit Content');?></legend>
					<?php echo $this->element('admin' . DS . 'nodes' . DS . 'form'); ?>
				</fieldset>
			</div>
			<div id="pagecontent">
				<?php
					echo $eav->inputs($attributes);
				?>
			</div>
			<div class="ctrlHolder buttonHolder">
				<?php echo $html->link(__('<< List Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
				<?php echo $uniform->submit('Save & List Content', array('div' => false, 'name' => 'saveList')); ?>
				<?php echo $uniform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
			</div>
		</div>
	<?php echo $uniform->end();?>
</div>