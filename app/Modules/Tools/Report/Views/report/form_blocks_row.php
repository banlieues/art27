<div class="block-row row no-gutters align-items-center border rounded mb-2 p-2">

    <input type="hidden" name="blocks[<?php echo $i;?>][id_block]" value="<?php echo $block->id_block;?>"/>
    <input type="hidden" name="blocks[<?php echo $i;?>][id_file]" value="<?php echo $block->id_file;?>"/>
    <input type="hidden" name="blocks[<?php echo $i;?>][rank]" value="<?php echo $i;?>"/>

    <div class="col-1">
        <a role="button" class="btn btn-sm"
            title="Changer l'ordre des blocs"
            > 
            <?php echo fontawesome('sort');?> 
        </a>
    </div>

    <div class="block-rank col-1 text-right"> 
    </div>

    <div class="<?php if(empty($block->is_old) && !empty($block->is_updated)):?>col-7<?php else:?>col-8<?php endif;?> pl-4"> 
        <?php echo $block->label;?> 
        <?php if(!empty($block->is_old)):?>
            <span class="badge badge-warning"> Non mis à jour </span>
        <?php endif;?>
    </div>
    <?php if(empty($block->is_old) && !empty($block->is_updated)):?> 
        <div class="col-1">
            <button type="button" class="btn btn-sm text-danger" 
                onclick="block_modal_choice(this, <?php echo $block->id_report_block;?>);" 
                title="Le bloc a été mise à jour. Veuillez choisir quelle version vous souhaitez garder pour ce rapport. Sans confirmation, la version initiale sera retenue pour la publication du rapport."> 
                <?php echo fontawesome('exclamation');?> 
            </button>
        </div>
    <?php endif;?>
    <div class="col-1">
        <button type="button" class="btn btn-sm"
            data-bs-toggle="collapse" data-bs-target="#block<?php echo $block->id_block;?>Collapse" 
            id_file=<?php if(!empty($block->is_old)) echo $block->id_file_current; else echo $block->id_file;?>
            onclick="block_info_collapse(this, <?php echo $id_report;?>, <?php echo $block->id_block;?>);" 
            title="Aperçu du bloc"
            > 
            <?php echo fontawesome('eye');?> 
        </button>
    </div>
    <div class="col-1">
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-sm" onclick="report_modal_block_remove(this)"> 
                <?php echo fontawesome('trash-alt');?> 
            </button>
        </div>
    </div>
    <div class="collapse col-12" id="block<?php echo $block->id_block;?>Collapse">
    </div>
</div>


