<div class="card flex-fill mb-4 border-top-office">
    <div class="card-header">
        <h5 class="card-title d-flex justify-content-between align-items-center">
                Conditions d'affichage
        </h5>
    </div>  
    <div class="card-body">
            <p><i>Si vous ne sélectionnez aucun filtre, le document apparaîtra alors pour toutes les inscriptions.</i></p>
            <?php $validation = \Config\Services::validation(); ?>
            <?=view("App\Modules\DataView\Views\injected-form-get-dataView",[
                                                    "validation"=>$validation,
                                                    "typeDataView"=>"form",
                                                    "fields"=>$fields,
                                                    "value_filtre"=>TRUE,
                                                    "value"=>$documents_template,
                                                    "indexes"=>$filtres,
                                                    
                                        
                                                    ])
                                                ?> 
    </div>     
</div>