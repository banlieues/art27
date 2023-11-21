
<?php foreach($fields as $field=>$label):?>

<?php if(!empty($label[1])):?>
    <th 
        ban_order="<?=$field?>" 
        <?php if(!empty($orderBy) && $orderBy==$field):?>
            banData_order_select_<?php echo $orderDirection?>
        <?php endif;?>
        >
        <?php echo $label[0]?>
    </th>
<?php else:?>
    <th><?php echo $label[0]?></th>
<?php endif;?>   

<?php endforeach;?>