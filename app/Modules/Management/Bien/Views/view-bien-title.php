<div class="container-fluid py-2">
    <div class="d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <div class="h5 mb-0"><?php echo $titleView; ?></div>
            <?php if($typeDataView=="update"):?>
                <a class="modalView btn btn-dark btn-sm ms-2"
                    data-view-title="Logs des modifications du bien n°<?=$bien->id_bien;?>"
                    href="<?=base_url()?>historique/get_logs_fiche/bien/<?=$bien->id_bien?>"
                    >
                    <i class="<?=icon("log")?>"></i> Logs
                </a>
            <?php endif;?>
        </div>
        <div>
            <?php if($typeDataView=="read"):?>
                <a class="btn btn-<?php echo $themes->bien->color;?> btn-sm" href="<?php echo base_url('bien/formbien/' . $bien->id_bien);?>">
                    <?php echo $themes->bien->icon;?> Modifier la fiche du groupe
                </a>
            <?php elseif($typeDataView=="associe"):?>
                <button id="btn_new_form" class="btn btn-success btn-sm">
                    <i class="<?=icon("save")?>"></i> Enregistrer
                </button>
                <a class="btn btn-danger btn-sm" href="<?php echo base_url('bien')?>/fiche/<?=$bien->id_bien?>" >
                    <i class="<?=icon("cancel")?>"></i> Annuler
                </a>
            <?php elseif($typeDataView=="new_form"):?>
                <button id="btn_new_form" class="btn btn-success btn-sm">
                    <i class="<?=icon("save")?>"></i> Enregistrer
                </button>
                <a class="btn btn-danger btn-sm" href="<?php echo base_url('bien')?>" >
                    <i class="<?=icon("cancel")?>"></i> 
                    Annuler
                </a>
            <?php else:?>
                <?php if(in_array($typeDataView, ["update", "read"])):?>
                    <a class="text-center btn btn-<?php echo $themes->demande->color;?> btn-sm text-center btn_contextuel_menu_form"
                        href="<?php base_url()?>/demande/new/bureau/0/0/0/<?=$bien->id_bien?>"
                        >
                        Nouvelle demande
                    </a>
                    <a class="text-center btn btn-<?php echo $themes->demande->color;?> btn-sm text-center btn_contextuel_menu_form"
                        href="<?php base_url()?>/bien/associe_demande/<?=$bien->id_bien?>"
                        >
                        Associer à une demande existante
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
                        <button type="submit" entity="bien" class="btn btn-success btn-sm btn_save_insert">
                            <i class="<?=icon("save")?>"></i> Enregistrer
                        </button>
                    <?php endif;?>
                    <?php if($typeDataView=="update"):?>   
                        <a class="btn btn-danger btn-sm" 
                            href="<?php echo base_url('bien/fiche/' . $bien->id_bien)?>"
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
</div>
<div style="background-color:#eeeeee!important" class="container-fluid py-2 d-flex border-bottom-<?php echo $themes->bien->color;?>">
        <?php if(!empty($demande)): echo affiche_adresse_demande_from_sql($demande); endif;?>
        <?php if(!empty($demande)): echo affiche_adresse_contact_from_sql($demande); endif;?>
       
</div>