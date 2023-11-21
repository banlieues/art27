<?php if($tableau_fusion_direct)
{
    $this->extend('Layout\index');
    $this->section("body");

    echo "<h4><i class='".icon("doublon")."'></i> titleView</h4>";
}
?>
<form class="form_fusion">
    <input  type="hidden" class="entity_en_cours" value="<?=$entity?>">
    <?php if(count($values)>1):?>
        <div class="text-center">
            <button class="btn btn-sm btn-dark mt-2 mb-2 btn_submit">Fusionner</button>
        </div>
    <?php endif;?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table_fusion">
               
                <?php for($i=0;$i<count($labels);$i++):?>
                    <?php 
                        $is_affiche=FALSE;
                        $is_different=FALSE;
                        $compare=[];
                        for($j=0;$j<count($values);$j++)
                        {
                            if(!empty($values[$j][$i]))
                            {
                                $is_affiche=TRUE;
                            }
                            array_push($compare,$values[$j][$i]);
                            
                        }
                       // debug(array_unique($compare));
                        if(count(array_unique($compare))>1)
                        {
                            $is_different=TRUE;
                            //debug($compare);
                        }
                    ?>    

                    <?php if($is_affiche):?>
                        <tr <?php if($is_different):?> class="table-danger" <?php endif;?>>
                            <th class="col"><?=$labels[$i]?></th>
                            <?php for($j=0;$j<count($values);$j++):?>
                                <td class="col">
                                   <?php if($is_different):?> <input value="<?=$values[$j][0]?>" checked type="radio" name="<?=$indexes[$j][$i]?>"><?php endif;?> <?=$values[$j][$i]?> 
                                    <?php $id_entity_doublon[]=$values[$j][0];?>
                                </td>
                            <?php endfor;?>
                        </tr>
                    <?php endif;?>


                <?php endfor?>
                <?php
                        foreach(array_unique($id_entity_doublon) as $id_doublon):
                ?>
                            <input type="hidden" name="id_doublons[]" value="<?=$id_doublon?>">

                    <?php endforeach;?>
            </table>
           
        </div>
</form>


<?php if($tableau_fusion_direct)
{
    $this->endSection();

    $this->section("script_foot_injected");
}
?>
            <script>
                
            $(document).off("submit",".form_fusion").on("submit",".form_fusion",function()
                {

                var form=$(this);
                var entity=$(".entity_en_cours").val();
                
                $(".btn_submit",form).html('<i class="fa fa-spin fa-spinner"></i> Fusion en cours…');
                $(".table_fusion",form).css("opacity",'0.3');

                var dataString=form.serialize();
                var url="<?=base_url()?>/doublon/fusion/"+entity;
                
                jQuery.ajax
                        ({  
                        type:'POST',
                        url: url,
                        cache: false,
                        data: dataString,

                        success: function(html)
                        { 
                            
                            form.html(html);
                            
                            
                        }

                        })
                return false;
                
                });
            </script>

<?php if($tableau_fusion_direct)
{
    $this->endSection();
}

?>        
