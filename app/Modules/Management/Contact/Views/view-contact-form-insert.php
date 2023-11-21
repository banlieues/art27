<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>
<?php $contactConstructor=\Config\Services::contact();?>

<?php $this->extend('\Contact\view-contact-base'); ?>

<?php $this->section('contact-body');?>

<form id="new_form" method="post" action="<?=base_url()?>/contact/save_new" >
<div class="row mb-2">
      <div class="col-6">
               <div class="card">

                   
                        <h4 class="text-center card-header border-top-<?php echo $themes->contact->color;?>">
                           <?php echo $themes->contact->icon;?> <?=$contact_component->title?>
                        </h4>
                    
                     
                     <div class="card-body">
                           <?=view("DataView\Views\get-dataView",[
                                       "validation"=>$validation,
                                       "typeDataView"=>$typeDataView,
                                       "fields"=>$fields,
                                       "value"=>null,
                                       "indexes"=>explode(",",trim($contact_component->fields)),
                                       "num_container"=>$contact_component->id_components,
                              
                                       
                                       ])
                              ?> 
                     </div>

               </div>

               <div class="card mt-2">

                   
                        <h4 class="text-center card-header border-top-<?php echo $themes->contact_profil->color;?>">
                           <?php echo $themes->contact_profil->icon;?> <?=$contact_profil_component->title?>
                        </h4>
                    
                     
                     <div class="card-body">
                           <?=view("DataView\Views\get-dataView",[
                                       "validation"=>$validation,
                                       "typeDataView"=>$typeDataView,
                                       "fields"=>$fields,
                                       "value"=>null,
                                       "indexes"=>explode(",",trim($contact_profil_component->fields)),
                                       "num_container"=>$contact_profil_component->id_components,
                              
                                       
                                       ])
                              ?> 
                     </div>

               </div>

               


      </div>
</div>
<input type="hidden" name="id_contact" value="0">
<input type="hidden" name="id_contact_profil" value="0">


</form>
<!-- input hidden to declare update or insert -->
<script>
      $("document").ready(function()
      {
            $(document).on("click","#btn_new_form", function(){

                  $("#new_form").submit();

                  return false;
            });
            
      }
      )
</script>

<?php $this->endSection();?>
