<div id="InfoAnchor" class="card mb-4">
    <div class="card-header">
        Infos générales
    </div>
    <div class="card-body">
        <div class="row">
            <label class="col-4 col-form-label small"> Demandeur </label>
            <div class="col-8">
                <div class="form-control-plaintext form-control-plaintext-sm">
                    <?php echo fullname($devis->prenom_contact, $devis->nom_contact);?>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-4 col-form-label small"> Catégorie du demandeur </label>
            <div class="col-8">
                <div class="form-control-plaintext form-control-plaintext-sm">
                    <?php echo $devis->bien_contact_type;?>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-4 col-form-label small"> Adresse du bien </label>
            <div class="col-8">
                <div class="form-control-plaintext form-control-plaintext-sm">
                    <?php echo $devis->adresse_fr;?>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-4 col-form-label small"> Type de bien </label>
            <div class="col-8">
                <div class="form-control-plaintext form-control-plaintext-sm">
                    <?php echo $devis->id_type_label;?>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-4 col-form-label small"> Etage du logement </label>
            <div class="col-8">
                <div class="form-control-plaintext form-control-plaintext-sm">
                    <?php echo $devis->etage_logement;?>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-4 col-form-label small"> Conseiller en charge </label>
            <div class="col-8">
                <div class="form-control-plaintext form-control-plaintext-sm">
                    <?php echo fullname($devis->user_prenom, $devis->user_nom);?>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mb-1">
            <label class="col-4 col-form-label small"> Date de la visite </label>
            <div class="col-8">
                <div class="form_read">
                    <div class="form-control-plaintext form-control-plaintext-sm">
                        <?php echo convert_date_en_to_fr_with_h($devis->date_visite, false);?>
                    </div>
                </div>
                <div class="form_update" style="display: none;">
                    <input type="text"
                        name="date_visite"
                        form="DevisForm"
                        class="datepicker w-auto"
                        value="<?php echo $devis->date_visite;?>"
                    />
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <label class="col-4 col-form-label small"> Travaux que le conseiller juge trop difficile à estimer </label>
            <div class="col-8">
                <div class="form_read">
                    <div class="form-control-plaintext form-control-plaintext-sm">
                        <?php echo $devis->comment_difficulty;?>
                    </div>
                </div>
                <div class="form_update" style="display: none;">
                    <textarea name="comment_difficulty" form="DevisForm" class="form-control"
                    ><?php echo $devis->comment_difficulty;?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>