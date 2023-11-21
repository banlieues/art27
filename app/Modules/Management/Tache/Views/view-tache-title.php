<!--block title -->
<div class="card-header p-1 p-xl-1 sticky_button bg-light">
    <div class="row">
        <div class="col-auto align-self-center">
            <h3 class="fs-4"><?php echo $titleView; ?>
            <?php if($typeDataView=="update"):?>
            <a class="modalView btn btn-dark" data-view-title="Logs des modifications du tache n°<?=$tache->id_tache;?>"  href="<?=base_url()?>historique/get_logs_fiche/tache/<?=$tache->id_tache?>"><i class="<?=icon("log")?>"></i> Logs</a>
            <?php endif;?>
        </h3>
        </div>
        <div class="col align-self-center">
            <div class="text-end">
              
                <?php if($typeDataView=="read_old"):?>
                    <a 
                        class="btn btn-<?php echo $themes->tache->color;?> btn-sm" 
                        href="<?php echo base_url('tache/formtache/' . $tache->id_tache);?>"
                        >
                        <?php echo $themes->tache->icon;?>
                        Modifier la fiche du groupe
                    </a>
                    <?php elseif($typeDataView=="associe"):?>
                        <button id="btn_new_form" class="btn btn-success btn-sm"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                      
                      <a 
                              class="btn btn-danger btn-sm" 
                              href="<?php echo base_url('tache')?>/fiche/<?=$tache->id_tache?>"
                              >
                              <i class="<?=icon("cancel")?>"></i> 
                              Annuler
                          </a>

                    <?php elseif($typeDataView=="new_form"||$typeDataView=="read"):?>
             
                        <button id="btn_new_form" class="bt_form_tache btn btn-success btn-sm"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                      
                        <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('tache')?>"
                                >
                                <i class="<?=icon("cancel")?>"></i> 
                                Annuler
                            </a>
               
                <?php else:?>
                    <?php if(in_array($typeDataView, ["update", "read"])):?>
                        <a class="text-center btn btn-<?php echo $themes->demande->color;?> btn-sm text-center btn_contextuel_menu_form" href="<?php base_url()?>/demande/new/bureau/0/0/0/<?=$tache->id_tache?>">Nouvelle demande</a>
                        <a class="text-center btn btn-<?php echo $themes->demande->color;?> btn-sm text-center btn_contextuel_menu_form" href="<?php base_url()?>/tache/associe_demande/<?=$tache->id_tache?>">Associer à une demande existante</a>
                    <?php endif;?>
                    <span <?php if(in_array($typeDataView, ["update", "read"])):?>style="display:none"<?php endif;?> class="zone-button-form zone_button_mode_form">
                        <?php if($typeDataView!="create"):?>
                            <button class="btn btn-success btn-sm btn_save_save" type="submit"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                        <?php else:?>    
                            <button entity="tache" class="btn btn-success btn-sm btn_save_insert" type="submit"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                        <?php endif;?>
                        <?php if($typeDataView=="update"):?>   
                            <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('tache/fiche/' . $tache->id_tache)?>"
                                >
                                <i class="<?=icon("cancel")?>"></i> 
                                Annuler
                            </a>
                        <?php endif;?>
                    </span>
                    <span style="display:none" class="zone-submit-loading"> <i class="fas fa-circle-notch fa-spin"></i> Veuillez patientez</span>  
                 
                <?php endif;?> 
            </div>  
        </div>      
    </div>
</div>