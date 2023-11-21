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
                                        <?php foreach($work->groups as $group):?>
                                            <tr>
                                                <td colspan="1">
                                                    <?php if(!empty($group->is_prior)):?>
                                                        <img src="./images/logos/homegrade-logo-round-red.png" height="30" width="30" alt="Logo travaux prioritaires"/>
                                                    <?php endif;?>
                                                    <?php if(!empty($group->is_recommended)):?>
                                                        <img src="./images/logos/homegrade-logo-round-green.png" height="30" width="30" alt="Logo travaux conseillés"/>
                                                    <?php endif;?>
                                                </td>
                                                <td colspan="5">
                                                    <?php echo $group->label_fr;?>
                                                </td>
                                                <td colspan="2" class="text-end">
                                                    Quantité :
                                                    <?php echo $group->quantity;?>
                                                    <?php echo $group->measure;?>
                                                </td>
                                                <td colspan="1" class="text-end"> PU </td>
                                                <td colspan="1" class="text-end"> HT </td>
                                                <td colspan="1" class="text-end"> TVA </td>
                                                <td colspan="1" class="text-end"> TVAC </td>
                                            </tr>
                                            <?php echo $group->roads_pdf;?>
                                            <tr>
                                                <td colspan="9" class="totalsub_label text-end">
                                                    Total par groupe de travaux
                                                </td>
                                                <td colspan="1" class="totalsub_label text-end"> <?php echo number_format($group->total->ht, 2, ',', '');?> </td>
                                                <td colspan="1" class="totalsub_label text-end"> <?php echo number_format($group->total->tva, 2, ',', '');?> </td>
                                                <td colspan="1" class="totalsub_label text-end"> <?php echo number_format($group->total->tvac, 2, ',', '');?> </td>
                                            </tr>
                                        <?php endforeach;?>
                                        <tr>
                                            <td colspan="9" class="total_label text-end">
                                                Total par ouvrage
                                            </td>
                                            <td colspan="1" class="total_label text-end"> <?php echo number_format($work->total->ht, 2, ',', '');?> </td>
                                            <td colspan="1" class="total_label text-end"> <?php echo number_format($work->total->tva, 2, ',', '');?> </td>
                                            <td colspan="1" class="total_label text-end"> <?php echo number_format($work->total->tvac, 2, ',', '');?> </td>
                                        </tr>
                                    <?php endif;?>
                                <?php endif;?>
                            <?php endforeach;?>
                        <?php endif;?>
                    <?php endif;?>
                <?php endforeach;?>
            <?php endif;?>
        <?php endif;?>
    <?php endforeach;?>
    <?php if(!empty($devis->comment_difficulty)):?>
        <tr>
            <td colspan="12">
                <b> Travaux que le conseiller juge trop difficiles à estimer : </b> <br>
                <?php echo $devis->comment_difficulty;?>
            </td>
        </tr>
    <?php endif;?>
    <tr>
        <td width="8.33%"></td> <td width="8.33%"></td> <td width="8.33%"></td> <td width="8.33%"></td>
        <td width="8.33%"></td> <td width="8.33%"></td> <td width="8.33%"></td> <td width="8.33%"></td>
        <td width="8.34%"></td> <td width="8.34%"></td> <td width="8.34%"></td> <td width="8.34%"></td>
    </tr>
</table>
<br>
