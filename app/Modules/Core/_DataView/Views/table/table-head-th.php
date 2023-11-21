<?php foreach($columns as $key=>$column):?>
    <?php if(!empty($column[1])):?>
        <th ban_order="<?php echo $key?>" 
            <?php if(!empty($order[0]) && $order[0]==$key):?>
                banData_order_select_<?php echo $order[1];?>
            <?php endif;?>
            <?php if(!empty($column[3])):?>
                title="<?php echo $column[3];?>"
            <?php endif;?>
            >
            <?php echo $column[0]?>
        </th>
    <?php else:?>
        <th class="ps-2"
            <?php if(!empty($column[3])):?>
                title="<?php echo $column[3];?>"
            <?php endif;?>
            >
            <?php echo $column[0]?>
        </th>
    <?php endif;?>   

<?php endforeach;?>