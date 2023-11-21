<div class="col">
    <div class="row mb-2">
        <div class="col-4"> 
            <input type="text" class="form-control datepicker" 
                form="<?php echo $form_id_prices_new;?>"
                name="<?php echo $form_id_prices_new;?>[<?php echo $i;?>][date_devis]"
                placeholder="Date du devis"
                <?php if($i==='##i##'):?> disabled <?php endif;?>
            />
        </div>
        <div class="col-4"> 
            <input type="number"
                step="0.01" 
                class="form-control" 
                form="<?php echo $form_id_prices_new;?>"
                name="<?php echo $form_id_prices_new;?>[<?php echo $i;?>][unit_price]"
                placeholder="Prix unitaire"
                <?php if($i==='##i##'):?> disabled <?php endif;?>
            />
        </div>
        <div class="col-4"> 
            <select class="form-select"
                data-style="bg-white border rounded"
                form="<?php echo $form_id_prices_new;?>"
                name="<?php echo $form_id_prices_new;?>[<?php echo $i;?>][price_origin]"
                <?php if($i==='##i##'):?> disabled <?php endif;?>
                >
                <option disabled selected> - Origine du prix - </option>
                <?php foreach($price_origin_list as $po):?>
                    <option value="<?php echo $po->id;?>"> <?php echo $po->label;?> </option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-4">
            <input type="number"
                step="1"
                class="form-control" 
                form="<?php echo $form_id_prices_new;?>"
                name="<?php echo $form_id_prices_new;?>[<?php echo $i;?>][validity_month]"
                placeholder="Durée de validité (mois)"
                <?php if($i==='##i##'):?> disabled <?php endif;?>
            />
        </div>
        <div class="col-auto">
            <div class="form-check text-nowrap px-0" title="Ignorer ce prix des calculs">
                <input type="checkbox"
                    class="form-check-input input-nullable mx-0"
                    value="1"
                    form="<?php echo $form_id_prices_new;?>"
                    name="<?php echo $form_id_prices_new;?>[<?php echo $i;?>][is_ignored]"
                    <?php if($i==='##i##'):?> disabled <?php endif;?>
                />
                <label class="form-check-label">
                    <?php echo fontawesome('calculator-no');?>
                </label>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12">
            <label> Remarques </label>
            <textarea class="form-control my-1" rows="3"
                name="<?php echo $form_id_prices_new;?>[<?php echo $i;?>][comment]"
                form="<?php echo $form_id_prices_new;?>"
                placehoder="Remarques"
                <?php if($i==='##i##'):?> disabled <?php endif;?>
            ></textarea>
        </div>
    </div>
</div>
<!-- <div class="col-auto">
    <div class="form-check text-nowrap" title="Notes">
        <input type="checkbox"
            class="form-check-input"
            onclick="display_control_notes(this, <?php echo $i;?>)"
        />
        <label class="form-check-label">
            <?php echo fontawesome('clipboard');?>           
        </label>
    </div>
</div> -->
<div class="col-auto text-end mb-1"> 
    <button class="plusminus-remove btn btn-sm btn-link link-dark invisible" title="Retirer ce PU">
        <?php echo fontawesome('trash-alt');?> 
    </button>
    <button class="plusminus-add btn btn-sm btn-link link-dark" title="Ajouter un PU">
        <?php echo fontawesome('plus');?> 
    </button>
</div>
<hr>


