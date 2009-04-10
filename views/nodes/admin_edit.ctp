<?php
	$html->css('/bakedsimple/css/jquery.ui.tabs', 'stylesheet', null, false);
	$javascript->link('/bakedsimple/js/jquery.ui.core', false);
	$javascript->link('/bakedsimple/js/jquery.ui.tabs', false);
	
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
 					<legend><?php __('Edit Node');?></legend>
					<?php echo $this->element('admin' . DS . 'nodes' . DS . 'form'); ?>
				</fieldset>
			</div>
			<div id="pagecontent">
				<?php
					echo $eav->inputs($attributes);
				?>
			</div>
			<div class="buttonHolder">
				<?php echo $html->link(__('<< List Nodes', true), array('action'=>'index'), array('class' => 'resetButton'));?>
				<?php echo $uniform->submit('Save & List Nodes', array('div' => false, 'name' => 'saveList')); ?>
				<?php echo $uniform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
			</div>
		</div>
	<?php echo $uniform->end();?>
</div>