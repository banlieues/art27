<div class="table-responsive table-fullview">
    <table class="table table-bordered table-striped table-hover my-0">
        <thead class="table-light">
            <tr>
                <th class="text-truncate"> Indroduit le </th>
                <th class="text-truncate"> Date du devis </th>
                <th class="text-truncate"> Origine </th>
                <th class="text-truncate"> Montant (€) </th>
                <th class="text-truncate"> Notes </th>
                <!-- <th class="text-truncate"> Màj le </th> -->
                <!-- <th class="text-truncate"> Màj par </th> -->
                <th class="text-truncate"> Désactivé </th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0;?>
            <?php foreach($road->prices as $price):?>
                <tr>
                    <td class="py-0">
                        <input type="hidden" 
                            form="<?php echo $form_id_price_update . $price->id_price;?>" 
                            name="<?php echo $form_id_price_update;?>[id_price]"
                            value="<?php echo $price->id_price;?>"
                        />
                        <div class="form-control-plaintext small"> 
                            <?php echo convert_date_en_to_fr_with_h($price->created_at, false);?>
                        </div>
                    </td>
                    <td class="py-0">
                        <div class="form_read form-control-plaintext small" 
                            form="<?php echo $form_id_price_update . $price->id_price;?>"
                            > 
                            <?php echo convert_date_en_to_fr_with_h($price->date_devis, false);?>
                        </div>
                        <div class="form_update" style="display: none"
                            form="<?php echo $form_id_price_update . $price->id_price;?>"
                            >
                            <input type="text" class="form-control datepicker"
                                form="<?php echo $form_id_price_update . $price->id_price;?>" 
                                name="<?php echo $form_id_price_update;?>[date_devis]" 
                                value="<?php echo $price->date_devis;?>"
                            />
                        </div>
                    </td>
                    <td class="py-0"> 
                        <div class="form_read form-control-plaintext small" 
                            form="<?php echo $form_id_price_update . $price->id_price;?>"
                            > 
                            <?php echo $price->price_origin_label;?>
                        </div>
                        <div class="form_update" style="display: none !important;"
                            form="<?php echo $form_id_price_update . $price->id_price;?>"
                            >
                            <select class="form-select"
                                form="<?php echo $form_id_price_update . $price->id_price;?>" 
                                name="<?php echo $form_id_price_update;?>[price_origin]"
                                >
                                <?php foreach($price_origin_list as $po):?>
                                    <option
                                        value="<?php echo $po->id;?>"
                                        <?php if($price->price_origin==$po->id):?>
                                            selected
                                        <?php endif;?>
                                        >
                                        <?php echo $po->label;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </td>
                    <td class="py-0"> 
                        <input type="text" class="form-control-plaintext small" 
                            form="<?php echo $form_id_price_update . $price->id_price;?>" 
                            name="<?php echo $form_id_price_update;?>[unit_price]" 
                            value="<?php echo $price->unit_price;?>" 
                        />
                    </td>
                    <td class="py-0"> 
                        <textarea class="form_read form-control-plaintext small" 
                            form="<?php echo $form_id_price_update . $price->id_price;?>"
                        ><?php echo $price->comment;?></textarea>
                        <div class="form_update" style="display: none !important;"
                            form="<?php echo $form_id_price_update . $price->id_price;?>"
                            >
                            <textarea class="form-control" rows="3"
                                form="<?php echo $form_id_price_update . $price->id_price;?>" 
                                name="<?php echo $form_id_price_update;?>[comment]"
                            ><?php echo $price->comment;?></textarea>
                        </div>
                    </td>
                    <!-- <td class="py-0"> 
                        <div class="form-control-plaintext small">
                            <?php echo convert_date_en_to_fr_with_h($price->updated_at, true, false);?>
                        </div>
                    </td> -->
                    <!-- <td class="py-0"> 
                        <div class="form-control-plaintext small">
                            <?php echo fullname($price->updated_prenom, $price->updated_nom);?> </div>
                    </td> -->
                    <td class="py-1"> 
                        <?php if(!empty($price->is_ignored)):?>
                            <div class="form_read"
                                form="<?php echo $form_id_price_update . $price->id_price;?>"
                                >
                                <label class="form-check-label" title="Prix ignoré dans les calculs">
                                    <?php echo fontawesome('calculator-no');?>
                                </label>
                            </div>
                        <?php endif;?>
                        <div class="form_update" style="display: none !important;"
                            form="<?php echo $form_id_price_update . $price->id_price;?>"
                            >
                            <div class="form-check px-1 text-nowrap" title="Ignorer ce prix des calculs">
                                <input type="checkbox"
                                    class="form-check-input input-nullable mx-0 mt-2"
                                    value="1"
                                    form="<?php echo $form_id_price_update . $price->id_price;?>"
                                    name="<?php echo $form_id_price_update;?>[is_ignored]"
                                    <?php if(!empty($price->is_ignored)):?> checked <?php endif;?>
                                />
                                <label class="form-check-label">
                                    <?php echo fontawesome('calculator-no');?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <td class="text-center py-1">
                        <div class="d-flex">
                            <button type="button" class="form_read btn btn-sm btn-link link-dark"
                                title="Editer le prix unitaire"
                                form="<?php echo $form_id_price_update . $price->id_price;?>"
                                onclick="js_form_update(this);"
                                > <?php echo fontawesome('edit');?> 
                            </button>
                            <form id="<?php echo $form_id_price_update . $price->id_price;?>" method="post" action="<?php echo base_url('calculator/road/' . $road->id_road);?>">
                                <button type="submit" class="form_update btn btn-sm btn-link link-dark" 
                                    style="display: none;"
                                    title="Sauvegarder les modifications sur le prix unitaire"
                                    form="<?php echo $form_id_price_update . $price->id_price;?>"
                                    href="<?php echo current_url();?>"
                                    onclick="waiting_start(this);"
                                    > <?php echo fontawesome('save');?> 
                                </button>
                            </form>
                            <a role="button" class="form_update btn btn-sm btn-link link-dark"
                                title="Annuler les modifications sur le prix unitaire" 
                                style="display: none;"
                                form="<?php echo $form_id_price_update . $price->id_price;?>"
                                href="<?php echo current_url();?>"
                                onclick="waiting_start(this);"
                                > <?php echo fontawesome('undo');?> 
                            </a>
                            <button class="ban_deleteForm form_update btn btn-sm btn-link link-danger"
                                title="Supprimer le prix unitaire"
                                style="display: none;"
                                form="<?php echo $form_id_price_update . $price->id_price;?>"
                                id_delete="<?php echo $price->id_price;?>"
                                href="<?php echo base_url("calculator/price/$price->id_price/delete");?>"
                                text_alert="le prix unitaire sélectionné"
                                >
                                <?php echo fontawesome('trash-alt');?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php $i++;?>
            <?php endforeach;?>
        </tbody>
    </table>
</div>