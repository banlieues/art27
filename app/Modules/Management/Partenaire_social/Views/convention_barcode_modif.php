<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>

<?php $this->extend('\Partenaire_social\view-partenaire_social-base'); ?>

<?php $this->section('partenaire_social-body');?>

<div class="row mt-4 load_ajax m-1">


    <input id="id_partenaire_social" type="hidden" name="id_partenaire_social" value="<?=$id_partenaire_social?>">
    <input id="annee_select" type="hidden" name="annee" value="<?=$annee_select?>">

<div>   
            <div class="row">
                <?php foreach($mois_ligne as $name=>$m):?>
                    <?php $name_produit=$name.'_produit'; ?>
                    <div class="col-4">
                            <div class="m-2 card">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-12 text-center">
                                        <h5><b><?=$m?> <?=$annee_select?></b></h5>
                                        <hr>
                                    </div>
                                
                                    <div class="col-6">
                                        <i>Ce qui est prévu:</i> 
                                    </div>
                                    <div class="col-6">
                                        <input name="<?=$name?>" type="text" value="<?php if(isset($convention->$name)):?><?=$convention->$name?><?php endif?>">
                                    </div>
                                    <div class="col-6">
                                        <i>Ce qui est déjà produit:</i> 
                                    </div>
                                    <div class="col-6">
                                        <?php if(isset($convention->$name)):?><?=$convention->$name_produit?> <?php endif?>

                                    </div>


                                    
                                </div>
                                </div>
                            </div>

                    </div>
                <?php endforeach;?>
            </div>

</div>

<?php $this->endSection();?>

