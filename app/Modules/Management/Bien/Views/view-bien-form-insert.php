<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>
<?php $contactConstructor=\Config\Services::contact();?>

<?php $this->extend('\Bien\view-bien-base'); ?>

<?php $this->section('bien-body');?>

<form id="new_form" method="post" action="<?=base_url()?>/bien/save_new" >

<div class="row mb-2 load_ajax">
      <div class="col-6">
               <div class="card">

                   
                        <h4 class="text-center card-header border-top-<?php echo $themes->bien->color;?>">
                           <?php echo $themes->bien->icon;?> <?=$bien_component->title?>
                        </h4>
                    
                     
                     <div class="card-body">
                           <?=view("DataView\Views\get-dataView",[
                                       "validation"=>$validation,
                                       "typeDataView"=>$typeDataView,
                                       "fields"=>$fields,
                                       "value"=>null,
                                       "indexes"=>explode(",",trim($bien_component->fields)),
                                       "num_container"=>$bien_component->id_components,
                              
                                       
                                       ])
                              ?> 
                     </div>

               </div>

               <div class="card mt-2">

                   
                        <h4 class="text-center card-header border-top-<?php echo $themes->bien_caracteristique->color;?>">
                           <?php echo $themes->bien_caracteristique->icon;?> <?=$bien_caracteristique_component->title?>
                        </h4>
                    
                     
                     <div class="card-body">
                           <?=view("DataView\Views\get-dataView",[
                                       "validation"=>$validation,
                                       "typeDataView"=>$typeDataView,
                                       "fields"=>$fields,
                                       "value"=>null,
                                       "indexes"=>explode(",",trim($bien_caracteristique_component->fields)),
                                       "num_container"=>$bien_caracteristique_component->id_components,
                              
                                       
                                       ])
                              ?> 
                     </div>

               </div>

               


      </div>
</div>
<input type="hidden" name="id_bien" value="0">
<input type="hidden" name="id_bien_caracteristique" value="0">

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
