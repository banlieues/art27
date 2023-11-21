<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?=$themes->partenaire_social->color?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php if($autorisationManager->is_autorise("partenaire_social_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbPartenaire_socials_culturel?> partenaire<?=plurial_s($pager->getTotal())?> <?=plurial_social($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?=$themes->partenaire_social->color?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if($autorisationManager->is_autorise("partenaire_social_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-<?=$themes->partenaire_social->color?> btn-sm mt-1" href="<?=base_url()?>/partenaire_social/new">
                                    <?=$themes->partenaire_social->icon?> Ajouter un partenaire social
                                    </a>
                                </div>
                            <?php endif;?>

                        </div>
                    </form>
                </div> 

            <?php if($autorisationManager->is_autorise("partenaire_social_r")):?>                                
                <?php if ($nbPartenaire_socials_culturel>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->partenaire_social->color)?><?php endif;?>

                    <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($partenaire_socials_culturel as $partenaire_social):?>
                                <tr>
                                    <?php if($autorisationManager->is_autorise("partenaire_social_d")):?>
                                    <td>
                                          
                                        <button text_alert="le partenaire culturel   
                                          
                                        
                                                <?=$partenaire_social->id_partenaire_social?>
                                          " 
                                            id_delete="<?=$partenaire_social->id_partenaire_social?>" href="<?=base_url()?>/delete/deletePartenaire_culturel" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                          
                                    </td>
                                    <?php endif;?> 

                                  
                                    

                                    <td>
                                        <a class="btn btn-<?=$themes->partenaire_social->color?> btn-sm" href="<?=base_url()?>partenaire_social/fiche/<?=$partenaire_social->id_partenaire_social?>">
                                        <?=$themes->partenaire_social->icon?> <?=$partenaire_social->nom_partenaire_social;?> (n°<?=$partenaire_social->numero_partenaire_social?>)
                                        </a>
                                    </td>
                                    <td>
                                        <?=$partenaire_social->statut_partenaire_social?>
                                    </td>
                                    <td>
                                        <?php if(!empty(trim($partenaire_social->remarque_by_partenaire_social))):?>
                                            <a href="<?=base_url()?>/partenaire_social/get_remarque_by_partenaire_social/<?=$partenaire_social->id_partenaire_social?>" class="text-success modalView" data-view-title="Remarque(s) de <?=$partenaire_social->nom_partenaire_social?>"><i class="<?=icon("triangle_warning")?> text-danger"></i></a>
                                        <?php endif;?>
                                        <?php if(!empty(trim($partenaire_social->commentaire_by_art27_partenaire_social))):?>
                                            <a href="<?=base_url()?>/partenaire_social/get_commentaire_by_art27_partenaire_social/<?=$partenaire_social->id_partenaire_social?>" class="text-success modalView" data-view-title="Commentaire(s) à propos de <?=$partenaire_social->nom_partenaire_social?>"><i class="<?=icon("remarques")?> text-dark"></i></a>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <?=$partenaire_social->adresse_partenaire_social?>
                                    </td>
                                    <td>
                                        <?=$partenaire_social->code_postal?>
                                    </td>
                                    <td>
                                        <?=$partenaire_social->telephone_partenaire_social?>

                                    </td>
                                    <td>
                                        <?=$partenaire_social->gsm_partenaire_social?>
                                    </td>
                                    <td>
                                        <?=$partenaire_social->mail_partenaire_social?>
                                    </td>
                                    <td>
                                        <?php if(!empty($partenaire_social->web_partenaire_social)):?>
                                            <a class="text-dark" href="<?=url_web($partenaire_social->web_partenaire_social)?>" target="blank"><i class="<?=icon("website")?>"></i></a>
                                        <?php endif;?>

                                        <?php if(!empty($partenaire_social->facebook_partenaire_social)):?>
                                            <a class="text-dark" href="<?=url_web($partenaire_social->facebook_partenaire_social)?>" target="blank"><i class="<?=icon("facebook")?>"></i></a>
                                        <?php endif;?>

                                        <?php if(!empty($partenaire_social->instagram_partenaire_social)):?>
                                            <a class="text-dark"  href="<?=url_web($partenaire_social->instagram_partenaire_social)?>" target="blank"><i class="<?=icon("instagram")?>"></i></a>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <a class="btn btn-dark" href="<?=base_url()?>partenaire_social/convention_barcode/<?=$partenaire_social->id_partenaire_social?>/<?=date("Y")?>">Voir convention</a>
                                    </td>
                                   

                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->partenaire_social->color)?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de partenaires sociaux</h3></div>        
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

