<?php $request = \Config\Services::request(); ?>

<?php 
    //on affiche le template si on appelle pas par Ajax
    if (!$request->isAJAX()) 
    {
        $this->extend('templates/index');
        $this->section("body");
    }
?> 


    <h3>Listes</h3>
    <?php if(!empty($documents)):?>
    <ul class="list-group">
          
            <?php foreach($documents as $document):?>   
               
            <li class="list-group-item">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">
                            <div class="row">
                                <div class="col-1"> 
                                    <?php if(in_array($document->id_template_document_action,$documents_create)):?>
                                        <i class="fa fa-check"></i>
                                        <?php if($documents_create_detail[$document->id_template_action]):?>
                        
                                            
                                                <i class="<?=icon("email")?>"></i>
                                         
                                        
                                        <?php endif; ?>
                                    <?php endif;?>    
                                    
                                </div> 
                                <div class="col">   
                                    <i class="<?=icon("file-pdf")?> text-danger"></i> <?=$document->label?>
                                </div>
                                <div class="col-4 text-right">
                                    
                                    <?php if(in_array($document->id_template_document_action,$documents_create)):?>
                                        <div class="btn-group btn-sm" role="group">

                                            <button id="btnGroupDrop1" type="button" class="btn btn-office btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Actions
                                            </button>
                                            
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            
                                                    <li>
                                                        <a class="dropdown-item" href="<?=base_url()?>/documentsgenerator/mass_getfile/<?=$document->id_template_document_action?>/<?=$id_activity?>"><i class="<?=icon("download")?> text-danger"></i> Télécharger</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item m-1 document_action" href="<?=base_url()?>/documentsgenerator/mass_sendfile/<?=$document->id_template_document_action?>/<?=$id_activity?>"><i class="<?=icon("send_email")?> text-danger"></i> Envoyer</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item m-1 document_action" href="<?=base_url()?>/documentsgenerator/mass_getDelete/<?=$document->id_template_document_action?>/<?=$id_activity?>"><i class="<?=icon("delete")?> text-danger"></i> Effacer</a>
                                                    </li>
                                                

                                            
                                            </ul> 
                                        
                                        </div> 
                                    <?php else:?>
                                                
                                            <a class="dropdown-item" href="<?=base_url()?>/documentsgenerator/mass_getpdf/<?=$document->id_template_document_action?>/<?=$id_activity?>"><i class="<?=icon("download")?> text-danger"></i> Produire pdf</a>
                                            
                                    <?php endif;?>    

                                        
                                </div>
                            </div>
                        </div> 
                    </div>
            </li>
            <?php endforeach;?> 
    </ul>
<?php else:?>
    <p>Pas de listes disponibles!</p>    
<?php endif;?>


    <h3>Documents</h3>
    <?php if(!empty($documents_individuels)):?>
    <ul class="list-group">
          
            <?php foreach($documents_individuels as $document_individuel):?>   
               
            <li class="list-group-item">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">
                            <div class="row">
                                <div class="col-1"> 
                                    <?php if(in_array($document_individuel->id_template_document,$documents_create)):?>
                                        <i class="fa fa-check"></i>
                                        <?php if($documents_create_detail[$document_individuel->id_template_document]):?>
                        
                                            
                                                <i class="<?=icon("email")?>"></i>
                                         
                                        
                                        <?php endif; ?>
                                    <?php endif;?>    
                                    
                                </div> 
                                <div class="col">   
                                    <i class="<?=icon("file-pdf")?> text-danger"></i> <?=$document_individuel->titre?>
                                </div>
                                <div class="col-4 text-right">
                                    
                                    <?php if(in_array($document_individuel->id_template_document,$documents_create)):?>
                                        <div class="btn-group btn-sm" role="group">

                                            <button id="btnGroupDrop1" type="button" class="btn btn-office btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Actions
                                            </button>
                                            
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            
                                                    <li>
                                                        <a class="dropdown-item" href="<?=base_url()?>/documentsgenerator/mass_getfile/<?=$document_individuel->id_template_document?>/<?=$id_activity?>"><i class="<?=icon("download")?> text-danger"></i> Télécharger</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item m-1 document_action" href="<?=base_url()?>/documentsgenerator/mass_sendfile/<?=$document_individuel->id_template_document?>/<?=$id_activity?>"><i class="<?=icon("send_email")?> text-danger"></i> Envoyer</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item m-1 document_action" href="<?=base_url()?>/documentsgenerator/mass_getDelete/<?=$document_individuel->id_template_document?>/<?=$id_activity?>"><i class="<?=icon("delete")?> text-danger"></i> Effacer</a>
                                                    </li>
                                                

                                            
                                            </ul> 
                                        
                                        </div> 
                                    <?php else:?>
                                                
                                            <a class="dropdown-item" href="<?=base_url()?>/documentsgenerator/mass_getpdf_docu/<?=$document_individuel->id_template_document?>/<?=$id_activity?>"><i class="<?=icon("download")?> text-danger"></i> Produire pdf</a>
                                            
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