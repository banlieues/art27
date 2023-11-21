<?php $request = \Config\Services::request(); ?>
<?php $validation = \Config\Services::validation(); ?>

<?php if($request->getVar()): $getVar=$request->getVar(); endif;?>

<?php $this->extend('Layout\index'); ?>
<?php $this->section("body"); ?>

    <?php if(empty($injectedForm)):?>

        <h3 class="text-center">Aucun formulaire trouvé</h3>

    <?php else:?> 
   


    <form method="post" action="<?=base_url()?>/modelisation/saveInjectedForm">
         <!--block title -->
         <div class="card-header p-1 p-xl-1 sticky_button bg-light">
            <div class="row">
                <div class="col-auto align-self-center">
                    <h3 class="fs-4">Edition de formulaire</h3>
                </div>
                <div class="col align-self-center">
                    <div class="text-end">
                        <span class="zone-button-form">
                            <button class="btn btn-success btn-sm" type="submit"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                        
                                <a class="btn btn-danger btn-sm" href="<?=base_url()?>/modelisation/"><i class="<?=icon("cancel")?>"></i> Annuler</a>
                        </span>
                        <span style="display:none" class="zone-submit-loading"> <i class="fas fa-circle-notch fa-spin"></i> Veuillez patientez</span>  
                
                    </div>  
                </div>      
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12 col-lg-8">
                <div class="card flex-fill mb-4 card_sortable">
                    <div class="card-header">
                        <h5 class="card-title d-flex justify-content-between align-items-center"><i class="<?=icon("modelisation")?>"></i> Formulaire
                           
                    </div>
                    <div class="card-body">
                        <div class="row">
                                <div class="col-md-8 col-lg-10">
                                    <b>Nom du formulaire</b>
                                </div>
                                <div class="col-md-8 col-lg-10">
                                    <input name="title" value="<?php if(isset($injectedForm->header_text)):?><?php echo $injectedForm->title?><?php endif;?>" type="text" class="form-control">
                                </div>
                        </div> 
                        <div class="row">
                                <div class="col-md-8 col-lg-10 mt-3">
                                    <b>En tête du formulaire</b>
                                </div>
                                <div class="col-md-8 col-lg-10">
                                   
                                    <textarea id="textarea_injected_form_header_text" onpinput="updateTextareaHeight(this);" rows="1" name="header_text"  type="text" class="form-control"><?=$valueHeaderText;?></textarea>
                                </div>
                        </div> 
                    </div>
                    <div class="card-body fields-sortable">
                          <hr>
                        <?=view("DataView\Views\injected-form-get-dataView",[
                                                    "validation"=>$validation,
                                                    "typeDataView"=>"modelisation",
                                                    "fields"=>$fields,
                                                    "value"=>NULL,
                                                    "indexes"=>explode(",",trim($injectedForm->fields))
                                                    
                                                    ])
                                                ?> 
                    <input class="fields_order" type="hidden" value="<?=$injectedForm->fields?>" name="fields"> 
                    </div>  
                    <div class="card-body pt-0 add_fields">
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-lg-12">
                                      
                                            <span url="<?=base_url()?>/dataview/list_add_field/contact/registration" class="link_add_fields" state="close" style="cursor:pointer"><i class="<?=icon("plus-field")?>"></i> Ajouter un champ</span>
                                        </div>
                                    </div>
                                    <div style="" type="contact" class="possible_fields">
                                    <div>
                                            <p class="my-3"><b>Champs par défaut</b></p>
                                        </div>
                                         <?=$dataView->getListAddFieldInjectedForm($id_injected_form);?>
                                         <div>
                                            <p class="my-3"><b>Autres Champs possibles</b></p>
                                            <p class="my-2"><i>Champs de l'entité contact</i></p>
                                            <?=$dataView->getListAddFieldInjectedOther($id_injected_form,"contact");?>
                                            <p class="my-2"><i>Champs de l'entité inscription</i></p>
                                            <?=$dataView->getListAddFieldInjectedOther($id_injected_form,"registration");?>
                                            
                                        </div>
                                    </div>
                                   
                                </div>                      
                 </div>                       
            </div>  
            
            <div class="col-md-12 col-lg-4">
                    <div class="card flex-fill mb-4 card_sortable">
                        <div class="card-header">
                            <h5 class="card-title d-flex justify-content-between align-items-center"><i class="<?=icon("modelisation")?>"></i> Conditions d'affichage
                            
                        </div>
                        <div class="card-body">
                            <p><i>Veuillez choisir les conditions d'affichage pour ce formulaire. Si un autre formulaire possède les mêmes conditions alors le formulaire le plus récent sera affiché sur le site</i></p>
                            <?php if(!is_null($injectedFormConditions->conditions_instruction)):?>
                                <p><i><?=$injectedFormConditions->conditions_instruction?></i></p>

                            <?php endif;?>    
                            <p><b>Ce formulaire s'affiche pour le ou les actions qui répondent aux conditions suivantes:</b></p>
                           <?php //debug($injectedFormConditions->conditions);?>
                            <?=view("DataView\Views\injected-form-get-dataView",[
                                                    "validation"=>$validation,
                                                    "typeDataView"=>"form",
                                                    "fields"=>$fields,
                                                    "value_filtre"=>TRUE,
                                                    "value"=>$injectedForm,
                                                    "indexes"=>explode(",",trim($injectedFormConditions->conditions)),
                                                    "filtres_spip"=>$filtres_spip,
                                                    "has_spip_filtre"=>$has_spip_filtre,
                                                    
                                                    
                                                    
                                                    ])
                                                ?> 
                        </div>   
            </div>
        </div> 
        <!-- input hidden to declare update or insert -->
        <input type="hidden" value="<?=$id_injected_form?>" name="id_injected_form">
     
        
        <!--button save & Cancel for form -->
                               
    </form>
    <?php endif;?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#textarea_injected_form_header_text').summernote({
                // airMode: false,
                // toolbar: true,
                height: 150,
                // minHeight: null,
                // maxHeight: null,
                // focus: true,
                // tabsize: 2,
                placeholder: 'Type your text here ...',
                // fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
                // fontSizeUnits: ['px', 'pt'],
                // disableDragAndDrop: true
                // shortcuts: false,
                // tabDisable: false,
                // codeviewFilter: false,
                // codeviewIframeFilter: true,
                // disableGrammar: false,
                // dialogsInBody: true,
                // dialogsFade: true,
                toolbar: [
                    ['style', ['style']],
                    ['view', ['undo', 'redo']],
                    // ['font', ['bold', 'underline', 'clear', 'backcolor', 'forecolor']],
                    // ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    // ['table', ['table']],
                    // ['insert', ['link', 'picture', 'video', 'hr']],
                    ['insert', ['link', 'hr']],
                    // ['view', ['fullscreen', 'codeview', 'help']],
                    ['view', ['codeview']],
                    // ['HelloButton', ['hello']],
                    ['TagsButton', ['Tags']],
                ],
                // buttons: {hello: HelloButton},
            });
        });
</script>


<?php $this->endSection(); ?>