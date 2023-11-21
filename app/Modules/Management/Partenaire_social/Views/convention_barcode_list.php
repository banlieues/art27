<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>

<?php $this->extend('\Partenaire_social\view-partenaire_social-base'); ?>

<?php $this->section('partenaire_social-body');?>

<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?=$themes->partenaire_social->color?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php if($autorisationManager->is_autorise("partenaire_social_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbBarcodes?> code barre<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?=$themes->partenaire_social->color?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php endif;?>

                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                 
                                </div>

                        </div>
                    </form>
                </div> 

            <?php if($autorisationManager->is_autorise("partenaire_social_r")):?>                                
                <?php if ($nbBarcodes>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->partenaire_social->color)?><?php endif;?>

                    <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list_BarCodes as $barCode):?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="">
                                    </td>
                                    <td>
                                        <?=$barCode->label_mois?>
                                    </td>
                                 
                                    <td>
                                    <?=$barCode->num_code?>
                                    </td>
                                    <td>
                                    
                                    <?php echo '<img width="100px" src="data:image/png;base64,' . $barCode->barcode. '">'; ?>
                                        
                                    </td>

           

                                    <td class="modif_container CSelectTypeDocument<?=$barCode->id_convention_barcode?> data_id_type_<?=$barCode->id_convention_barcode?>">


                                            <?php 
                                            echo view($module.'\form_barcode_statut', [
                                                    "barcode" => $barCode,
                                                    "statut"=>$statuts,
                                                    
                                                ]);
                                                ?>
  
                                    </td>

                                                             <td class="modif_container">

                                    <?php 
                                            echo view($module.'\form_barcode_commentaire', [
                                                    "barcode" => $barCode,
                                                  
                                                    
                                                ]);
                                                ?>
                                       
                                    </td>

                                   
                                    <td>
                                        <?=convert_date_en_to_fr_with_h($barCode->created_at)?>

                                    </td>
                                    <td>
                                        <?=$barCode->user_create?>

                                    </td>

                                    <td>
                                    <?php if(!empty($barCode->id_partenaire_culturel)):?>
                                            <a href="<?=base_url()?>partenaire_culturel/<?=$barCode->id_partenaire_culturel;?>" class="btn btn-success btn-small">
                                                <?=$barCode->nom_partenaire_culturel?>
                                            </a>
                                        <?php endif;?>

                                    </td>

                                    <td>
                                    <?php if(!empty($barCode->id_partenaire_culturel)):?>
                                        <a class="my-lightbox-toggle" data-toggle="lightbox" data-gallery="gallery-images" href="<?=base_url()?>tickets/individuel/<?=$barCode->url_file?>">
                                        <img width="100px" src="<?=base_url()?>tickets/individuel/<?=$barCode->url_file?>">
                                    </a>
                                    <?php endif;?>

                                    </td>

                                   
                             
                                   

                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->partenaire_social->color)?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de Codes Barres</h3></div>        
                <?php endif;?>  
                <?php else:?>
                    <div class="text-center m-5">
                        <b>Vous n'avez pas l'autorisation pour accèder à ce contenu!</b>
                    </div>
            <?php endif;//autorisation?>  
        </div>        
    </div>

</div>    


    
<?php $this->endSection();?>