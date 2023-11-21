
<div class="row">

<?php foreach($fieldsAll as $fieldAll):?>
    
    <div 
        style="<?php if(in_array($fieldAll->field_index,$fieldsSelected)):?>display:none; <?php endif;?> cursor:pointer" 
        class="col-sm-4 col_<?=$fieldAll->field_index?> addField addField<?=$fieldAll->field_index?>"
        index="<?=$fieldAll->field_index?>" value="<?=$fieldAll->field_index?>"

        >
        <div class="mb-1">
        <div class="card-body">
            <p class="card-text">
            <!-- <input index="<?=$fieldAll->field_index?>" value="<?=$fieldAll->field_index?>" class="addField addField<?=$fieldAll->field_index?>" type="checkbox">  -->
               <i class="<?=icon("plus-field")?>"></i> <span class="labelField"><?=$fieldAll->label?></span>
            </p>
        </div>
        </div>
    </div>
   
 <?php endforeach;?>   

 <?php if(isset($fieldAll->field_index)):?>
 <div 
       style="cursor:pointer"
        class="col-sm-4 col_<?=$fieldAll->field_index?> addHr"
       index="@#<hr>"

        >
        <div class="card mb-1">
        <div class="card-body">
            
           <hr class="card-text">
          
        </div>
        </div>
    </div>

 </div> 
 <?php endif;?>


 
<?php if(!isset($is_need_clone)): $is_need_clone=TRUE; endif;?>
<?php if(!isset($type_clone)): $type_clone="default"; endif;?>
 <?php if($is_need_clone):?>

<?php if($type_clone=="default"):?>    
    <div style="display:none" class="row mb-2 container_one_field clone">
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <i index="null" style="cursor:pointer" class="fas fa-minus-circle text-danger deleteField"></i>
                    </div>
                    <div class="col-10"> 
                        <b class="labelField">null</b>
                    </div>
                </div>
            </div>
        
        <div class="col-4 col_name_index" name_index="null">
            <input class="inputName form-control" readonly="" value="null">
        </div> 
                    
        <div class="col-2">
            <i style="cursor:grab" class="far fa-hand-rock move-sortable"></i>
        </div>                         
    </div>

    <div style="display:none" class="row container_one_field clone_hr">
    
        <div class="col">
                <div class="row">
                    <div class="col"><i index="@#<hr>" style="cursor:pointer" class="fas fa-minus-circle text-danger deleteField"></i></div>
                    <div class="col-11 col_name_index" name_index="@#<hr>"><hr></div>
                </div>    
            
        </div>    
        <div class="col-2">  
            <i style="cursor:grab" class="far fa-hand-rock move-sortable"></i>
        </div>
                      
    </div>
<?php endif;?>

<?php if($type_clone=="injected_form"):?>
    <div style="display:none" class="row mb-2 container_one_field clone mb-4">
        <div class="col-lg-12">
            <div class="row">
                <div class="col"><i index="null" style="cursor:pointer" class="fas fa-minus-circle text-danger deleteField"></i>
                <b class="labelField">null</b></div>
            </div>
        </div>
        
        <div class="col-10 col_name_index" name_index="null">
            <input class="inputName form-control" readonly="" value="null">
        </div> 
                    
        <div class="col-2">
            <i style="cursor:grab" class="far fa-hand-rock move-sortable"></i>
        </div>                         
    </div> 
    
    <div style="display:none" class="row container_one_field clone_hr">

        <div class="col">
                <div class="row">
                    <div class="col"><i index="@#<hr>" style="cursor:pointer" class="fas fa-minus-circle text-danger deleteField"></i></div>
                    <div class="col-11 col_name_index" name_index="@#<hr>"><hr></div>
                </div>    
            
        </div>    
        <div class="col-2">  
            <i style="cursor:grab" class="far fa-hand-rock move-sortable"></i>
        </div>
                      
    </div>
<?php endif;?>    



<?php endif;?>
     
 