<?php
	$javascript->codeBlock('
		$(function() {
			$("#NodeTitle").change(function() {
				var menuTitle = $("#NodeMenuTitle")
				if ( !menuTitle.attr("value") ) {
					menuTitle.attr("value", this.value);
				}
			});
			$("#NodeType").change(function() {
				if ( "Url" == this.value ) {
					$("#DivNodeUrl").show();
				}
				else {
					$("#DivNodeUrl").hide();
				}
				if ( "Page" != this.value ) {
					$(".pageProperties").hide();
				}
				else {
					$(".pageProperties").show();
				}
			});
		});
	', array('inline' => false));

	$showUrl = isset($this->data['Node']['type']) && 'Url' == $this->data['Node']['type'];
	$showPageProperties = empty($this->data['Node']['type']) || 'Page' == $this->data['Node']['type'];

	// Steal the flash message so it doesnt look gay with the tabs
	$session->flash();

	echo $advform->input('title', array('class' => 'textInput title'));
	echo $advform->input('menu_title');
	echo $advform->input('parent_id', array('empty' => '- No Parent -', 'escape' => false));
	echo $advform->input('type');
	$opts = array();
	if ( !$showUrl ) {
		$opts['div'] = array(
			'style' => 'display: none;',
			'id' => 'DivNodeUrl'
		);
	}
	echo $advform->input('url', $opts);
?>
<div class="pageProperties" style="display: <?php echo $showPageProperties ? 'block' : 'none'; ?>">
	<?php
		echo $advform->input('NodeAlias.alias', array('label' => 'Node Aliases', 'type' => 'textarea', 'after' => '<p class="formHint">Node aliases allow this node to be access from different URLs. One per line. Use a MySQL Regex</p>'));
		echo $advform->input('layout');
		echo $advform->input('template');
		echo $advform->input('enabled', array('checked' => isset($this->data['Node']['enabled']) ? $this->data['Node']['enabled'] : true ));
		echo $advform->input('visible', array('checked' => isset($this->data['Node']['visible']) ? $this->data['Node']['visible'] : true ));
		echo $advform->input('default');
	?>
</div>