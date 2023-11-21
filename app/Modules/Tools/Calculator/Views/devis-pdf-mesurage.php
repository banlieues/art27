<?php echo view('Calculator\devis-pdf-style');?>
<table>
    <?php foreach($roads as $road_0):?>
        <?php if(!empty($devis->works) && in_array($road_0->id_road, array_column($devis->works, 'id_them_parent'))):?>
            <tr>
                <td class="them0_label" colspan="12">
                    <?php echo $road_0->label_fr;?>
                </td>
            </tr>
            <?php if(!empty($road_0->children)):?>
                <?php foreach($road_0->children as $road_1):?>
                    <?php if(!empty($devis->works) && in_array($road_1->id_road, array_column($devis->works, 'id_them'))):?>
                        <tr>
                            <td class="them1_label" colspan="12">
                                <?php echo $road_1->label_fr;?>
                            </td>
                        </tr>
                        <?php if(!empty($devis->works)):?>
                            <?php foreach($devis->works as $work):?>
                                <?php if($work->id_them==$road_1->id_road):?>
                                    <tr>
                                        <td class="work_label" colspan="12"> Ouvrage : <?php echo $work->label;?> </td>
                                    </tr>
                                    <tr>
                                        <td class="work_annotation" colspan="12" style="font-style: italic; <?php if(empty($work->annotation)):?> padding: 0px; border-bottom: none; <?php endif;?>">
                                            <?php echo $work->annotation;?>
                                        </td>                                           
                                    </tr>
                                    <?php if(!empty($work->groups)):?>
                                        <tr>
                                            <td class="group_title group_label" colspan="5"> Groupe de travaux </td>
                                            <td class="group_title" colspan="2"> Quantité </td>
                                            <td class="group_title" colspan="1"> Mesure </td>
                                            <td class="group_title" colspan="4"> Indications </td>
                                        </tr>
                                         <?php foreach($work->groups as $group):?>
                                            <tr>
                                                <td class="group_label" colspan="5">
                                                    <?php echo $group->label_fr;?>
                                                </td>
                                                <td colspan="2">
                                                    <?php if(isset($group->quantity)):?>
                                                        <?php echo $group->quantity;?>
                                                        <?php echo $group->quantity ? $group->measure : '';?>
                                                    <?php else:?>
                                                        <span style="background-color: yellow; margin-right: 5px;">
                                                            ..............       
                                                        </span>
                                                    <?php endif;?>
                                                </td>
                                                <td colspan="1">
                                                    <?php echo $group->measure;?>
                                                </td>                                          
                                                <td colspan="4">
                                                    <?php echo $group->annotation_fr;?>
                                                </td>                                            
                                            </tr>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                <?php endif;?>
                            <?php endforeach;?>
                        <?php endif;?>
                    <?php endif;?>
                <?php endforeach;?>
            <?php endif;?>
        <?php endif;?>
    <?php endforeach;?>
</table>
<br>
