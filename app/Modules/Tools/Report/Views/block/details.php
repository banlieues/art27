<form id="<?php echo $form_id;?>" class="updateForm needs-validation ajax_submit" novalidate
    action="<?php echo base_url('report/block/view/' . $id_block);?>" 
    method="post" enctype="multipart/form-data"
    >
    <div class="mt-2 row">    
        <div class="col-10">
            
            <?php view('Report\block/form.php');?>
            
        </div>
        <div class="col-sm-2">
            <div class="form-nav text-center">    
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 w-100"
                        onclick="window.location.reload()" 
                        > 
                        <?php echo t("Annuler les modifications", __NAMESPACE__);?> 
                    </button>
                    <button class="btn btn-sm btn-success mb-1 w-100" form="<?php echo $form_id;?>"
                        >
                        <?php echo t("Enregistrer les modifications", __NAMESPACE__);?>
                    </button>
                    <a role="button" class="btn btn-sm btn-primary mb-1 w-100" 
                        href="<?php echo base_url('report/block_download/' . $id_block);?>"
                        >
                        <?php echo t("Télécharger le bloc en Word", __NAMESPACE__);?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
