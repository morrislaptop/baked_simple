<div class="nodes index">
<h2><?php __('Content');?></h2>
<table cellpadding="0" cellspacing="0">
<tr>
	<th>#</th>
	<th>Parent</th>
	<th>Title</th>
	<th>Type</th>
	<th>Url</th>
	<th>Modified</th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($nodes as $node):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?> id="<?php echo $node['Node']['id']; ?>">
		<td>
			<?php echo $node['Node']['id']; ?>
		</td>
		<td>
			<?php echo $node['Node']['parent_id']; ?>
		</td>
		<td>
			<?php echo $node['Node']['title']; ?>
		</td>
		<td>
			<?php echo $node['Node']['type']; ?>
		</td>
		<td>
			<?php echo $node['Node']['url']; ?>
		</td>
		<td>
			<?php echo $node['Node']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $node['Node']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $node['Node']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $node['Node']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $node['Node']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Node', true), array('action'=>'add')); ?></li>
	</ul>
</div>
