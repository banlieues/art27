<!-- container document -->
<?php if($typeDataView=="read"):?>
 
    <div class="card mb-4">
        <div class="card-header border-top-<?=$themes->document->color?>">
            <h5 class="card-title d-flex justify-content-between align-items-center">
                <span class="text-<?=$themes->document->color?>"><?=$themes->document->icon?></span>
                Documents liés
                <a id_entity="<?=$id_entity?>" entity="<?=$entity?>" href="#" class="gerer_document_modal text-dark">
                    <i class="far fa-window-restore"></i>
                </a>
                <?php if($typeDataView=="read"):?>  
                    <div class="btn-group btn_contextuel_menu_form_document">
                        <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="<?=icon("menu_contextuel")?>"></i>
                        </span>
                        <ul class="dropdown-menu">
                            <li><a  id_entity="<?=$id_entity?>"  entity="<?=$entity?>" href="#" class="dropdown-item gerer_document_modal">Gérer les documents de la fiche</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a  id_entity="<?=$id_entity?>" entity="<?=$entity?>" href="#" class="dropdown-item ajouter_document_modal">Uploader des documents dans le CRM</a></li>
                            <li style="display:none"><hr class="dropdown-divider"></li>
                            <li style="display:none"><a  id_entity="<?=$id_entity?>" entity="<?=$entity?>" href="#" class="dropdown-item ajouter_document_modal_crm">Ajouter des documents du CRM à cette fiche</a></li>
                        </ul>
                    </div>
                <?php endif;?>
            </h5>
        </div>
        <div class="card-body" style="height: 30vh !important; overflow: auto">  
            <div class="loading text-center mt-2"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
            <div id="container_document" href_ajax="<?=base_url()?>document/listes_documents_entity/<?=$entity?>/<?=$id_entity?>" style="display:none" class="loader" ></div>
        </div>
        <div class="card-footer text-center">
            <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
            <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>
        </div>
    </div>  
<?php endif;?> 

<!-- fin containr document -->