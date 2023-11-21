<div class="container-fluid py-2 d-flex justify-content-between">
    <div class="d-flex align-items-center">
        <div class="h5 mb-0"><?php echo $titleView; ?>
    
    </div>
        <?php if($typeDataView=="update"):?>
            <a class="modalView btn btn-sm btn-dark ms-2"
                data-view-title="Logs des modifications du contact n°<?=$contact->id_contact;?>"
                href="<?=base_url()?>historique/get_logs_fiche/contact/<?=$contact->id_contact?>"
                >
                <i class="<?=icon("log")?>"></i> Logs
            </a>
        <?php endif;?>
    </div>
    <div>
        <?php if($typeDataView=="read"):?>
        <?php elseif($typeDataView=="associe"):?>    
            <button id="btn_new_form"
                class="btn btn-success btn-sm"
                >
                <i class="<?=icon("save")?>"></i> Enregistrer
            </button>
            <a class="btn btn-danger btn-sm" 
                href="<?php echo base_url('contact')?>/fiche/<?=$contact->id_contact?>"
                >
                <i class="<?=icon("cancel")?>"></i> 
                Annuler
            </a>
        <?php elseif($typeDataView=="new_form"):?>
            <button id="btn_new_form" class="btn btn-success btn-sm">
                <i class="<?=icon("save")?>"></i> Enregistrer
            </button>          
            <a class="btn btn-danger btn-sm" href="<?php echo base_url('contact')?>">
                <i class="<?=icon("cancel")?>"></i> Annuler
            </a>
        <?php else:?>
            <?php if(in_array($typeDataView, ["update", "read"])):?>
                <a class="text-center btn btn-success btn-sm btn_new_contact_profil btn_contextuel_menu_form" href="">
                    Ajouter un contact de profil
                </a>
            <?php endif;?>
            <div class="zone-button-form zone_button_mode_form"
                <?php if(in_array($typeDataView, ["update", "read"])):?>style="display:none"<?php endif;?>
                >
                <?php if($typeDataView!="create"):?>
                    <button type="submit" class="btn btn-success btn-sm btn_save_save">
                        <i class="<?=icon("save")?>"></i> Enregistrer
                    </button>
                <?php else:?>    
                    <button type="submit" entity="contact" class="btn btn-success btn-sm btn_save_insert" >
                        <i class="<?=icon("save")?>"></i> Enregistrer
                    </button>
                <?php endif;?>
                <?php if(isset($contact->id_contact)):?>   
                    <a class="btn btn-danger btn-sm" 
                        href="<?php echo base_url('contact/fiche/' . $contact->id_contact)?>"
                        >
                        <i class="<?=icon("cancel")?>"></i> 
                        Annuler
                    </a>
                <?php endif;?>
            </div>
            <div style="display:none" class="zone-submit-loading">
                <i class="fas fa-circle-notch fa-spin"></i> Veuillez patientez
            </div>  
        <?php endif;?>
    </div>
</div>
<div style="background-color:#eeeeee!important"  class="container-fluid py-2 d-flex border-bottom-<?php echo $themes->contact->color;?>">
        <?php if(!empty($demande)): echo affiche_adresse_demande_from_sql($demande); endif;?>
        <?php if(!empty($demande)): echo affiche_adresse_bien_from_sql($demande); endif;?>
       
</div>