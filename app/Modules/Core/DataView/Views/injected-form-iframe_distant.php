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


    <form method="post" action="<?=base_url()?>/distant/save_registration">
        <div class="row mb-2">
            
           
            <?php if($id_contact>0):?>
                <div class="mb-3">
                        <h3>Inscrire <?=$contact->prenom?> <?=$contact->nom?> à l'action</h3>
                        <i>Veuillez compléter les informations suivantes pour finaliser l'inscription</i>
                </div>
                
            <?php else:?>    
               
                <?php echo $injectedForm->header_text;?> 
                <?php if(!is_null($activity)):?>
                    <h3><?=$activity->titre?></h3>
                    

                <?php else:?>
                <h3>Nom de l'action</h3>
                <?php endif;?>
                
            <?php endif;?>  
            
            <?php if(!isset($form_login)):$form_login=FALSE; endif;?>
           
           
            <p>Si vous êtes déjà enregistré(e), veuillez d'abord vous <a href="<?php echo base_url("identification/login")?>">connecter</a></p>
                    <?php //mettre statut à traiter, payement pas de statut, date suivi, heure suivi, id_activity ?>
                       
            
                        <?=view("DataView\Views\injected-form-get-dataView",[
                                                    "validation"=>$validation,
                                                    "typeDataView"=>"form",
                                                    "fields"=>$fields,
                                                    "value"=>NULL,
                                                    "indexes"=>$indexes_form,
                                                    "id_contact"=>$id_contact,
                                                    "activity"=>$activity
                                                   
                                                    ])
                                                ?> 
                                       
         
            
           
        </div> 
        <?php if(!$is_frame):?>
            <input type="hidden" name="id_user" value="<?=$id_user?>">

        <?php endif;?>

        <?php if($form_login):?>
            <div>
            <h5>Informations d'enregistrement</h5>
            <p>Ces informations vous donneront accès à un espace sécurisé où vous pourrez gérer vos inscriptions</p>

                <div class="row mb-2">
                    <label for="username" class="col-12 col-form-label">
                        <b>Login</b>
                    </label>
                    <div class="col-sm-9">
                        <input autocomplete="off"  type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>" placeholder="<?php echo lang('MembersList.username'); ?> ...">
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'username') : '' ?></span>
                    </div>
                </div> 
          

           
                <div class="row mb-2">
                    <label for="password" class="col-12 col-form-label">
                        <b>Mot de passe</b>
                    </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="<?php echo lang('MembersList.password'); ?> ...">
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'password') : '' ?></span>
                    </div>
                </div> 
          
            
                <div class="row mb-2">
                    <label for="confirm" class="col-12 col-form-label">
                       <b>Confirmer mot de passe</b>
                    </label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="confirm" name="confirm" value="<?php echo set_value('confirm'); ?>" placeholder="<?php echo lang('MembersList.confirm_password'); ?> ...">
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'confirm') : '' ?></span>
                    </div>
                </div> 
             </div>
             <input type="hidden" name="form_login" value="1">
        <?php endif;?>    
        <div class="mt-5">
                <input type="hidden" name="id_activity" value="<?=$id_activity?>">
                <input type="hidden" name="id_contact" value="<?=$id_contact?>">
            
                <input type="hidden" name="date_suivi" value="<?=date("d/m/Y")?>">
                <input type="hidden" name="heure_suivi" value="<?=date("H:i:s")?>">
                <input type="hidden" name="statut_payement" value="0">
            

                <!-- input hidden to declare update or insert -->
                <input type="hidden" value="<?=$id_injected_form?>" name="id_injected_form">
                <input type="hidden" value="create" name="typeDataView">
            
                        <span style="font-size:90%; color:#333333;">* Les champs marqués d'un astérisque sont des champs obligatoires</span><br>
                
                        <input name="jeminscris" id="jeminscris" value="y" type="checkbox" class="checkbox" checked="checked"> <label for="jeminscris" style="display:inline; font-size:1.2em; color:#660000;">Je m'inscris à l'action : </label>
                    </p>
                
                    <?php if($id_activity>0):?>
                        <span class="zone-button-form">
                            <button id="btn_enregistrement_frame" class="btn btn-success" type="submit">Enregistrer</button>
                        </span>
                        <span style="display:none" class="zone-submit-loading"> <i class="fas fa-circle-notch fa-spin"></i> Veuillez patientez pendant l'enregistrement de votre demande d'inscription…</span>  
                     <?php endif;?>   
        </div>  
    </form>
    <?php endif;?>


    <?php if($is_frame):?>
        <?php $this->endSection(); ?>
    <?php endif;?>


    <script src="<?php echo base_url('node_modules/jquery/dist/jquery.js'); ?>" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#btn_enregistrement_frame").click(function(e) {
                $(this).hide();
                $(".zone-submit-loading").show();
            });
        });
    </script>


