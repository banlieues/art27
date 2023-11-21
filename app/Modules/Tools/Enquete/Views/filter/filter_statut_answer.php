<?php $id_answer_status = session('filter') && isset(session('filter')->id_answer_status) && isset(session('filter')->id_answer_status->value) ? session('filter')->id_answer_status->value : null;?>

<div class="row mb-2">
    <label for="enqueteIdAnswerStatut" class="col-form-label col-4"> <strong> Statut de l'enquête </strong> </label>
    <div class="col-8">
        <select value="" name="id_answer_status" id="enqueteIdAnswerStatut"
            class="form-control <?php if(!empty($id_answer_status)):?> highlighted <?php endif;?>"
            >
            <option class="form-control" value=""> Choisir un filtre </option>
            <?php foreach ($answer_status_list as $status):?>
                <option class="form-control" value="<?php echo $status->id?>"
                    <?php if(!empty($id_answer_status)):?> selected <?php endif;?>
                    >
                    <?php echo $status->label_fr;?>
                    <?php if(!empty($status->annotation_fr)) echo '(' . $status->annotation_fr . ')';?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>