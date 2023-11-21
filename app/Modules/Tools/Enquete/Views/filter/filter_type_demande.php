<?php $id_enquete = session('filter') && isset(session('filter')->id_enquete) && isset(session('filter')->id_enquete->value) ? session('filter')->id_enquete->value : null;?>

<div class="row mb-2">
    <label for="enqueteIdEnquete" class="col-form-label col-4"> <strong> Demande/accompagnement </strong> </label>
    <div class="col-8">
        <select name="id_enquete" id="enqueteIdEnquete"
            class="form-control <?php if(!empty($id_enquete)):?> highlighted <?php endif;?>" 
            >
            <option class="form-control" value="">Choisir un filtre</option>
            <?php foreach ($enquete_list as $enquete):?>
                <option class="form-control" value="<?php echo $enquete->id_enquete?>"
                    <?php if(!empty($id_enquete) && $id_enquete==$enquete->id_enquete):?> selected <?php endif;?>
                    >
                    <?php echo $enquete->path_fr;?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>