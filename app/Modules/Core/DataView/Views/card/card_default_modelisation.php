<?php 
    //on recupere le theme
    $type_component=$component->type;

?>

<!-- ccard dans la colonne -->
<div class="card flex-fill mb-4 card_sortable">

    <!-- header de la card -->
    <div class="card-header border-top-<?php echo $themes->$type_component->color;?>">
        
        <h5 class="card-title d-flex justify-content-between align-items-center">
            <span class="text-<?php echo $themes->$type_component->color;?>"><?php echo $themes->$type_component->icon;?></span>
            <?=$component->title?>
            <i style="cursor:grab" class="<?=icon("moveHorizontal");?> move-sortable-column"></i>
        </h5>
        
    </div>
    <!-- fin header de la card -->

    <!-- body de la card  -->
    <div class="card-body">

    </div>
    <!-- fin body de la card  -->

    <!-- footer de la card  -->
    <div class="card-footer">

    </div>
    <!-- footer de la card  -->

</div>
<!-- fin card dans la colonne -->