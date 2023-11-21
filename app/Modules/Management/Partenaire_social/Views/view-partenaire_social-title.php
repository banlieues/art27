<!--block title -->
<div class="card-header p-1 p-xl-1 sticky_button bg-light">
    <div class="row">
        <div class="col-auto align-self-center">
            <h3 class="fs-4"><?php echo $titleView; ?>
        
            <?php if(($typeDataView=="update"||$typeDataView=="read")&&$tab=="fiche"):?>
                <a class="modalView btn btn-sm btn-dark ms-2"
                    data-view-title="Logs des modifications du partenaire social n°<?=$partenaire_social->id_partenaire_social;?>"
                    href="<?=base_url()?>historique/get_logs_fiche/partenaire_social/<?=$partenaire_social->id_partenaire_social?>"
                    >
                    <i class="<?=icon("log")?>"></i> Logs
            
                </a>
            <?php endif;?>

            <?php if($tab=="convention"&&$typeDataView=="read"):?>
            <small>
                <?php $annee_bottom="2011"; ?>
                <b> - Convention </b>
                <select id="select_annee">
                        <?php for($annee=date("Y");$annee>=$annee_bottom;$annee--):?>
                            <option value="<?=base_url()?>partenaire_social/convention_barcode/<?=$id_partenaire_social?>/<?=$annee?>" <?php if($annee_select==$annee):?>selected<?php endif;?>><?=$annee?></option>
                        <?php endfor;?>
                </select>   
            </small>
            <?php endif;?>
       

        </h3>
        </div>
        
        <div class="col align-self-center">
            <div class="text-end">
                <?php if($typeDataView=="read"&&$tab=="fiche"):?>
                    <a 
                        class="btn btn-<?php echo $themes->partenaire_social->color;?> btn-sm" 
                        href="<?php echo base_url('partenaire_social/modif/' . $partenaire_social->id_partenaire_social);?>"
                        >
                        <?php echo $themes->partenaire_social->icon;?>
                        Modifier la fiche
                    </a>
                    <?php elseif($typeDataView=="associe"&&$tab=="fiche"):?>
                        <button id="btn_new_form" class="btn btn-success btn-sm"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                      
                      <a 
                              class="btn btn-danger btn-sm" 
                              href="<?php echo base_url('partenaire_social')?>/fiche/<?=$partenaire_social->id_partenaire_social?>"
                              >
                              <i class="<?=icon("cancel")?>"></i> 
                              Annuler
                          </a>

                    <?php elseif($typeDataView=="create"&&$tab=="fiche"):?>
                        
                        <button form="form_component_actif" class="btn btn-success btn-sm btn_save_save" ><i class="<?=icon("save")?>"></i> Enregistrer</button>


                      
                        <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('partenaire_social')?>"
                                >
                                <i class="<?=icon("cancel")?>"></i> 
                                Annuler
                            </a>
                    <?php elseif($typeDataView=="read"&&$tab=="convention"):?>
                        <a 
                        class="btn btn-<?php echo $themes->partenaire_social->color;?> btn-sm" 
                        href="<?php echo base_url('partenaire_social/convention_barcode_modif/' . $partenaire_social->id_partenaire_social.'/'.$annee_select);?>"
                        >
                        <?php echo $themes->partenaire_social->icon;?>
                            Modifier les informations de la Convention
                    </a>

                    <?php elseif($typeDataView=="print"&&$tab=="convention"):?>
                        <a 
                        class="btn btn-<?php echo $themes->partenaire_social->color;?> btn-sm" 
                        href="<?php echo base_url('partenaire_social/convention_barcode_modif/' . $partenaire_social->id_partenaire_social.'/'.$annee_select);?>"
                        >
                        <?php echo $themes->partenaire_social->icon;?>
                           Produire PDF
                    </a>

                    <a 
                              class="btn btn-success btn-sm" 
                              href="<?php echo base_url('partenaire_social')?>/convention_barcode/<?=$partenaire_social->id_partenaire_social?>/<?=$annee_select?>"
                              >
                              <i class="<?=icon("cancel")?>"></i> 
                              Retour convention
                          </a>

                    <?php elseif($typeDataView=="list"&&$tab=="convention"):?>
                      

                    <a 
                              class="btn btn-success btn-sm" 
                              href="<?php echo base_url('partenaire_social')?>/convention_barcode/<?=$partenaire_social->id_partenaire_social?>/<?=$annee_select?>"
                              >
                              <i class="<?=icon("cancel")?>"></i> 
                              Retour convention
                          </a>

                    <?php elseif($typeDataView=="update"&&$tab=="convention"):?>
                        <button id="btn_new_form" class="btn btn-success btn-sm"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                      
                      <a 
                              class="btn btn-danger btn-sm" 
                              href="<?php echo base_url('partenaire_social')?>/convention_barcode/<?=$partenaire_social->id_partenaire_social?>/<?=$annee_select?>"
                              >
                              <i class="<?=icon("cancel")?>"></i> 
                              Annuler
                          </a>

               
                <?php else:?>
                  
                    <span <?php if(in_array($typeDataView, ["read"])):?>style="display:none"<?php endif;?> class="zone-button-form zone_button_mode_form">
                        <?php if($typeDataView!="create"):?>
                            <button class="btn btn-success btn-sm" type="submit"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                        <?php else:?>    
                            <button entity="partenaire_social" class="btn btn-success btn-sm" type="submit"><i class="<?=icon("save")?>"></i> Enregistrer</button>
                        <?php endif;?>
                        <?php if($typeDataView=="update"):?>   
                            <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('partenaire_social/fiche/' . $partenaire_social->id_partenaire_social)?>"
                                >
                                <i class="<?=icon("cancel")?>"></i> 
                                Annuler
                            </a>
                        <?php else:?>
                            <a 
                                class="btn btn-danger btn-sm" 
                                href="<?php echo base_url('modelisation')?>"
                                >
                                <?php echo $themes->partenaire_social->icon;?>
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