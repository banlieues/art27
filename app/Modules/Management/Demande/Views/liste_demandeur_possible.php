<div class="row row-cols-1 row-cols-md-3 g-1">
    <?php if(!empty($contacts)):?>
        <?php foreach($contacts as $contact):?>
            <div class="col-md-6 col-12">
                <div class="card h-100">
                
                    <div class="card-body">
                        <small class="card-title"><b><?=$contact->prenom_personne?> <?=$contact->nom_personne;?> (id=<?=$contact->id_contact?>)</b></small>
                        <p class="text-body-secondary">
                        <small><?=$contact->email_personne;?></small>
                        </p>
                        <button style="font-size:10px !important" class="btn btn-sm btn-success btn_copy_index_field">Choisir</button>
                        <?php foreach($contact as $index=>$value):?>
                            <input class="copy_index_field" type="hidden" value="<?=$value?>" name="<?=$index?>">
                        <?php endforeach;?>    
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    <?php else:?>
        <p>Pas de demandeurs trouvés</p>
    <?php endif;?>
</div>