<!--block title -->
<?php $dataview = \Config\Services::dataViewConstructor();?>

<div class="container-fluid d-flex justify-content-between">
    <div class="d-flex align-items-center">
        <div class="h5 py-2 mb-0"><?php echo $titleView; ?>
       
        </div>
     
        <?php if(in_array($typeDataView, ["update", "read"])):?>
            <a class="modalView btn btn-dark btn-sm ms-2"
                data-view-title="Historique de la demande n°<?=$demande->id_demande?>"
                href="<?=base_url()?>historique/get_historique_demande/<?=$demande->id_demande?>"
                >
                <i class="<?=icon("historique")?>"></i>
                Historique
            </a>
            <a class="modalView btn btn-dark btn-sm ms-2"
                data-view-title="Logs des modifications de la demande n°<?=$demande->id_demande;?>"
                href="<?=base_url()?>historique/get_logs_fiche/demande/<?=$demande->id_demande?>"
                >
                <i class="<?=icon("log")?>"></i>
                Logs
            </a>
        <?php endif;?>
        <?php if(in_array($typeDataView, ["update", "read"])):?>
            <div id="cChangeSelectStatut<?=$demande->id_demande?>" class="ms-2">
                <?php 
                    $data["statut_demandes"]=$statut_demande;
                    $data["demande"]=$demande;
                    echo view("Demande\get_statut_demande_select",$data);
                ?>
            </div>
        <?php endif;?>
      
    </div>
    <div class="d-flex align-items-center">
        <?php if($typeDataView!="read"):?>
            <div class="zone-button-form zone_button_mode_form"
                <?php if(in_array($typeDataView, ["update", "read"])):?>style="display:none"<?php endif;?>
                >
                <?php if($typeDataView!="create"):?>
                    <button class="btn btn-success btn-sm btn_save_save" type="submit">
                        <i class="<?=icon("save")?>"></i>
                        Enregistrer
                    </button>
                <?php else:?>    
                    <button type="submit"
                        entity="demande"
                        class="btn btn-success btn-sm btn_save_insert"
                        >
                        <i class="<?=icon("save")?>"></i>
                        Enregistrer
                    </button>
                <?php endif;?>
                <?php if($typeDataView=="update"):?>   
                    <a class="btn btn-danger btn-sm" 
                        href="<?php echo base_url('demande/fiche/' . $demande->id_demande)?>"
                        >
                        <i class="<?=icon("cancel")?>"></i> 
                        Annuler
                    </a>
                <?php endif;?>
            </div>
            <div class="zone-submit-loading" style="display:none">
                <i class="fas fa-circle-notch fa-spin"></i>
                Veuillez patientez
            </div>  
            <?php if(in_array($typeDataView, ["update", "read"])):?>
                <div class="zone-button-form-rdv"
                    <?php if(is_null($oneRdv)):?>style="display:none"<?php endif;?>
                    >
                    <button class="bt_form_rdv btn btn-success btn-sm">
                        Enregistrer le rendez-vous
                    </button>
                    <button id="rdv_new_cancel" class="btn btn-danger btn-sm">
                        Fermer le volet rendez-vous
                    </button>
                </div>
            <?php endif;?>
            <?php if(in_array($typeDataView, ["update", "read"])):?>
                <div class="zone-button-form-tache"
                    <?php if(is_null($oneTache)):?>style="display:none"<?php endif;?>
                    >
                    <button class="bt_form_tache btn btn-success btn-sm">
                        Enregistrer la tâche
                    </button>
                    <button id="tache_new_cancel" class="btn btn-danger btn-sm">
                        Fermer le volet tâche
                    </button>
                </div>
            <?php endif;?>
            <?php if(in_array($typeDataView, ["update", "read"])):?>
                <div class="zone-button-form-message" style="display:none">
                    <button id="btn_form_send_message" class="btn btn-success btn-sm">
                        Envoyer le message
                    </button>
                    <button id="btn_form_send_message_brouillon" class="btn btn-warning btn-sm">
                        Enregistrer comme brouillon
                    </button>
                    <button id="message_new_cancel" class="btn btn-danger btn-sm">
                        Annuler message
                    </button>
                </div>
            <?php endif;?>
            <?php if(in_array($typeDataView, ["update", "read"])):?>
                <div class="zone-button-form-note" style="display:none">
                    <button id="bt_form_note" class="btn btn-success btn-sm">
                        Enregistrer la note
                    </button>
                    <button id="note_new_cancel" class="btn btn-danger btn-sm">
                        Annuler
                    </button>
                </div>
            <?php endif;?>
        <?php endif;?> 
    </div>
</div>

<div style="background-color:#eeeeee!important" class="container-fluid py-2 d-flex border-bottom-<?php echo $themes->demande->color;?>">
        <?php if(!empty($contact)): echo affiche_adresse_contact_from_sql($contact); endif;?>
        <?php if(!empty($bien)): echo affiche_adresse_bien_from_sql($bien); endif;?>

</div>