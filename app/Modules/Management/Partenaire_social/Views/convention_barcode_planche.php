<?php $this->extend('\Partenaire_social\view-partenaire_social-base'); ?>
<?php $this->section('partenaire_social-body');?>

<style>
    .print_a4{
        border: 1px solid black;
        background-color:  #FFF !important;
        width: 210mm;
        height: 297mm;
       
        padding: 0;
    }

    .print_tr{

        padding:0mm;
    }

    .print_td{

        width:70mm !important;
        height: 42.3mm !important;
        border: 1px solid black;
        }

</style>

<?php

    $nb_col=3;
    $nb_ligne=7;

    //initialize
    $col_encours=1;
    $barre_code_encours=3;
    $page_encours=1;

    //nombre de barre code
    $total_by_page=$nb_col*$nb_ligne;

   
    
    //collection image collection decale consiste à ajouter des i
    //je décale pour récupérer papier

    $images_decales=[];

    for($i=0;$i<$a_partir_de;$i++)
    {
        array_push($images_decales,null);
    }

    foreach($images as $im)
    {
        array_push($images_decales,$im);
    }

    //debug($images_decales);
     //Nombre de pages à produire
    $total_pages=count($images_decales)/$total_by_page;
    $total_pages=round($total_pages);
   
   $barcodes=array_chunk($images_decales,$total_by_page);


  
?>


<div class="row">
    <div class="col-2">
        
            <table class="table-bordered table m-5" style="width:100px; position:fixed;">
            <?php $num_position=1;?>
            <tr>
                <td colspan="<?=$nb_ligne?>">Choisir Case départ</td>
            </tr>
            <?php for($j=0;$j<$nb_ligne;$j++):?>
            <tr>
                <?php for($k=0;$k<$nb_col;$k++):?>
                    <td>
                        <form action="<?=base_url()?>partenaire_social/barcode_generator" method="post">

                        <input type="hidden" name="id_partenaire_social" value="<?=$id_partenaire_social?>">
                        <input type="hidden" name="number_barre" value="<?=$number_barre?>">
                        <input type="hidden" name="annee_select" value="<?=$annee_select?>">
                        <input type="hidden" name="mois_select" value="<?=$mois_select?>">
                        <input type="hidden" name="mois_select_sql" value="<?=$mois_select_sql?>">
                        <input type="hidden" name="a_partir_de" value="<?=$num_position-1?>">
                        <input type="hidden" name="no_stat" value="0">

                        <?php foreach($id_barcodes as $ibarcode):?>
                                    <input type="hidden" name="id_barcodes[]" value="<?=$ibarcode?>">
                        <?php endforeach;?>

                        <input type="hidden" name="nb_produire" value="<?=$nb_produire?>">
                        
                        <?php if($a_partir_de+1<=$num_position):?>
                            <button type="submit" class="btn btn-success"><?php echo $num_position;?></button>
                        <?php else:?>
                        <button type="submit" class="btn btn-dark"><?php echo $num_position;?></button>
                        <?php endif;?>
                        </form>
                    </td>
                    <?php $num_position=$num_position+1;?>
                <?php endfor;?>
            </tr>
           
            <?php endfor;?>


            </table>
            
    </div>

    <div class="col-10">

        <p class="text-center"> <small>Nombre de codes barre produits: <?=count($images)?></small></p>
        <h3 class="text-center">Prévisualisation</h3>

        <?php foreach($barcodes as $images):?>
            <?php $new_a_partir_de=0;?>
            <div class="mt-4 mb-2">
                <p class="text-center"><small><b>-- Page <?=$page_encours?>/<?=$total_pages+1;?> --</b></small></p>
                
                <div class="print_a4 mx-auto align-self-center text-center">
                    <table>

                            <?php foreach($images as $reference=>$image):?>

                                <?php if($col_encours==1):?>
                                <tr class="print_tr">
                                
                                <?php endif;?>
                                    
                                    <td class="print_td">
                                        <?php if(!empty($image)):?>
                                            <?=$mois_select?> <?=$annee_select?><br><?php echo '<img width="95%" src="data:image/png;base64,' . $image . '">'; ?>
                                        <?php endif;?>
                                    </td>

                                <?php if($col_encours>=$nb_col):?>
                                </tr>
                                <?php $col_encours=1;?>
                                <?php else: ?>
                                    <?php $col_encours=$col_encours+1;?>
                                <?php endif;?>
                            <?php $new_a_partir_de=$new_a_partir_de+1;?>
                            <?php endforeach;?>
                    </table>
                </div>
                <?php $page_encours=$page_encours+1;?>
            </div>

        <?php endforeach;?>
        <?php if(isset($new_a_partir_de)):?>
            <?php session()->set("a_partir_de",$new_a_partir_de);?>
        <?php endif;?>
    </div>
</div>
<?php $this->endSection(); ?>

