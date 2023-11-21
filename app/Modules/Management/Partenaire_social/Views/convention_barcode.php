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
                                        <?php if(isset($convention->$name)):?><?=$convention->$name?><?php endif?>
                                    </div>
                                    <div class="col-6">
                                        <i>Ce qui est déjà produit:</i> 
                                    </div>
                                    <div class="col-6">
                                        <?php if(isset($convention->$name)):?><a href="<?=base_url()?>partenaire_social/barcode_list/<?=$id_partenaire_social?>/<?=$annee_select?>/<?=$name?>" class="btn btn-sm btn-dark"><?=$convention->$name_produit?></a> <?php endif?>

                                    </div>


                                    <div class="col-12">
                                        <hr>
                                        <i>Produire code barre</i>
                                            <?php if(isset($convention->$name)&&$convention->$name>0):?>
                                                <form class="mb-2" action="<?=base_url()?>partenaire_social/barcode_generator" method="post">
                                                    <input type="hidden" name="id_partenaire_social" value="<?=$id_partenaire_social?>">
                                                    <input type="hidden" name="number_barre" value="<?=$convention->$name?>">
                                                    <input type="hidden" name="annee_select" value="<?=$annee_select?>">
                                                    <input type="hidden" name="mois_select" value="<?=$m?>">
                                                    <input type="hidden" name="mois_select_sql" value="<?=$name?>">
                                                    <?php if(session()->get("a_partir_de")): $a_partir_de=session()->get("a_partir_de"); else: $a_partir_de=0; endif; ?>
                                                    <input type="hidden" name="a_partir_de" value="<?=$a_partir_de?>">

                                        
                        
                                                    <?php $rest=$convention->$name-$convention->$name_produit; if($rest<0): $rest=0; endif?>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <input type="text" name="nb_produire" value="<?=$rest?>">
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="submit" class="btn btn-sm btn-dark">Produire Code Barre</button>
                                                        </div>
                                                    </div>
                                        
                                                </form>
                                            <?php endif;?>
                                    </div>
                                </div>
                                </div>
                            </div>

                    </div>
                <?php endforeach;?>
            </div>

</div>

    


<?php $this->endSection();?>