<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?=$themes->partenaire_culturel->color?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php if($autorisationManager->is_autorise("partenaire_culturel_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbPartenaire_culturels_culturel?> partenaire<?=plurial_s($pager->getTotal())?> culturel<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?=$themes->partenaire_culturel->color?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if($autorisationManager->is_autorise("partenaire_culturel_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-<?=$themes->partenaire_culturel->color?> btn-sm mt-1" href="<?=base_url()?>/partenaire_culturel/new">
                                    <?=$themes->partenaire_culturel->icon?> Ajouter un partenaire culturel
                                    </a>
                                </div>
                            <?php endif;?>

                        </div>
                    </form>
                </div> 
            <?php if($autorisationManager->is_autorise("partenaire_culturel_r")):?>                                
                <?php if ($nbPartenaire_culturels_culturel>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->partenaire_culturel->color)?><?php endif;?>

                    <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($partenaire_culturels_culturel as $partenaire_culturel):?>
                                <tr>
                                    <?php if($autorisationManager->is_autorise("partenaire_culturel_d")):?>
                                    <td>
                                          
                                        <button text_alert="le partenaire culturel   
                                          
                                        
                                                <?=$partenaire_culturel->id_partenaire_culturel?>
                                          " 
                                            id_delete="<?=$partenaire_culturel->id_partenaire_culturel?>" href="<?=base_url()?>/delete/deletePartenaire_culturel" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                          
                                    </td>
                                    <?php endif;?> 

                                  
                                    

                                    <td>
                                        <a class="btn btn-<?=$themes->partenaire_culturel->color?> btn-sm" href="<?=base_url()?>partenaire_culturel/fiche/<?=$partenaire_culturel->id_partenaire_culturel?>">
                                        <?=$themes->partenaire_culturel->icon?> <?=$partenaire_culturel->nom_partenaire_culturel;?> (n°<?=$partenaire_culturel->numero_partenaire_culturel?>)
                                        </a>
                                    </td>
                                    <td>
                                        <?php if(!empty(trim($partenaire_culturel->remarque_by_partenaire_culturel))):?>
                                            <a href="<?=base_url()?>/partenaire_culturel/get_remarque_by_partenaire_culturel/<?=$partenaire_culturel->id_partenaire_culturel?>" class="text-success modalView" data-view-title="Remarque(s) de <?=$partenaire_culturel->nom_partenaire_culturel?>"><i class="<?=icon("triangle_warning")?> text-danger"></i></a>
                                        <?php endif;?>
                                        <?php if(!empty(trim($partenaire_culturel->commentaire_by_art27_partenaire_culturel))):?>
                                            <a href="<?=base_url()?>/partenaire_culturel/get_commentaire_by_art27_partenaire_culturel/<?=$partenaire_culturel->id_partenaire_culturel?>" class="text-success modalView" data-view-title="Commentaire(s) à propos de <?=$partenaire_culturel->nom_partenaire_culturel?>"><i class="<?=icon("remarques")?> text-dark"></i></a>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <?=$partenaire_culturel->adresse_partenaire_culturel?>
                                    </td>
                                    <td>
                                        <?=$partenaire_culturel->code_postal?>
                                    </td>
                                    <td>
                                        <?=$partenaire_culturel->telephone_partenaire_culturel?>

                                    </td>
                                    <td>
                                        <?=$partenaire_culturel->gsm_partenaire_culturel?>
                                    </td>
                                    <td>
                                        <?=$partenaire_culturel->mail_partenaire_culturel?>
                                    </td>
                                    <td>
                                        <?php if(!empty($partenaire_culturel->web_partenaire_culturel)):?>
                                            <a class="text-dark" href="<?=url_web($partenaire_culturel->web_partenaire_culturel)?>" target="blank"><i class="<?=icon("website")?>"></i></a>
                                        <?php endif;?>

                                        <?php if(!empty($partenaire_culturel->facebook_partenaire_culturel)):?>
                                            <a class="text-dark" href="<?=url_web($partenaire_culturel->facebook_partenaire_culturel)?>" target="blank"><i class="<?=icon("facebook")?>"></i></a>
                                        <?php endif;?>

                                        <?php if(!empty($partenaire_culturel->instagram_partenaire_culturel)):?>
                                            <a class="text-dark"  href="<?=url_web($partenaire_culturel->instagram_partenaire_culturel)?>" target="blank"><i class="<?=icon("instagram")?>"></i></a>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <a class="btn btn-dark btn-small" href="<?=base_url()?>ticket/listTicket/<?=$partenaire_culturel->id_partenaire_culturel?>/<?=date("Y")?>">Gestion tickets</a>
                                    </td>
                                   

                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->partenaire_culturel->color)?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de partenaires culturels</h3></div>        
                <?php endif;?>  
                <?php else:?>
                    <div class="text-center m-5">
                        <b>Vous n'avez pas l'autorisation pour accèder à ce contenu!</b>
                    </div>
            <?php endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

