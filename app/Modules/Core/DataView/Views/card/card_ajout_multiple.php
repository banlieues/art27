<?php $validation = \Config\Services::validation(); ?>
<?php $dataView = \Config\Services::dataViewConstructor(); ?>

<div class="accordion-item">

<!-- button de l'accordeon -->
<h2 class="accordion-header" id="panelsStayOpen-heading<?=$i?>">
    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse<?=$i?>" aria-expanded="true" aria-controls="panelsStayOpen-collapse<?=$i?>">
        <i><?=$label_multiple_title?> sans titre</i>
    </button>
</h2>
<!-- fin button de l'accordeon -->

<!-- contenu de l'accordeon -->
<div id="panelsStayOpen-collapse<?=$i?>" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-heading<?=$i?>">
    <div class="accordion-body">
       

    <?=view("DataView\Views\get-dataView",[
                                "validation"=>$validation,
                                "typeDataView"=>$typeDataView,
                                "fields"=>$fields,
                                "value"=>null,
                                "indexes"=>explode(",",trim($fields_index)),
                                "num_container"=>$id_components,
                                "is_multiple"=>true,
                                "numero_tour"=>$i-1,
                                "dataView"=>$dataView
                            
                                
            ]); ?>
            <input type="hidden" value="0" name="<?=$name_id_multiple?>[]">
            <div style="display:none" class="text-center">
            <a href="#" class="btn_annuler_multiple_new text-danger"><i class="<?=icon("cancel")?>"></i> Annuler</a>
            </div>
    </div>
</div>
<!-- fincontenu de l'accordeon -->

</div>