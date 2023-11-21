<?php //$emails=$demandeModel->getFil($id_demande);?>

<?php $outlookModel = \Config\Services::outlookModel();?>


<div  style="display:none" class="list-group-flush">
        <?php foreach($emails as $email):?>
          <a href="<?php //URL_DOCUMENT?>" class="list-group-item list-group-item-action text-primary">
          
          <?=$email->subject?>
        
            </a>
        <?php endforeach;?>        
</div>

<?php //variables
    $a_mail=NULL;
    $cc_mail=NULL;
    $cci_mail=NULL;


?>




<?php if(!empty($emails)):?>
    <?php $body_mail_deja_affiche=array();?>
    <?php $ncollapse=0?>
    <?php foreach($emails as $email):?>
        <?php //debug($email,true);?>
        <?php 
        
            if(!empty($email->mail_template)):
                
                $from_demande_homegrade="crm";

            else:
            $from_demande_homegrade=from_demande_homegrade($email->sender_mail,$email->subject);
            endif;
            
        ?>

        <?php if($from_demande_homegrade=="conseiller"):?>
         
            <div id="<?=$email->id_primary?>" date_sort="<?php echo $email->send_datetime;?>" class="row sort_fil_classement">
                        
                <div class="col-md-4"></div>
                    
                <div class="col-md-8">  
                    <div class="card mb-2  <?php if($email->is_brouillon):?>bg-warning<?php endif?>">
                            <div style="" class="card-header">
                            <div class="row">
                                <div class="col">
                                <?php if(!$email->is_brouillon):?>
                                    <button style="font-size:12px !important" id_demande="<?=$id_demande?>"  context="repondre" id_message="<?=$email->id_email?>" class="btn btn-info text-white btn-sm btn_action_message_mail bt_redaction_message"><i class="fa-solid fa-reply"></i> Re</button>

                                    <button style="font-size:12px !important" id_demande="<?=$id_demande?>"  context="transfert" id_message="<?=$email->id_email?>" class="btn btn-orange text-white btn-sm btn_action_message_mail bt_redaction_message"><i class="fa-solid fa-share"></i> Fwd</button>
                                <?php else:?>

                                    <button style="font-size:12px !important" id_demande="<?=$id_demande?>"  context="brouillon" id_message="<?=$email->id_email?>" class="btn btn-danger text-white btn-sm btn_action_message_mail bt_redaction_message"><i class="fa-brands fa-firstdraft"></i> Editer le brouillon</button>

                                <?php endif;?>
                                </div>
                                <div class="col text-end">
                                    <?php if($email->is_brouillon):?>
                                        <div class="text-danger badge badge-danger">Brouillon</div>
                                    <?php else:?>
                                <?=view("Outlook\Views/statut_lu",[
                                        "email"=>$email
                                    ])?> 
                                    <?php endif;?>
                                </div>
                            </div>
                        
                            </div>

                        <?php echo view("Outlook\message_view_document",["documents"=>$outlookModel->get_fichier_joins($email->id_email)])?>

                        <div style="" class="card-body">
                            

                                <small style="font-size:12px"  class="text-body-secondary">
                                        <a href="<?=base_url()?>outlook/message_view/<?=$email->id_email?>" class="text-success view_message float-end"><i class="<?=icon("message_view")?>"></i></a>


                                            <span class="message_date"><?=convert_date_en_to_fr_with_h($email->send_datetime)?></span> <small>(#<?=$email->id_email?>)</small>
                                                <br>
                                                <div class="<?php echo $ncollapse;?>">
                                                    <div class="col-md-12">
                                                        De: <b><span class="message_a"><?=$email->sender_mail?></span></b>
                                                    </div>
                                                    <div class="col-md-12">
                                                     A: <b><?=$email->to_mail?></b>
                                                    </div>
                                                </div>
                                                
                                                Objet:<b><span class="message_sujet"><?=$email->subject?></span></b>
                                               
                                            </small>
                            
                                <?php $body_mail_affiche=nettoye_body_mail($email->body_content,$body_mail_deja_affiche)?>
                                <?php array_push($body_mail_deja_affiche,$body_mail_affiche);?>
                                <br><br><span class="message_body"><?php echo $body_mail_affiche?></span>
                                                                                    
                        </div>        
                    </div>
                    
                </div>
                <?php $a_mail=$email->to_mail;?>
            </div>    

        <?php elseif($from_demande_homegrade=="crm"):?>
         
            <?php //Les mails automatiques ?>
                <div id="<?=$email->id_primary?>" date_sort="<?php echo $email->send_datetime;?>" class="row sort_fil_classement"> 
                    <div class="col-md-4">
                        
                      
                    </div>
                    <div class="col-md-8">  
                       
                            <div class="card mb-2">
                                
                                    <div class="card-body">
                                        <a style="font-size:12px" href="<?=base_url()?>outlook/message_view/<?=$email->id_email?>" class="text-success view_message float-end"><i class="<?=icon("message_view")?>"></i></a>
                                        <small style="font-size:12px" class="text-body-secondary"><span class="message_date"><?=convert_date_en_to_fr_with_h($email->send_datetime)?></span>  <small>(#<?=$email->id_email?>)</small> </small>    
                                        <br><small style="font-size:12px" class="text-body-secondary">Mail automatique à <b><?=$email->to_mail?></b></small>
                                        <br>    <small><span class="message_sujet"><?=$email->subject?></span></small>
                                    </div>
                            </div>
                    </div>
                   
                </div>
            
        
        <?php else:?>
           
                <?php //Les mails reçus ?>
                <div id="<?=$email->id_primary?>" date_sort="<?php echo $email->received_datetime;?>" class="row sort_fil_classement">
                    
                    <div class="col-md-8 col-md-offset-4">  
                            <div class="card mb-2 card_message"> 
                            <div style="" class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <button style="font-size:12px !important" id_demande="<?=$id_demande?>"  context="repondre" id_message="<?=$email->id_email?>" class="btn btn-info text-white btn-sm btn_action_message_mail bt_redaction_message"><i class="fa-solid fa-reply"></i> Re</button>

                                        <button style="font-size:12px !important" id_demande="<?=$id_demande?>"  context="transfert" id_message="<?=$email->id_email?>" class="btn btn-orange text-white btn-sm btn_action_message_mail bt_redaction_message"><i class="fa-solid fa-share"></i> Fwd</button>
                                    </div>
                                    <div class="col text-end">
                                    <?=view("Outlook\Views/statut_lu",[
                                            "email"=>$email
                                        ])?> 
                                    </div>
                                </div>

                        
                            </div>


                            <?php echo view("Outlook\message_view_document",["documents"=>$outlookModel->get_fichier_joins($email->id_email)])?>

                                    <div class="card-header">
                                            <small style="font-size:12px"  class="text-body-secondary">
                                            <a href="<?=base_url()?>outlook/message_view/<?=$email->id_email?>" class="text-success view_message float-end"><i class="<?=icon("message_view")?>"></i></a>

                                                        <span class="message_date"><?=convert_date_en_to_fr_with_h($email->received_datetime)?></span>  <small>(#<?=$email->id_email?>)</small>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        De: <b><span class="message_a"><?=$email->sender_mail?></span></b>
                                                    </div>
                                                    <div class="col-md-12">
                                                     A: <b><?=$email->to_mail?></b>
                                                    </div>
                                                </div>
                                         
                                                Objet:<b><span class="message_sujet"><?=$email->subject?></span></b>
                                             
                                            </small>
                                           
                                           
                                            <small>
                                                <?php $body_mail_affiche=nettoye_body_mail($email->body_content,$body_mail_deja_affiche)?>
                                                <?php array_push($body_mail_deja_affiche,$body_mail_affiche);?>
                                                <br><br><span class="message_body"><?php echo $body_mail_affiche?></span>
                                            </small>   
                                    </div>
                            </div>
                    </div>
                </div>
                <?php $a_mail=$email->sender_mail;?>
        <?php endif;?>    

        

        <?php $ncollapse=$ncollapse+1?>

    <?php endforeach;?> 


    
    <?php else:?>

        <div class="row p-1 m-2">
            Pas de messages mail liés à cette demande
        </div>


<?php endif;?> 

<input type="hidden" id="a_mail" value="<?=$a_mail?>"></a>
<script>
    jQuery(document).ready(function()
    {
	
       if($("div#container_fil>div.sort_fil_classement").length)
       {
           
     
      
            //tinysort("div#container_fil>div.sort_fil_classement",{attr:'date_sort',order:'desc'});
            
            $(".accordion_historique").collapse();
            
            $(document).off("click","#inverser_ordre_fil").on("click","#inverser_ordre_fil", function(e){
            
                    var sens=$(this).attr("statut");
                tinysort("div#container_fil>div.sort_fil_classement",{attr:'date_sort',order:sens});
                    
                    if(sens==="asc"){$(this).attr("statut","desc");} else {$(this).attr("statut","asc")};
                    
                    
                
                });
        
        }
	
    });

</script>