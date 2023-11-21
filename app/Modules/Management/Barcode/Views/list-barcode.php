<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>
 
<form method="post" action="<?=base_url()?>barcode-controller/partenaire_social">

    <select name="id_partenaire_social">
        <option>Veuillez choisir un partenaire social</option>
        <?php foreach($partenaires as $partenaire):?>
            <option value="<?=$partenaire->id_partenaire_social?>"><?=$partenaire->nom_partenaire_social;?></option>
        <?php endforeach;?>
    </select>

    <input placeholder="nombre de barre de code" type="text" name="number_barre">
    <button type="submit">Envoyer</button>

</form>


<?php //debug($partenaires);?>



<?php $this->endSection(); ?>

