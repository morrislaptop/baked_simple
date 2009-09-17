<div class="snippets index">
<h2><?php __('Snippets');?></h2>
<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th class="headerLeft">#</th>
			<th><?php echo $paginator->sort('title'); ?></th>
			<th><?php echo $paginator->sort('type'); ?></th>
			<th><?php echo $paginator->sort('modified'); ?></th>
			<th class="headerRight actions"><?php __('Actions');?></th>
		</tr>
	</thead>
	<tbody>
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
			<?php echo $snippet['Snippet']['type']; ?>
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
	</tbody>
	<tfoot>
		<?php echo $this->element('tfoot', array('plugin' => 'advindex', 'cols' => 4)); ?>
	</tfoot>
</table>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Snippet', true), array('action'=>'add')); ?></li>
	</ul>
</div>