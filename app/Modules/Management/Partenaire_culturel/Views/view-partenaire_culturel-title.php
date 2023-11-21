<!--block title -->
<div class="card-header p-1 p-xl-1 sticky_button bg-light">
    <div class="row">
        <div class="col-auto align-self-center">
            <h3 class="fs-4"><?php echo $titleView; ?></h3>
        </div>
        <div class="col align-self-center">
            <div class="text-end">
              
                <?php if($typeDataView=="read"):?>
                    <a 
                        class="btn btn-<?php echo $themes->partenaire_culturel->color;?> btn-sm" 
                        href="<?php echo base_url('partenaire_culturel/formpartenaire_culturel/' . $partenaire_culturel->id_partenaire_culturel);?>"
                        >
                        <?php echo $themes->partenaire_culturel->icon;?>
                        Modifier la fiche
                    </a>
                    <?php elseif($typeDataView=="associe"):?>
                        <button id="btn_new_form" class="btn btn-success btn-sm"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                      
                      <a 
                              class="btn btn-danger btn-sm" 
                              href="<?php echo base_url('partenaire_culturel')?>/fiche/<?=$partenaire_culturel->id_partenaire_culturel?>"
                              >
                              <i class="<?=icon("cancel")?>"></i> 
                              Annuler
                          </a>

                    <?php elseif($typeDataView=="new_form"):?>
                        
                        <button form="form_component_actif" class="btn btn-success btn-sm btn_save_save" ><i class="<?=icon("save")?>"></i> Enregistrer</button>


                      
                        <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('partenaire_culturel')?>"
                                >
                                <i class="<?=icon("cancel")?>"></i> 
                                Annuler
                            </a>
               
                <?php else:?>
                    <?php if(in_array($typeDataView, ["update", "read"])):?>
                    <?php endif;?>
                    <span <?php if(in_array($typeDataView, ["update", "read"])):?>style="display:none"<?php endif;?> class="zone-button-form zone_button_mode_form">
                        <?php if($typeDataView!="create"):?>
                            <button class="btn btn-success btn-sm btn_save_save" type="submit"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                        <?php else:?>    
                            <button entity="partenaire_culturel" class="btn btn-success btn-sm btn_save_insert" type="submit"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                        <?php endif;?>
                        <?php if($typeDataView=="update"):?>   
                            <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('partenaire_culturel/fiche/' . $partenaire_culturel->id_partenaire_culturel)?>"
                                >
                                <i class="<?=icon("cancel")?>"></i> 
                                Annuler
                            </a>
                        <?php else:?>
                            <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('modelisation')?>"
                                >
                                <?php echo $themes->partenaire_culturel->icon;?>
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
<?php if(isset($info_log)):?><?=$info_log?><?php endif;?>