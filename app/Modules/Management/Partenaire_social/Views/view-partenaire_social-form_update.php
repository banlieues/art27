<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>

<?php $this->extend('\Partenaire_social\view-partenaire_social-base'); ?>

<?php $this->section('partenaire_social-body');?>
<form id="form_component_actif" class="form_component_actif" method="post" action="<?=base_url()?>/partenaire_social/save" >

<div class="row mb-2 load_ajax">


<?php $nb_columm=2; ?>
<?php for($i=1;$i<=$nb_columm;$i++): //creer les columm, par défaut on affiche 2 colonnes?>
    <!-- Columm -->
    <div id="column<?=$i?>" num_column="<?=$i?>" class="col-lg-<?=12/$nb_columm?>">
        <!-- on boucle sur les différenrs component -->
        <?php foreach($components as $component):?>
            <!-- on affiche sir le compenent est dans la colonne -->
            <?php if($component->column==$i):?>

                    <?php //récupére les data à afficher
                        $datas_value=$component->type;         
                    ?>

                  
                        <?=view("DataView\Views\card/".$component->card,
                                    [
                                        "component"=>$component,
                                        "data"=>$$datas_value,
                                        "validation"=>$validation,
                                        "fields"=>$fields,
                                        "typeDataView"=>$typeDataView
                                    ] );
                        ?>
                        <input type="hidden" value="<?=$id_partenaire_social?>" name="id_partenaire_social">
                
            <?php endif;?>
            <!-- fin  affiche sir le compenent est dans la colonne -->
        <?php endforeach;?>
        <!-- fin de la boucle sur les différenrs component -->
    </div>
    <!-- Columm -->
<?php endfor;?>

</div>


<?php $this->endSection();?>
