<?php $request = \Config\Services::request(); ?>

<?php if (!$request_is_ajax): ?>
    <?php $this->extend('\Tache\view-tache-base'); ?>
    <?php $this->section('tache-body');?>

    <div class="container-fluid pt-2">

  
<?php endif;?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php //$dataview= \Config\Services::dataViewController();?>



<div class="row container">
<?php if (!$request_is_ajax): ?>
    <div class="col-md-12 col-sm-12">
<?php else:?>
    <div class="col-md-12 col-sm-12">
<?php endif;?>
      
</div>

<?php if (!$request_is_ajax): ?>
    <div class="col-md-12 col-sm-12">
<?php else:?>
    <div class="col-md-12 col-sm-12 mt-2">
<?php endif;?>

   <form id="form_tache" action="<?=base_url()?>tache/set_tache">
    
     <input type="hidden" name="id_demande" value="<?=$id_demande?>">
     <input type="hidden" name="id_tache" value="<?=$id_tache?>">
     
        <?php 
            if(isset($tache->sujet)):
                echo $dataview->getElementFormByIndex("sujet_tache","tache",$tache->sujet);    
            else:
                echo $dataview->getElementFormByIndex("sujet_tache","tache");
        endif;
        ?>

    
        <?php 
        //echo $tache->date_tache;
            if(isset($tache->date_tache)&&$tache->date_tache!="0000-00-00 00:00:00"):
                echo $dataview->getElementFormByIndex("date_tache","tache",$tache->date_tache);
            else:
                echo $dataview->getElementFormByIndex("date_tache","tache");
            endif;
                
        ?>

<?php 
  //echo $tache->echeance;
            if(isset($tache->echeance)&&$tache->echeance!="0000-00-00 00:00:00"):
                echo $dataview->getElementFormByIndex("echeance","tache",$tache->echeance);
            else:
                echo $dataview->getElementFormByIndex("echeance","tache");
            endif;
                
        ?>


<?php 
            if(isset($tache->rappel)):
                echo $dataview->getElementFormByIndex("is_rappel","tache",$tache->rappel);
            else:
                echo $dataview->getElementFormByIndex("is_rappel","tache");
            endif;
                
        ?>

<?php 
  //echo $tache->date_rappel;
            if(isset($tache->date_rappel)&&$tache->date_rappel!="0000-00-00 00:00:00"):
                echo $dataview->getElementFormByIndex("date_tache_rappel","tache",$tache->date_rappel);
            else:
                echo $dataview->getElementFormByIndex("date_tache_rappel","tache");
            endif;
                
        ?>
      


        <?php 
             if(isset($tache->id_user_tache)):
                echo $dataview->getElementFormByIndex("user_tache","tache",$tache->id_user_tache);
            else:
                if($id_tache>0):
                    echo $dataview->getElementFormByIndex("user_tache","tache");
                else:
                    echo $dataview->getElementFormByIndex("user_tache","tache",$id_user);
                endif;
            endif;
            
        ?>


        <?php 
             if(isset($tache->id_type_tache)):
                echo $dataview->getElementFormByIndex("type_tache","tache",$tache->id_type_tache);
            else:
                echo $dataview->getElementFormByIndex("type_tache","tache");
            endif;
        ?>

<?php 
             if(isset($tache->type_tache_libre)):
                echo $dataview->getElementFormByIndex("type_tache_libre","tache",$tache->type_tache_libre);
            else:
                echo $dataview->getElementFormByIndex("type_tache_libre","tache");
            endif;
        ?>
        
        <?php 
            if(isset($tache->id_statut_tache)):
                echo $dataview->getElementFormByIndex("statut_tache","tache",$tache->id_statut_tache);
            else:
                echo $dataview->getElementFormByIndex("statut_tache","tache");
            endif;
        ?>
        <?php 
             if(isset($tache->is_prive)):
                echo $dataview->getElementFormByIndex("is_prive","tache",$tache->is_prive);
            else:
                echo $dataview->getElementFormByIndex("is_prive","tache");
            endif;
        ?>
   


        <?php 
             if(isset($tache->note)):
                echo $dataview->getElementFormByIndex("note_tache_direct","tache",$tache->note);
            else:
                echo $dataview->getElementFormByIndex("note_tache_direct","tache");
            endif;
        ?>
        <?php if (!$request_is_ajax): ?>
        <!--<button class="btn btn-success btn-sm">Enregistrer le rendez-vous</button> <button id="tache_new_cancel" class="btn btn-danger btn-sm">Annuler la création du rendez-vous</button>-->
        <?php endif;?>
   </form>
</div>

</div>

<?php if (!$request_is_ajax):?>
</div>
<?=view("Demande\js_demande",["is_form_tache_direct"=>true])?>
<?php $this->endSection()?>
<?php endif;?>
