<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
    <div class="card border-top-amethyst">
                <div class="card-header sticky_button bg-light">
                    <h3> Associer le document n°<?=($document->id_document)?> <a class="text-<?php echo $themes->document->color;?>" href="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>">
                                                       
                                                       <?=($document->name)?>
                                               </a> à une demande</h3>
                    <form class="form_with_order">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("contact_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'>
                                    <?php if(is_image($document->url_file)):?>
                                                <img width="50px" src="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>" class="" alt="<?=$document->name?>">

                                            <?php else:?>
                                                <i class="fas fa-file fa-3x text-<?php echo $themes->document->color;?>"></i> 
                                            <?php endif;?> 

                                    <?=$nbDemandes?> demande<?=plurial_s($pager->getTotal())?>
                                        <select class="select_submit" name="statut_demande" >
                                            <option value="0">Tous les statuts</option>
                                            <?php foreach($statut_demandes as $statut):?>
                                                <option <?php if($id_statut_demande==$statut->id):?>selected<?php endif;?> value="<?=$statut->id?>"><?=$statut->label?></option>
                                            <?php endforeach;?>    
                                        </select>
                                        <input <?php if($mes_demandes==1):?>checked<?php endif;?>  class="select_submit" name="mes_demandes" value="1" type="checkbox"> Mes demandes
                                        <input <?php if($homegrade==1):?>checked<?php endif;?>  class="select_submit" name="homegrade" value="1" type="checkbox"> Homegrade
                                        
                                        
                                    </h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher demande" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-amethyst text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                            <?php //if($autorisationManager->is_autorise("contact_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                <button id="bt_associe_demande" class="btn btn-amethyst btn-sm mt-1">
                                <?php echo $themes->demande->icon;?> Associer à une demande
                            </button>
                            </div>
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 
               
            <?php if ($nbDemandes>0): ?>
                <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_amethyst")?><?php endif;?>
                <div class="table-responsive"> 
                    <form id="form_associe_demande" action="<?=base_url()?>/document/associe_demande/<?=$document->id_document?>">

                    <table id="table_data" class="table table-striped table-hover my-0 table-sm">
                        <thead>
                        <tr>
                                    <?=$getTh?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($demandes as $demande):?>
                            <tr
                            <?php if($demande->id_demande_statut==6):?> class="table-light"<?php endif;?>
                                <?php if($demande->id_demande_statut==1):?> class="table-success"<?php endif;?>
                            >
                                <td><input name="id_demande" value="<?=$demande->id_demande?>" type="radio"></td>
                                <td><?=convert_date_en_to_fr_with_h($demande->date,FALSE)?></td>
                                <td><a href="<?=base_url("demande/fiche/$demande->id_demande")?>" class="btn btn-amethyst btn-sm text-white"><?php echo $themes->demande->icon;?> N°<?=$demande->id_demande?></a></td>
                                <td><?=$demande->type?></td>
                                <td ><?=$demande->statut?></td>
                                <td><?=$demande->prenom_createur?> <?=$demande->nom_createur?></td>
                                <td><?=$demande->prenom_encharge?> <?=$demande->nom_encharge?> <?php //$demande->id_utilisateur?></td>
                                <td><?=$demande->sujet?></td>
                                <td>
                                <?php echo affiche_adresse_contact($demande->contact_associee)?>

                                          
                                </td>
                                <td>
                                <?php echo affiche_adresse_bien($demande->bien_associe)?>

                                   

                                </td>
                                
                            </tr>
                        <?php endforeach;?>
                        </tbody>   
                    </table>
                    </form>
                </div>   
                <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_amethyst")?><?php endif;?>
            <?php else:?>
                <div class="text-center m-5"><h3>Je n'ai pas trouvé de demandes</h3></div>        
            <?php endif;?>    
        </div>        
    </div>

</div>    

<script>
    jQuery(document).ready(function()
    {
        $(document).on("click","#bt_associe_demande",function() {

            $("#form_associe_demande").submit();
            return false;
        })

    });
</script>

<?php $this->endSection(); ?>

