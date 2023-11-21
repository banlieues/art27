<?php $id_lang = session('filter') && isset(session('filter')->id_lang) && isset(session('filter')->id_lang->value) ? session('filter')->id_lang->value : null;?>

<div class="row mb-2">
    <label for="enqueteIdLang" class="col-form-label col-4"> <strong> Langue de la réponse </strong> </label>
    <div class="col-8">
        <select value="" name="id_lang" id="enqueteIdLang"
            class="form-control <?php if(!empty($id_lang)):?> highlighted <?php endif;?>"
            >
            <option class="form-control" value=""> Choisir un filtre </option>
            <?php foreach ($language_list as $lang):?>
                <option class="form-control" value="<?php echo $lang->id?>"
                    <?php if(!empty($id_lang)):?> selected <?php endif;?>
                    >
                    <?php echo $lang->label;?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>