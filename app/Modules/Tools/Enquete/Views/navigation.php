<div class="row justify-content-end align-items-center px-3">
    <?php if(!empty($target)):?>
        <div class="col-auto mb-0">
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-warning" 
                    onclick="filter_modal(this);" 
                    filter-target="<?php echo $target->name;?>"
                    <?php if(!empty($reference)):?>
                        reference="<?php echo $reference;?>"
                    <?php endif;?>
                    > 
                    Filtrer les enquêtes 
                </button>
                <?php if(!empty(session('filter'))):?>
                    <a role="button"
                        class="btn btn-sm btn-warning"
                        href="<?php echo $target->url . '/1';?>"
                        title="Réinitialiser le filtre"
                        onclick="waiting_start(this);"
                        > 
                        <?php echo fontawesome('filter-clear');?> 
                    </a>     
                <?php endif;?>
            </div>
        </div>
    <?php endif;?>
    <div class="col-auto mb-0 d-flex">
        <div class="d-flex align-items-center mx-1" title="En attente">
            <div class="mx-1"> <?php echo fontawesome('paper-plane');?> </div>
            <small> <div class="badge bg-secondary"> <?php echo $totals->waiting;?> </div> </small>
        </div>
        <div class="d-flex align-items-center mx-1" title="Consulté">
            <div class="mx-1"> <?php echo fontawesome('eye');?> </div>
            <small> <div class="badge bg-secondary"> <?php echo $totals->consulted;?> </div> </small>
        </div>
        <div class="d-flex align-items-center mx-1" title="Répondu">
            <div class="mx-1"> <?php echo fontawesome('clipboard-check');?> </div>
            <small> <div class="badge bg-secondary"> <?php echo $totals->answer;?> </div> </small>
        </div>
        <div class="d-flex align-items-center mx-1" title="Total">
            <div class="mx-1 fw-bold"> Total </div>
            <small> <div class="badge bg-secondary"> <?php echo $totals->sended;?> </div> </small>
        </div>
    </div>
</div>