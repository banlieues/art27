<?php $request = \Config\Services::request(); ?>

<?php $this->extend("\Administrator\Views\administrator/index"); ?>

<?php $this->section("admin_body"); ?>

<div class="row">
    <div class="col-md-12 col-xl-12">
        <h4><i class="fa fa-edit"></i> Modification données Contact</h4>
        <div class="card flex-fill mb-4">
                <div class="card-body">
                    <form method="post" action="<?=base_url()?>/user/save_updateContact">
                        
                        <?php foreach($indexes as $index):?>
                            <div class="row mb-2 container_one_field mb-4">

                                <?php //I.l'élement du label?>
                                <div class="col-12">
                                    <b><?=$fields[$index]["label"];?> <?php if($dataView->isRequired($fields[$index])):?>* <?php endif;?> </b>
                                </div>  


                                <?php  //II. Message d'erreur ?>
                                <?php if(!empty($validation)&&$validation->getError($index)): ?>
                                        <div class="text-danger">
                                            <i class="<?=icon("triangle_warning")?>"></i> <?= $error = $validation->getError($index); ?>
                                        </div>
                                <?php endif; ?>


                                <?php
                                    //III. initialize mode_error
                                    $field_sql=$dataView->getFiedlSql($index);
                                
                                    if(!empty($validation)&&!empty($validation->getErrors())): 
                                        $mode_error_on=TRUE; 
                                    else: 
                                        $mode_error_on=FALSE; 
                                    endif;


                                    //IV verifiy if inout value has set after a submit.
                                    if($request->getVar($index)): //value of form after submit 
                                        $valueIndex=$request->getVar($index); 
                                    else: 
                                        //if not getVar from submit then value in database (if case update) or Null(if case insert)
                                        if(isset($field_sql)&&isset($contact->$field_sql))://value of form before submit
                                            $valueIndex=$contact->$field_sql; 
                                        else:
                                            $valueIndex=NULL;
                                        endif;    
                                    endif;

                                    //Treatement of checkbox, if none checkbox (for a index of type checkox) checked, the input does not exist then $valueIndex = NULL
                                    if(!$request->getVar($index)&&$mode_error_on):
                                        $valueIndex=NULL; 
                                    endif;  
                                                
                                ?>

                                <?php //V. l'élément de formulaire ?>
                                <div class="col-12">
                                    <?php $field_sql=$dataView->getFiedlSql($index);?>
                                    <?php echo $dataView->getElementForm($index,$fields[$index],$valueIndex, $component->type); ?>
                                </div> 
                                
                                <?php /* Input with list of index of form, to use for verification of checkbox empty */ ?>
                                <input name="indexesForm[]" type="hidden" value="<?=$index?>">   
                            </div>
                        <?php endforeach;?>

                        <?php /* button */ ?>

                        <button type="submit" class="btn btn-success btn-sm">Enregistrer</button>
                        <a href="<?php echo base_url("user/contacts/list?id_user=$user->id");?>" class="btn btn-danger btn-sm">Annuler</a>
                            
                        <input type="hidden" name="id_contact" value="<?=$id_contact?>">
                        <input type="hidden" name="id_user" value="<?=$user->id?>">


                    </form>
                </div>
        </div>                   
    </div> 
</div>                      

<?php $this->endSection(); ?>