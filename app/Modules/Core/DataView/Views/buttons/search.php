<div class="input-group input-group-sm input-group-navbar border border-light rounded">
    <input type="text" class="form-control"
        form="searchOrderForm"
        placeholder="Rechercher…" 
        aria-label="Rechercher" 
        name="itemSearch" 
        onkeydown="if(event.keyCode == 13) {$(this).parent().find('button[type=\'submit\']').click();}"
        <?php if($itemSearch!==FALSE):?>value="<?php echo $itemSearch?>" <?php endif;?>
    />
    <button type="button"
        class="btn btn-sm btn-outline-secondary"
        title="Effacer la valeur de recherche"
        onclick="$(this).parent().find('input').val(null);"
        >
        <?php echo fontawesome('times');?>
    </button>
    <?php if(!empty($itemSearch)):?>
        <button type="button"
            form="searchOrderForm"
            class="btn btn-sm btn-secondary"
            title="Reinitialiser le filtre"
            onclick="
                waiting_start(this);
                $('input[form=\'searchOrderForm\']').val(null);
                $('#searchOrderForm').submit();
            " 
            >
            <?php echo fontawesome('filter-clear');?>
        </button>
    <?php endif;?>
    <button type="submit"
        form="searchOrderForm"
        class="btn btn-sm btn-<?php echo $color;?> btn_search"
        title="Filtrer"
        onclick="waiting_start(this);" 
        >
        <?php echo fontawesome('search');?>
    </button>
</div>

