<style>
 .accordion {
  --bs-accordion-btn-bg: white;
  --bs-accordion-active-bg: #eee;

  --bs-accordion-bg: white;

  --bs-accordion-btn-focus-box-shadow: none;

  --bs-accordion-btn-focus-box-shadow: none;
  --bs-accordion-btn-focus-border-color: unset;
}
.accordion-button:not(.collapsed) {
  box-shadow: none;
}
.accordion-button {
  border-top: 1px solid #000000;
}
.accordion-item:last-child .accordion-button.collapsed,
.accordion-item:last-child .accordion-body {
  border-bottom: 1px solid #000000;
}

</style>

<?php 
    //on recupere le theme
    $type_component=$component->type;
?>



<!-- card dans la colonne -->
<div class="card flex-fill mb-4">

    <!-- header de la card -->
    <div class="card-header border-top-<?php echo $themes->$type_component->color;?>">
        
        <h5 class="card-title d-flex justify-content-between align-items-center">

            <!-- titre de la card -->
            <span class="text-<?php echo $themes->$type_component->color;?>"><?php echo $themes->$type_component->icon;?></span>
            <?=$component->title?>
            <!-- fin de la card -->

           

        </h5>

    </div>
    <!-- fin header de la card -->

    <!-- body de la card  -->
    <div class="card-body">

        <!-- container de la view read -->
        <div class="view_components_read">
                    
                    <?php if(!is_array($data)):?>

                        <!-- cas où l'on est pas multiple -->
                        <?=view("DataView\Views\get-dataView",[
                                                "validation"=>$validation,
                                                "typeDataView"=>$typeDataView,
                                                "fields"=>$fields,
                                                "value"=>$data,
                                                "indexes"=>explode(",",trim($component->fields)),
                                                "num_container"=>$component->id_components,
                                                
                            ]);
                        ?> 
                        <!-- fin du cas où l'on n'est pas multiple -->

                <?php else:?>

                    <!-- cas où l'on est multiple -->    
                    <?php $i=1;?>

                    <!-- debut de l'accordion -->
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        
                        <?php foreach($data as $d):?>

                            <div class="accordion-item">

                                <!-- button de l'accordeon -->
                                <h2 class="accordion-header" id="panelsStayOpen-heading<?=$i?>">
                                    <button class="accordion-button <?php if($i>1):?>collapsed<?php endif?>" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse<?=$i?>" aria-expanded="<?php if($i==1):?>true<?php else:?>false<?php endif?>" aria-controls="panelsStayOpen-collapse<?=$i?>">
                                        <i><?=$component->label_multiple_title?><?php $champ_multiple_title=$component->champ_multiple_title?> <?=$d->$champ_multiple_title?></i>
                                    </button>
                                </h2>
                                <!-- fin button de l'accordeon -->

                                <!-- contenu de l'accordeon -->
                                <div id="panelsStayOpen-collapse<?=$i?>" class="accordion-collapse collapse <?php if($i==1):?>show<?php endif?>" aria-labelledby="panelsStayOpen-heading<?=$i?>">
                                    <div class="accordion-body">

                                        <?=view("DataView\Views\get-dataView",[
                                                                "validation"=>$validation,
                                                                "typeDataView"=>$typeDataView,
                                                                "fields"=>$fields,
                                                                "value"=>$d,
                                                                "indexes"=>explode(",",trim($component->fields)),
                                                                "num_container"=>$component->id_components,
                                                                
                                            ]); ?>

                                    </div>
                                </div>
                                 <!-- fincontenu de l'accordeon -->

                            </div>

                            <?php $i=$i+1;?>
                        <?php endforeach?>

                    </div><!-- fin de l'accordéon -->
                    
                <!-- cas où l'on est pas multiple -->
                <?php endif;?>
        </div>
        <!-- fin container de la view read -->


    



    </div>
    <!-- fin body de la card  -->

    <!-- footer de la card  -->
    <div class="card-footer">

    </div>
    <!-- footer de la card  -->

</div>
<!-- fin card dans la colonne -->