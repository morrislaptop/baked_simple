<div class="nodes form">
  <?php echo $advform->create('Node', array('class' => 'uniForm'));?>
    <div id="tabs">
      <ul id="sub-nav">
        <li><a href="#"><span>Properties</span></a></li>
      </ul>
    </div>
    <div id="setup">
      <fieldset class="blockLabels">
         <legend><?php __('Create Content');?></legend>
         <?php echo $this->element('nodes' . DS . 'form'); ?>
      </fieldset>
    </div>
    <div class="ctrlHolder buttonHolder">
      <?php echo $html->link(__('<< List Content', true), array('action'=>'index'), array('class' => 'resetButton'));?>
      <?php echo $advform->submit('Save & List Content', array('div' => false, 'name' => 'saveList')); ?>
      <?php echo $advform->submit('Save & Continue', array('div' => false, 'class' => 'primaryAction', 'name' => 'saveEdit')); ?>
    </div>
  <?php echo $advform->end();?>
</div>