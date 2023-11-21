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
                                <div class="col-1"> 
                                    <?php if(in_array($document->id_template_document,$documents_create)):?>
                                        <i class="fa fa-check"></i>
                                    <?php endif;?>    
                                    
                                </div> 
                                <div class="col">   
                                    <i class="<?=icon("file-pdf")?> text-danger"></i> <?=$document->titre?>
                                </div>
                                <div class="col-4 text-right">
                                <?php if(in_array($document->id_template_document,$documents_create)):?>
                                    <a href="<?=base_url()?>/documentsgenerator/getFile/<?=$document->id_template_document?>/<?=$id_activity?>/<?=$id_contact?>/<?=$id_inscription?>"><i class="<?=icon("download")?> text-danger"></i> Télécharger</a>
                                    <a class="m-1" href="<?=base_url()?>/documentsgenerator/getpdfmailNoCreate/<?=$document->id_template_document?>/<?=$id_activity?>/<?=$id_contact?>/<?=$id_inscription?>"><i class="<?=icon("send_email")?> text-danger"></i> Envoyer</a>
                                <?php else:?>
                                    <a href="<?=base_url()?>/documentsgenerator/getpdf/<?=$document->id_template_document?>/<?=$id_activity?>/<?=$id_contact?>/<?=$id_inscription?>"><i class="<?=icon("download")?> text-danger"></i> Télécharger</a>
                                    <a class="m-1" href="<?=base_url()?>/documentsgenerator/getpdfmail/<?=$document->id_template_document?>/<?=$id_activity?>/<?=$id_contact?>/<?=$id_inscription?>"><i class="<?=icon("send_email")?> text-danger"></i> Envoyer</a>

                                 <?php endif;?>   
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