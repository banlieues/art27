<?php echo $controls->id_report;?>
<?php echo $controls->label;?>

<?php if(in_array($level, ['publication'])):?>
    <?php echo $controls->id_person;?>
    <?php echo $controls->id_request;?>
<?php endif;?>

<?php if(in_array($level, ['schema'])):?>
    <?php echo $controls->ids_road_them;?>
<?php endif;?>

<?php echo $controls->comment;?>

<?php if(in_array($level, ['template', 'publication'])):?>
    <?php echo $controls->id_parent;?>
    <?php echo $controls->ids_road_them;?>
<?php endif;?>

<?php if(!empty($id_report)):?>
    <?php echo $controls->blocks;?>
<?php elseif(in_array($level, ['template', 'publication'])) :?>
    <div id="reportBlocksImport"></div>
<?php elseif($level=='schema'):?>
    <div class="row">
        <div class="col-9 offset-3">
            <div class="alert alert-warning w-100">
                Pour ajouter des blocs, vous devez d'abord enregistrer le schéma.
            </div>
        </div>
    </div>
<?php endif;?>
