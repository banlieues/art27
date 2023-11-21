<?php if(!empty($id_report)):?>
    <div class="row">
        <label class="col-form-label col-3 pt-0"> 
            <?php echo $label;?> 
            <button type="button" class="btn btn-sm" title="Aperçu de tous les blocs"
                onclick="$('[data-bs-toggle=\'collapse\']', '#reportBlocks').click();" 
                > 
                <?php echo fontawesome('eye');?> 
            </button>
        </label>
        <div class="col-9">
            <div id="reportBlocks" <?php if(!empty($blocks)):?> class="mb-2" <?php endif;?>
                >
                <?php if(!empty($blocks)):?>
                    <?php echo $block_warning;?>
                <?php endif;?>

                <?php if(!empty($id_report)) :?>
                    <?php $data = (object) [];?>
                    <?php $data->id_report = $id_report;?>
                    <?php $i = 0;?>
                    <?php foreach ($blocks as $block) : ?>
                        <?php $data->block = $block;?>
                        <?php $data->i = $i;?>
                        <?php echo view('Report\report/form_blocks_row', (array) $data);?>
                        <?php $i++;?>
                    <?php endforeach ;?>
                <?php endif;?>
            </div>
            
            <button type="button" class="btn btn-info" title="Rechercher un bloc par critères"   
                onclick="report_block_modal_search(this, <?php echo $id_report;?>);"
                > 
                Ajouter un nouveau bloc 
            </button>
        </div>
    </div>
<?php endif;?>