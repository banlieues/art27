<div class="h-100">
    <div class="mb-3">
        <?php if($filter[0] == 'Aucun filtre'):?>
            <small> Aucun filtre </small>
        <?php else:?>
            <small> FILTRE(S) : <br> - 
                <?php echo implode('<br> - ', $filter);?>
            </small>
        <?php endif;?>
    </div>
    <?php if(in_array($type_question, [1, 4])):?>
        <div class="d-flex">
            <?php if($type_question==4):?>
                <button type="button" class="btn-sm btn-info" onclick="set_chart_zoom_order(<?php echo $id_question;?>);"> Ordonner </button>
            <?php endif;?>
            <button type="button" class="btn-sm btn-dark ml-2" onclick="add_average_line(<?php echo $id_question;?>);"> Moyenne </button>
            <button type="button" class="btn-sm btn-secondary ml-2" onclick="set_canvas_zoom(<?php echo $id_question;?>);"> Réinitialiser </button>
        </div>
    <?php endif;?>
    <div>
        <canvas id="canvas-zoom" style="max-height: 50vh;"></canvas>
    </div>
</div>
