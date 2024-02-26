<div class="panel-group no-margin">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo html_entity_decode($nodeId); ?>" aria-expanded="true">
        <span class="text-success glyphicon glyphicon-log-in"> </span><span class="text-success"> <?php echo _l('flow_start'); ?></span>
      </h4>
    </div>
    <div id="collapse_node_<?php echo html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
      <div class="box" node-id="<?php echo html_entity_decode($nodeId); ?>">
            <div class="form-group">
                  <label for="Select_Flow"><?php echo _l('Select Flow'); ?></label><br />
                  <select name="flow_dropdown" class="form-control" onchange="load_actions(this.value)" df-flows="">
                      <?php foreach ($flows as $flowItem): ?>
                          <option value="<?php echo $flowItem; ?>"><?php echo $flowItem; ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
        </div>
    </div>
  </div>
</div>
