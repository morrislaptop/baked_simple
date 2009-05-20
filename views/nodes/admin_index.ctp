<div class="nodes index">
<h2><?php __('Content');?> <small><?php echo $html->link(__('Create Content', true), array('action'=>'add')); ?></small></h2>
<?php $session->flash(); ?>
<table cellpadding="0" cellspacing="0">
<tr>
	<th>#</th>
	<th>Title</th>
	<th>Type</th>
	<th>Template</th>
	<th>Url</th>
	<th>Modified</th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$levels = array();
$levelCounts = array();
$i = 0;
foreach ($nodes as $node):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}

	$pid = $node['Node']['parent_id'];
	if ( end($levels) != $pid ) {
		if ( $pid && !in_array($pid, $levels) ) {
			$levels[] = $node['Node']['parent_id'];
		}
		else {
			array_pop($levels);
		}
	}

	$level = count($levels);
	if ( !isset($levelCounts[$level]) ) {
		$levelCounts[$level] = 0;
	}
	$levelCounts[$level]++;

	// reset all future levels back to 0
	$maxLevels = count($levelCounts);
	for ( $j = $level + 1; $j < $maxLevels; $j++ ) {
		$levelCounts[$j] = 0;
	}
	$levelCounts = array_filter($levelCounts);
?>
	<tr<?php echo $class;?> id="<?php echo $node['Node']['id']; ?>">
		<td>
			<?php echo implode('.', $levelCounts); ?> (<?php echo $node['Node']['id']; ?>)
		</td>
		<td style="padding-left: <?php echo 30 * $level + 5; ?>px;">
			- <?php echo $node['Node']['title']; ?>
		</td>
		<td>
			<?php echo $node['Node']['type']; ?>
		</td>
		<td>
			<?php echo $node['Node']['template']; ?>
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
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<?php
	echo $javascript->codeBlock('
		$(".paging a").click(function() {
			$("#content").load(this.href);
			return false;
		});
	');
?>