<div class="row mt-3">
<?php


foreach($demandes as $demande):
    if(isset($demande->id_demande)):
?>

    <div class="col-4">
    <a class="btn btn-info mb-2" href="<?=base_url()?>demande/fiche/<?=$demande->id_demande?>/0/<?=$id_message?>">
        <?=$demande->id_demande?> <?=$demande->date?> <?=$demande->type?>

    </a>
    </div>

<?php   
    endif;
endforeach;

?>
</div>
