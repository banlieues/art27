<div class="h-100">
    <div class="mb-3">
        <div class="alert alert-warning">
            <?php if(!empty($filter_active)) echo $filter_active;?>
        </div>
    </div>
    <?php if(!empty($type_question) && in_array($type_question, [1, 4])):?>
        <div>
            <?php if($type_question==4):?>
                <button type="button" class="btn btn-sm btn-info" onclick="set_chart_zoom_order(<?php echo $id_question;?>);"> Ordonner </button>
            <?php endif;?>
            <button type="button" class="btn btn-sm btn-dark" onclick="add_average_line(<?php echo $id_question;?>);"> Moyenne </button>
            <button type="button" class="btn btn-sm btn-secondary" onclick="set_canvas_zoom('chart', <?php echo $id_question;?>);"> Réinitialiser </button>
        </div>
    <?php endif;?>
    <div>
        <canvas id="canvas-zoom" style="max-height: 50vh;"></canvas>
    </div>
</div>
