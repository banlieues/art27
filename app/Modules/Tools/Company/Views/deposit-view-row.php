<tr>
    <td class="col-3">
        <?php echo $titles->$ref;?>
    </td>
    <?php $i=0;?>
    <?php foreach($posts as $post):?>
        <?php if(count($posts)>1):?>
            <td class="align-top text-right">
                <input type="radio" class="invisible"
                    name="<?php echo $ref;?>" value="<?php echo $post->$ref->value;?>"
                    <?php if($i==0):?> id_deposit=<?php echo $post->id_deposit;?>
                    <?php else:?> id_company=<?php echo $post->id_company;?>
                    <?php endif;?>
                />
            </td>
        <?php endif;?>
        <td class="align-top <?php if($i==0):?>font-weight-bold<?php endif;?>">
            <?php echo $post->$ref->label;?>
        </td>
        <?php $i++;?>
    <?php endforeach;?>
</tr>