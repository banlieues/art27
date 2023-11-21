<!-- container note -->
<div style=""  id="liste_note" class="card<?php if($entity=="demande"):?> mt-2<?php endif;?> mb-2">
    <div class="card-header border-top-dark">
        <h5 class="card-title d-flex justify-content-between align-items-center"><i class="<?=icon("sticky-note-empty")?>"></i> Notes
        <a style="text-decoration:none; color:black!important" href="<?=base_url()?>messagerie/get_note_of_entity/<?=$entity?>/<?=$id_entity?>" class="modalView" data-view-title="Notes Internes"><i class="far fa-window-restore"></i></a>

           
              
                <div class="btn-group btn_contextuel_menu_form">
                    <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="<?=icon("menu_contextuel")?>"></i>
                    </span>
                    
                    <ul class="dropdown-menu">
                        <?php if($entity=="demande"):?>
                            <li><a href="<?=base_url()?>messagerie/form_note/<?=$entity?>/<?=$id_entity?>" class="dropdown-item" id="ajouter_note">Ajouter une note</a></li>
                        <?php else:?>
                            <li><a href="<?=base_url()?>messagerie/form_note/<?=$entity?>/<?=$id_entity?>" data-view-title="Créer une note pour le <?=$entity?> n°<?=$id_entity?>" class="modalView dropdown-item">Ajouter une note</a></li>

                        <?php endif;?>

                    
                    
                    </ul>
                </div>
           
    </div>

    <div style="max-height: 30vh !important; overflow: auto" class="card-body">  
        <div class="loading text-center mt-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
        <div id="container_note" href_ajax="<?=base_url()?>messagerie/get_note_of_entity/<?=$entity?>/<?=$id_entity?>" style="display:none" class="loader" ></div>
    </div>

    <div style="display:none" id="provisoire_note">
        <form id="form_id_lu"><?=$notes_non_lues?>  </form>
                          
    </div>

    <div class="card-footer text-center">

          
                    <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                    <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>


    </div>


    
</div>
<!-- fin container note -->
<?php echo view("Messagerie\js_messagerie",["notes_non_lues"=>$notes_non_lues]); ?>