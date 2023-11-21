<?php $this->extend('templates/index'); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-yellow">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <div class="col-lg-auto p-1 align-self-center">
                                <h5 class='card-title mb-0'><?=$nbLieux?> lieu<?=plurial_x($pager->getTotal())?></h5>
                            </div>
                            <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                <div class="input-group input-group-navbar text-lg-end">
                                    <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                    <button class="btn btn-amethyst text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                <a class="btn btn-yellow btn-sm mt-1" href="<?=base_url()?>/lieu/formlieu">
                                    <i class="<?=icon("lieu")?>"></i> Ajouter un lieu
                                </a>
                            </div>
                        </div>
                    </form>
                </div> 
            <?php if ($nbLieux>0): ?>
                <div class="table-responsive"> 
                    <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                        <thead>
                            <tr>
                                <?=$getTh?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($lieux as $lieu):?>
                            <tr <?php if($lieu->is_actif<>1):?> style="opacity: 1" class="table-danger" <?php endif;?>>
                                <td>
                                    <button text_alert="le lieu   
                                        
                                            <?=$lieu->titre_lieu?>
                                        " 
                                        id_delete="<?=$lieu->id_lieu?>" href="<?=base_url()?>/delete/deleteLieu" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> 
                                    </button>
                                </td>
                               
                                <td>
                                    <a class="btn btn-sm btn-yellow text-wrap text-white" href="<?=base_url("lieu/viewlieu/$lieu->id_lieu")?>"><i class="<?=icon("lieu")?>"></i> 
                                       <?=$lieu->titre_lieu?>
                                    </a>
                                </td>
                                <td>
                                    <?=$lieu->actif_lieu?>
                                </td>
                                <td><?=$lieu->descriptif_lieu?></td>
                                <td><?=$lieu->adresse_lieu?></td>

                  
                            </tr>
                        <?php endforeach;?>
                        </tbody>   
                    </table>
                </div>   
                <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_amethyst")?><?php endif;?>
            <?php else:?>
                <div class="text-center m-5"><h3>Je n'ai pas trouvé de lieux</h3></div>        
            <?php endif;?>    
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

