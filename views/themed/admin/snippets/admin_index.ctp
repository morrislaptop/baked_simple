<?php echo $html->css('tables', false, false, false); ?>
<div class="snippets index">
<h2><?php __('Snippet Content');?> <small><?php echo $html->link(__('Create Snippet Content', true), array('action'=>'add')); ?></small></h2>
<table cellpadding="0" cellspacing="0">
<tr>
	<th class="headerLeft">#</th>
	<th>Title</th>
	<th>Modified</th>
	<th class="headerRight actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($snippets as $snippet):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?> id="<?php echo $snippet['Snippet']['id']; ?>">
		<td>
			<?php echo $snippet['Snippet']['id']; ?>
		</td>
		<td>
			<?php echo $snippet['Snippet']['title']; ?>
		</td>
		<td>
			<?php echo $snippet['Snippet']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $snippet['Snippet']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $snippet['Snippet']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $snippet['Snippet']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $snippet['Snippet']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	<?php echo $this->element('tfoot', array('plugin' => 'advindex', 'cols' => 3)); ?>
</table>
</div>
