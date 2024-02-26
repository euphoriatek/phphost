<div class="panel-group no-margin">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo html_entity_decode($nodeId); ?>" aria-expanded="true">
        <span class="text-danger glyphicon glyphicon-fullscreen"> </span><span class="text-danger"> <?php echo _l('condition'); ?></span>
      </h4>
    </div>
    <div id="collapse_node_<?php echo html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
      <div class="box" node-id="<?php echo html_entity_decode($nodeId); ?>">
        <?php $condition = [
            ['id' => 'nothing','name' => _l('nothing')],
            ['id' => 'click','name' => _l('click')],
            ['id' => 'hover','name' => _l('hover')],
          ]; ?>
          <?php echo render_select('condition['.$nodeId.']',$condition, array('id', 'name'),'condition', '', ['df-condition' => ''], [], '', '', false); ?>
      </div>
    </div>
  </div>
</div>