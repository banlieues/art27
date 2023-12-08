<?php  ?>

<?php $burgis_champs=["adresse_fr_bien","adresse_nl_bien","adresse_fr_bien_no_modif","adresse_nl_bien_no_modif"];?>
<?php 
$uri = current_url(true);
$path=$uri->getPath();  

if(strpos($path, "/contact/fiche") !== false){
    $no_direct_modif_indexes=array("demande_id","adresse_fr_bien_no_modif");
  } else{
    $no_direct_modif_indexes=array("prenom_personne","nom_personne","demande_id","adresse_fr_bien_no_modif");
  }

?>
<?php if(!in_array($index,$burgis_champs)):?>
        <?php if(in_array($index,$no_direct_modif_indexes)&&!empty($value)):?>
            <input disabled  name="<?=$index?><?=$numero_multiple?>" type="text" class="form-control" value="<?= $value?>">
        <?php else:?>
        <input  name="<?=$index?><?=$numero_multiple?>" type="text" class="form-control" value="<?= $value?>">
        <?php endif;?>
<?php else:?>

    <span class="c_translate"><span class="c_search_address">      

    <?php if($index=="adresse_nl_bien"||$index=="adresse_nl_bien_no_modif" ): $lg="NL"; else: $lg="FR"; endif; ?>
	
                <input autocomplete="off" name="<?=$index?><?=$numero_multiple?>" type="text" id="input_dao" class="search_address form-control input_dao" value="<?= $value?>" placeholder="Adresse <?=$lg?>" table="bien" key="id_bien" field_sql="adresse_fr">

                <div class="result-list-address" id="result-list-address" role="menu" aria-labelledby="dLabel" style="border: 1px solid rgb(238, 238, 238); max-height: 390px; overflow-y: auto; padding-right: 15px; padding-left: 15px; margin: auto;"></div>
        </span>
 

    <a href="" class="btn btn-dark btn-small translatebrugis">Recherche Brugis <?=$lg?></a>
</span>

   

    <?php endif;?>
