<?php
  /**
  * @var JavascriptHelper
  */
  $javascript;
  $html->css('/baked_simple/css/jquery.ui.tabs', 'stylesheet', null, false);
  $javascript->link('/baked_simple/js/jquery.ui.core', false);
  $javascript->link('/baked_simple/js/jquery.ui.tabs', false);

  echo $javascript->codeBlock('
    $(function() {
      $("#tabs").tabs();
      // append a preview link with javascript so it doesnt get picked up by tabs.
      $("#sub-nav").append("<li class=\'sub-nav-view\'>' . $javascript->escapeString($html->link(__('Preview', true), $uniform->value('Node.url'), array('target' => '_blank'))) . '</li>");
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
            <fieldset>
              <legend><?php echo $tab; ?></legend>
              <?php
                foreach ($fields as $input )
                {
                  $name = $input['name'];
                  unset($input['name']);

                  if ( isset($this->data['Node'][$name]) && in_array($input['type'], array('image', 'flash', 'file')) ) {
                    $mediaId = 'media' . intval(mt_rand());
                    $deleteId = 'delete' . intval(mt_rand());
                    echo $html->div('media', $media->display(str_replace('\\', '/', '/' . $this->data['Node'][$name]['dir'] . '/' . $this->data['Node'][$name]['value'])), array('id' => $mediaId));
                    $input['after'] = $html->link('Delete', array('plugin' => 'eav', 'controller' => 'eav_attribute_files', 'action' => 'delete', $input['model'], $this->data['Node']['id'], $input['id']), array('id' => $deleteId));
                    echo $uniform->input($name, $input);

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
                  echo $uniform->input($name, $input);
                }
              ?>
            </fieldset>
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