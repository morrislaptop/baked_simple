<?php
	/**
	* @var JavascriptHelper
	*/
	$javascript;
	$html->css('/vendors/jquery.ui/jquery.ui.tabs', 'stylesheet', null, false);
	$javascript->link('/vendors/jquery.ui/jquery.ui.core', false);
	$javascript->link('/vendors/jquery.ui/jquery.ui.tabs', false);

	echo $javascript->codeBlock('
		$(function() {
			$("#tabs").tabs();
			// append a preview link with javascript so it doesnt get picked up by tabs.
			$("#sub-nav").append("<li class=\'sub-nav-view\'>' . $javascript->escapeString($html->link(__('Preview', true), $advform->value('Node.url'), array('target' => '_blank'))) . '</li>");
		});
	', array('inline' => false));
?>
<div class="nodes form">
	<?php echo $advform->create('Node', array('type' => 'file'));?>
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
						$safeContentTabs[$ct] = '#tab-' . $safe;
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
					<?php echo $this->element('nodes' . DS . 'form'); ?>
				</fieldset>
			</div>
			<?php
				#debug($this->data);
				foreach ($attributes as $tab => $fields)
				{
					$safe = preg_replace('/\W/', '', $tab);
					?>
					<div id="tab-<?php echo $safe; ?>">
						<fieldset>
							<legend><?php echo $tab; ?></legend>
							<?php
								foreach ($fields as $input )
								{
									$name = $input['name'];
									unset($input['name']);
									$id = $input['id'];
									unset($input['id']);

									if ( isset($this->data['Node'][$name]) && in_array($input['type'], array('image', 'media', 'document')) ) {
										$mediaId = 'media' . intval(mt_rand());
										$deleteId = 'delete' . intval(mt_rand());

										// decide URL it could be a straight url or use the dir column as well
										if ( !empty($this->data['Node'][$name]['dir']) ) {
											$url = '/' . $this->data['Node'][$name]['dir'] . '/' . $this->data['Node'][$name]['value'];
										}
										else {
											$url = $this->data['Node'][$name]['value'];
											$form->data['Node'][$name] = $url;
										}

										echo $html->div('media', $medium->embed(str_replace('\\', '/', $url)), array('id' => $mediaId));
										$input['after'] = $html->link('Delete', array('plugin' => 'eav', 'controller' => 'eav_attribute_files', 'action' => 'delete', $input['model'], $this->data['Node']['id'], $id), array('id' => $deleteId));
										echo $advform->input($name, $input);

										$javascript->codeBlock('
											$(function() {
												$("#' . $deleteId . '").click(function() {
													$.get(this.href, function (data) {
														$("#' . $mediaId . '").remove();
													});
													return false;
												});
											});
										', array('inline' => false));
										continue;
									}
									echo $advform->input($name, $input);
								}
							?>
						</fieldset>
					</div>
					<?php
				}
			?>
			<div class="ctrlHolder buttonHolder">
				<?php echo $html->link(__('<< List Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
				<?php echo $advform->submit('Save & List Content', array('div' => false, 'name' => 'saveList')); ?>
				<?php echo $advform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
			</div>
		</div>
	<?php echo $advform->end();?>
</div>