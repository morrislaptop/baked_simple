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
			<ul id="sub-nav">
				<?php
					$tabs = array(
						'Properties' => '#setup',
					);

					// add the tabs for content fields.
					$contentTabs = array_keys($attributes);
					$safeContentTabs = array();
					foreach ($contentTabs as $ct) {
						$safe = preg_replace('/\W/', '', $ct);
						$safeContentTabs[$ct] = '#' . $safe;
					}

					$tabs = array_merge($tabs, $safeContentTabs);

					foreach ($tabs as $label => $url) {
						?>
						<li><?php echo $html->link($label, $url); ?></li>
						<?php
					}
				?>
			</ul>
			<div id="setup">
				<fieldset class="blockLabels">
 					<legend><?php __('Edit Content');?></legend>
					<?php echo $this->element('admin' . DS . 'nodes' . DS . 'form'); ?>
				</fieldset>
			</div>
			<?php
				#debug($this->data);
				foreach ($attributes as $tab => $fields)
				{
					$safe = preg_replace('/\W/', '', $tab);
					?>
					<div id="<?php echo $safe; ?>">
						<?php
							foreach ($fields as $input )
							{
								if ( isset($this->data['Node'][$input['name']]) && in_array($input['type'], array('image', 'flash', 'file')) ) {
									echo $media->display('/' . $this->data['Node'][$input['name']]);
								}
								echo $uniform->input($input['name'], $input);
							}
						?>
					</div>
					<?php
				}
			?>
			<div class="ctrlHolder buttonHolder">
				<?php echo $html->link(__('<< List Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
				<?php echo $uniform->submit('Save & List Content', array('div' => false, 'name' => 'saveList')); ?>
				<?php echo $uniform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
			</div>
		</div>
	<?php echo $uniform->end();?>
</div>