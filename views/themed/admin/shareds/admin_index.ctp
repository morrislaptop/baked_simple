<?php echo $html->css('tables', false, false, false); ?>
<div class="shareds index">
<h2><?php __('Shared Content');?> <small><?php echo $html->link(__('Create Shared Content', true), array('action'=>'add')); ?></small></h2>
<table cellpadding="0" cellspacing="0">
<tr>
	<th class="headerLeft">#</th>
	<th>Title</th>
	<th>Modified</th>
	<th class="headerRight actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($shareds as $shared):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?> id="<?php echo $shared['Shared']['id']; ?>">
		<td>
			<?php echo $shared['Shared']['id']; ?>
		</td>
		<td>
			<?php echo $shared['Shared']['title']; ?>
		</td>
		<td>
			<?php echo $shared['Shared']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $shared['Shared']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $shared['Shared']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $shared['Shared']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $shared['Shared']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	<?php echo $this->element('tfoot', array('plugin' => 'advindex', 'cols' => 3)); ?>
</table>
</div>
