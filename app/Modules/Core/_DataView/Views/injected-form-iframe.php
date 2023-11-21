<?php $request = \Config\Services::request(); ?>
<?php $validation = \Config\Services::validation(); ?>

<?php if($request->getVar()): $getVar=$request->getVar(); endif;?>

<?php if(!isset($is_frame))
            $is_frame=TRUE;
?>            

<?php if($is_frame):?>
<?php $this->extend('Layout\index'); ?>
<?php $this->section("body"); ?>
<?php endif;?>

    <?php if(empty($injectedForm)):?>

        <h3 class="text-center">Aucun formulaire trouvé</h3>

    <?php else:?> 
        <?php if($is_frame):?>
        <div class="row justify-content-start align-items-center mb-5">
				<div class="col-2 col-md-2"><img src="http://www.cemea.be/squelettes/images/logo.png" class="img-fluid pulse animated infinite"></div>

	        </div>
    <?php endif;?>    


    <form method="post" action="<?=base_url()?>/utilisator/save_registration">
        <div class="row mb-2">
            
         
            <?php if($id_contact>0):?>
                <div class="mb-3">
                        <h3>Inscrire <?=$contact->prenom?> <?=$contact->nom?> à l'action <?=$name_action?> </h3>
                        <i>Veuillez compléter les informations suivantes pour finaliser l'inscription</i>
                </div>
            <?php else:?>    
                
                <?php echo $injectedForm->header_text;?> 
                <?php if(!is_null($activity)):?>
                <h3><?=$activity->titre?></h3>
                <?php endif;?>
            <?php endif;?>  
            
            <?php   if(!isset($contact)): $contact=NULL; endif;
                    if(!isset($is_distant)): $is_distant=0; endif;
              ?>      
                    <?php //mettre statut à traiter, payement pas de statut, date suivi, heure suivi, id_activity ?>
                       <?php if(!isset($modules)): $modules=NULL; endif;?>
            
                        <?=view("DataView\Views\injected-form-get-dataView",[
                                                    "validation"=>$validation,
                                                    "typeDataView"=>"form",
                                                    "fields"=>$fields,
                                                    "value"=>$contact,
                                                    "indexes"=>$indexes_form,
                                                    "id_contact"=>$id_contact,
                                                    "modules"=>$modules,
                                                    "activity"=>$activity
                                                    ])
                                                ?> 
                                       
         
            
           
        </div> 
        <?php if(!$is_frame):?>
            <input type="hidden" name="id_user" value="<?=$id_user?>">

        <?php endif;?>

        <input type="hidden" name="id_activity" value="<?=$id_activity?>">
        <input type="hidden" name="id_contact" value="<?=$id_contact?>">
       
        <input type="hidden" name="date_suivi" value="<?=date("d/m/Y")?>">
        <input type="hidden" name="heure_suivi" value="<?=date("H:i:s")?>">
        <input type="hidden" name="statut_payement" value="0">
       

        <!-- input hidden to declare update or insert -->
        <input type="hidden" value="<?=$id_injected_form?>" name="id_injected_form">
        <input type="hidden" value="create" name="typeDataView">
        <input type="hidden"   value="<?=$is_distant?>" name="is_distant">
       
                <span style="font-size:90%; color:#333333;">* Les champs marqués d'un astérisque sont des champs obligatoires</span><br>
          
                <input name="jeminscris" id="jeminscris" value="y" type="checkbox" class="checkbox" checked="checked"> <label for="jeminscris" style="display:inline; font-size:1.2em; color:#660000;">Je m'inscris à l'action : </label>
            </p>
        
          <?php if($id_activity>0):?>
                <span class="zone-button-form">
                    <button class="btn btn-success" type="submit">Enregistrer</button>
                </span>
                <span style="display:none" class="zone-submit-loading"> <i class="fas fa-circle-notch fa-spin"></i> Veuillez patientez</span>  
          <?php endif;?>
    </form>
    <?php endif;?>


    <?php if($is_frame):?>
        <?php $this->endSection(); ?>
    <?php endif;?>