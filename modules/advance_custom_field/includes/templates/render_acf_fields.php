 <!-- RENDER ADVANCE CUSTOM FIELDS zeeshan-->
    <div class="deleted_area "><i class="fa fa-trash"></i></div>

    <textarea id="droppable_content" name="droppable_content"></textarea>

    <h3 class="droppableArea text-center">Drag & Drop Custom Fields&nbsp;&nbsp;<span class=" fa fa-arrow-down"> </span></h3>
    <div class="row dropp_able">
        <?php 
        $dropableHTML = get_advance_custom_fields('proposal', $fc_rel_id)[0]['data'];
        $dropableHTML = str_replace("&lt;", "<", $dropableHTML);
        $dropableHTML = str_replace("&gt;", ">", $dropableHTML);
        echo $dropableHTML;
        ?>
    </div>