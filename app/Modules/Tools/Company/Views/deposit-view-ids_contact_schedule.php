<table class="table table-sm table-imbricated mt-2 w-auto">
    <thead>
        <tr>
            <th class="border-0"></th>
            <?php foreach($field->clock as $clock):?>
                <th class="border border-top-0">
                    <small> <?php echo $clock->label_fr;?> </small>
                </th>
            <?php endforeach;?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($field->day as $day):?>
            <tr>
                <td> <?php echo substr($day->label_fr, 0, 2);?> </td>
                    <?php foreach($field->clock as $clock):?>
                        <?php foreach($field->list as $elem):?>
                            <?php if($elem->id_day==$day->id && $elem->id_time==$clock->id):?>
                                <?php if(isset($ids_contact_schedule)) :?>
                                    <?php if(in_array($elem->id, $ids_contact_schedule)): ?>
                                        <td class="bg-success border w-auto px-3" title="<?php echo $clock->label_fr;?>">
                                            <!-- <input type="hidden" name="ids_contact_schedule[]" value="<?php echo $elem->id;?>"/> -->
                                    </td>
                                    <?php else:?>
                                        <td class="bg-light border w-auto px-3"></td>
                                    <?php endif; ?>
                                <?php else:?>
                                    <td class="bg-light border w-auto px-3"></td>
                                <?php endif;?>
                            <?php endif; ?>
                        <?php endforeach;?>
                    <?php endforeach;?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
