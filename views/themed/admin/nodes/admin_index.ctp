<div class="nodes index">
<h2><?php __('Nodes');?>
</h2>
<?php echo $advindex->create('Node'); ?>
<table cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th class="headerLeft"><?php echo $paginator->sort('id'); ?></th>
		<th><?php echo $paginator->sort('type'); ?></th>
		<th><?php echo $paginator->sort('title'); ?></th>
		<th><?php echo $paginator->sort('template'); ?></th>
		<th><?php echo $paginator->sort('enabled'); ?></th>
		<th><?php echo $paginator->sort('visible'); ?></th>
		<th><?php echo $paginator->sort('default'); ?></th>
		<th><?php echo $paginator->sort('depth'); ?></th>
		<th><?php echo $paginator->sort('sequence'); ?></th>
		<th><?php echo $paginator->sort('first'); ?></th>
		<th><?php echo $paginator->sort('last'); ?></th>
		<th class="headerRight actions"><?php __('Actions'); ?></th>
	</tr>
</thead>
<tbody>
<?php
$i = 0;
foreach ($nodes as $node):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	$id = $node['Node']['id'];
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $node['Node']['id']; ?>
		</td>
		<td>
			<?php echo $node['Node']['type']; ?>
		</td>
		<td style="padding-left: <?php echo $node['Node']['depth'] * 50 + 10; ?>px; text-align: left;">
			<?php
				if ( $node['Node']['url'] ) {
					echo $html->link($node['Node']['title'], $node['Node']['url'], array('target' => '_blank')); 
				}
				else {
					echo $node['Node']['title'];
				}
			?> 
			<span style="color: #ccc; font-size: 10px;">(<?php echo $node['Node']['menu_title']; ?>)</span>
		</td>
		<td>
			<?php echo $node['Node']['layout']; ?> / <?php echo $node['Node']['template']; ?>
		</td>
		<td>
			<?php echo $this->element('toggler', array('plugin' => 'advindex', 'value' => $node['Node']['enabled'], 'field' => 'enabled', 'id' => $id)); ?>
		</td>
		<td>
			<?php echo $this->element('toggler', array('plugin' => 'advindex', 'value' => $node['Node']['visible'], 'field' => 'visible', 'id' => $id)); ?>
		</td>
		<td>
			<?php echo $html->image('/advindex/img/' . ($node['Node']['default'] ? 'on.png' : 'off.png')); ?>
		</td>
		<td>
			<?php echo $node['Node']['depth']; ?>
		</td>
		<td>
			<?php echo $node['Node']['sequence']; ?>
		</td>
		<td>
			<?php echo $node['Node']['first']; ?>
		</td>
		<td>
			<?php echo $node['Node']['last']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $node['Node']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $node['Node']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $node['Node']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $node['Node']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
	<?php echo $this->element('tfoot', array('plugin' => 'advindex', 'cols' => 11)); ?>
</tfoot>
</table>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Node', true), array('action'=>'add')); ?></li>
	</ul>
</div>