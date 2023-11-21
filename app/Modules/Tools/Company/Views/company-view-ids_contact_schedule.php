<!-- Contact schedule -->

<div class="border rounded p-2">
    <div class="row">
        <div class="col"></div>
        <div class="col text-center"> Tout cocher </div>
        <?php foreach($field->clock as $clock):?>
            <div class="col text-center">
                <label class="text-center"> <?php echo $clock->label;?> </label>
            </div>
        <?php endforeach;?>
    </div>
    <?php foreach($field->day as $day):?>
        <div class="row check-all-group">
            <label class="col"> <?php echo $day->label;?> </label>
            <div class="col text-center">
                <input type="checkbox"
                    class="form-check-input check-all-input"
                    name="ids_contact_schedule[]"
                />
            </div>
            <?php foreach($field->time as $time):?>
                <?php foreach($field->list as $row): if($row->id_day==$day->id && $row->id_time==$time->id):?>
                    <div class="col text-center">
                        <input type="checkbox"
                            class="form-check-input check-all-target"
                            name="ids_contact_schedule[]"
                            value="<?php echo $row->id;?>"
                            <?php if(isset($company->ids_contact_schedule)) :?>
                                <?php foreach($company->ids_contact_schedule as $id):?>
                                    <?php if($id==$row->id):?>
                                        checked
                                        <?php break;?>
                                    <?php endif;?>
                                <?php endforeach;?>
                            <?php endif;?>
                        />
                    </div>
                <?php endif; endforeach;?>
            <?php endforeach;?>
        </div>
    <?php endforeach;?>       
</div>