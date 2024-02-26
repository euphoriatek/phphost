<div class="panel-group no-margin">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo html_entity_decode($nodeId); ?>" aria-expanded="true">
        <span class="text-info glyphicon glyphicon-retweet"> </span><span class="text-info"> <?php echo _l('action'); ?></span>
      </h4>
    </div>
    <div id="collapse_node_<?php echo html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
      <div class="box" node-id="<?php echo html_entity_decode($nodeId); ?>">
      	<select name="actions_dropdown" id="actions_dropdown" class="form-control" df-track="">
    	</select>
      </div>
    </div>
  </div>
</div>
