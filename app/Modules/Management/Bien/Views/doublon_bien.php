<div class="row row-cols-1 row-cols-md-3 g-1">
    <?php foreach($biens as $bien):?>
        <div class="col-md-6 col-12">
            <div class="card h-100">
            
                <div class="m-0 card-body">
                    <small class="card-title">
                        <i class="fa fa-building"></i>
                        <?=$bien->type?> | <?=$bien->adresse_fr;?> | <?=$bien->adresse_nl?>
                        <?php if(!empty($bien->etage_logement)):?>
			               | Etage: <?=$bien->etage_logement;?>
			            <?php endif; ?>
                        <?php if(!empty($bien->bt)):?>
                           | Bt: <?=$bien->bt?>
                            <?php endif;?>
                    
                    </small>
                    <div>
                        <button id_entity="<?=$bien->id_bien?>" entity="bien" style="font-size:10px !important" class="attacher_directement btn btn-sm btn-success">Choisir</button>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach;?>
</div>