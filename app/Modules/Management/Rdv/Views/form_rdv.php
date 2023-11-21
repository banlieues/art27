<?php $request = \Config\Services::request(); ?>

<?php if (!$request_is_ajax): ?>
    <?php $this->extend('\Rdv\view-rdv-base'); ?>
    <?php $this->section('rdv-body');?>

    <div class="container-fluid pt-2">

  
<?php endif;?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php //$dataview= \Config\Services::dataViewController();?>



<div class="row">
<?php if (!$request_is_ajax): ?>
    <div class="col-md-6 col-sm-12">
<?php else:?>
    <div class="col-md-6 col-sm-12">
<?php endif;?>
        <span id="url_calendar" url="<?=base_url()?>/rdv/get_calendar"></span>
        <?php echo $dataview->getElementFormByIndex("user_calendrier","rdv",$id_user);?>
        <div style="display:none" id="loading_calendar_load" class="text-center mt-5 p-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>

        <div id="container_calendar_load">
            <?=$calendar;?>
        </div>
</div>

<?php if (!$request_is_ajax): ?>
    <div class="col-md-6 col-sm-12">
<?php else:?>
    <div class="col-md-6 col-sm-12 mt-2">
<?php endif;?>

   <form id="form_rdv" action="<?=base_url()?>rdv/set_rdv">
    
     <input type="hidden" name="id_demande" value="<?=$id_demande?>">
     <input type="hidden" name="id_rdv" value="<?=$id_rdv?>">
        <?php 
            if(isset($rdv->titre)):
                echo $dataview->getElementFormByIndex("titre_rdv","rdv",$rdv->titre);    
            else:
                echo $dataview->getElementFormByIndex("titre_rdv","rdv");
        endif;
        ?>

    
        <?php 
            if(isset($rdv->date_rdv_debut)):
                echo $dataview->getElementFormByIndex("date_rdv_debut","rdv",$rdv->date_rdv_debut);
            else:
                echo $dataview->getElementFormByIndex("date_rdv_debut","rdv");
            endif;
                
        ?>

        <?php
             if(isset($rdv->date_rdv_fin)):
                echo $dataview->getElementFormByIndex("date_rdv_fin","rdv",$rdv->date_rdv_fin);
            else:
                echo $dataview->getElementFormByIndex("date_rdv_fin","rdv");
            endif;
        ?>

        <div class="row">
            <div class="col-6">
                <?php 
                if(isset($rdv->temp_avant)):
                    echo $dataview->getElementFormByIndex("temp_avant_rdv","rdv",$rdv->temp_avant);
                else:
                    echo $dataview->getElementFormByIndex("temp_avant_rdv","rdv");
                endif;
                ?>
            </div>
                <div class="col-6">
            <?php 
                if(isset($rdv->temp_apres)):
                    echo $dataview->getElementFormByIndex("temp_apres_rdv","rdv",$rdv->temp_apres);
                else:
                    echo $dataview->getElementFormByIndex("temp_apres_rdv","rdv");
                endif;
            ?>
            </div>

        </div>

        <?php 
             if(isset($rdv->id_user_rdv)):
                echo $dataview->getElementFormByIndex("user_rdv","rdv",$rdv->id_user_rdv);
            else:
                if($id_rdv>0):
                    echo $dataview->getElementFormByIndex("user_rdv","rdv");
                else:
                    echo $dataview->getElementFormByIndex("user_rdv","rdv",$id_user);
                endif;
            endif;
            
        ?>
        <?php 
             if(isset($rdv->id_type_rdv)):
                echo $dataview->getElementFormByIndex("type_rdv","rdv",$rdv->id_type_rdv);
            else:
                echo $dataview->getElementFormByIndex("type_rdv","rdv");
            endif;
        ?>
        <?php 
            if(isset($rdv->id_statut_rdv)):
                echo $dataview->getElementFormByIndex("statut_rdv","rdv",$rdv->id_statut_rdv);
            else:
                echo $dataview->getElementFormByIndex("statut_rdv","rdv");
            endif;
        ?>
        <?php 
             if(isset($rdv->is_prive)):
                echo $dataview->getElementFormByIndex("is_prive_rdv","rdv",$rdv->is_prive);
            else:
                echo $dataview->getElementFormByIndex("is_prive_rdv","rdv");
            endif;
        ?>
   

        <?php 
            if(isset($rdv->lieu)):
                echo $dataview->getElementFormByIndex("lieu_rdv","rdv",$rdv->lieu);
            else:
                echo $dataview->getElementFormByIndex("lieu_rdv","rdv");
            endif;
        ?>

        <?php 
             if(isset($rdv->note)):
                echo $dataview->getElementFormByIndex("note_rdv","rdv",$rdv->note);
            else:
                echo $dataview->getElementFormByIndex("note_rdv","rdv");
            endif;
        ?>
        <?php if (!$request_is_ajax): ?>
        <!--<button class="btn btn-success btn-sm">Enregistrer le rendez-vous</button> <button id="rdv_new_cancel" class="btn btn-danger btn-sm">Annuler la création du rendez-vous</button>-->
        <?php endif;?>
   </form>
</div>

</div>

<?php if (!$request_is_ajax):?>
</div>
<?=view("Demande\js_demande",["is_form_rdv_direct"=>true])?>
<?php $this->endSection()?>
<?php endif;?>
