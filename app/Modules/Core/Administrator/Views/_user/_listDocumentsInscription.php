<?php $request = \Config\Services::request(); ?>

<?php 
    //on affiche le template si on appelle pas par Ajax
    if (!$request->isAJAX()) 
    {
        $this->extend('Layout\index');
        $this->section("body");
    }
?> 

<?php if(!empty($documents)):?>
    <ul class="list-group">
            <?php foreach($documents as $document):?>    
            <li class="list-group-item">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">
                            <div class="row">
                                <div class="col"> 
                                <i class="<?=icon("file-pdf")?> text-danger"></i> <?=$document->filename?>
                                </div>
                                <div class="col-4 text-right">
                                <a href="<?=base_url()?>/user/seeFile/<?=$document->filename?>"> Voir</a>
                                    <a class="m-2" href="<?=base_url()?>/user/getFile/<?=$document->filename?>"> Télécharger</a>
                                </div>
                            </div>
                        </div> 
                    </div>
            </li>
            <?php endforeach;?> 
    </ul>
<?php else:?>
    <p>Pas de documents disponibles!</p>    
<?php endif;?>

<?php 
    //on affiche le template si on appelle pas par Ajax
    if (!$request->isAJAX()) 
    {
        $this->endSection();
    }
?>    