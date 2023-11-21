<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<fieldset id="reportIdentificationFieldset">
    <legend id="reportIdentificationLegend"> Identification </legend>
    <div class="form-group row">
        <label for="reportName" class="col-sm-2 col-form-label">Nom du <?php echo $title;?></label>
        <div class="col-10">
            <input type="text" class="form-control" id="reportName" name="report_name" 
                value="<?php if(isset($report_name)) {echo $report_name;} ?>" required/>
            <div class="invalid-feedback">
                Veuillez entrer un nom de rapport.
            </div>
        </div>
    </div>
    <?php if($hierarchy=='publication'):?>
        <div class="form-group row">
            <label for="reportDemandeur" class="col-sm-2 col-form-label">Demandeur</label>
            <div class="col-auto">
                <input type="text" class="form-control persons-autocomplete" id="reportDemandeurAutocomplete" autocomplete="off"
                    value="<?php if(isset($demandeur)) echo $demandeur;?>" target="reportIdDemandeur" required
                    />
                <div class="invalid-feedback mb-2"> 
                    Il n'y a pas de personne correspondant au nom encodé. Veuillez sélectionner un nom de la liste. 
                </div>
                <input type="hidden" id="reportIdDemandeur" class="autocomplete-input" name="id_person" value="<?php if(isset($id_person)) echo $id_person;?>"/>
            </div>
            <div id="reportDemandeurDetails"></div>
        </div>
        <div class="form-group row">
            <label for="reportIdDemande" class="col-2 col-form-label">Numero de la demande</label>
            <div class="col-auto">
                <select type="text" class="form-control" id="reportIdDemande" name="id_demande">
                </select>
            </div>
            <div id="reportDemandeDetails"></div>
        </div>
    <?php endif;?>
    <?php if($hierarchy == 'template' || $hierarchy=='publication'):?>
        <div class="form-group row">
            <label for="reportBase" class="col-sm-2 col-form-label">
                <?php if($hierarchy == 'template'):?> Sur base du schéma
                <?php elseif($hierarchy=='publication'):?> Sur base du modèle
                <?php endif;?>
            </label>
            <div class="col-10">
                <?php if(!empty($base_name)):?>
                    <input type="text" class="form-control" value="<?php echo $base_name;?>" disabled/>
                <?php else:?>
                    <select class="form-select reportIdBaseSelect" id="reportIdBaseSelect" name="id_base" onchange="js_get_report_sections(this, '<?php echo $hierarchy;?>');" required>
                        <option selected></option>
                        <?php foreach($report_list as $r) : ?>
                            <option value="<?php echo $r['id_report'];?>">
                                <?php echo $r['report_name'];?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif;?>
            </div>
        </div>   
    <?php endif;?>
<!--    <div class="form-group row">
        <label for="reportCycle" class="col-2 col-form-label">Restreindre au cycle</label>
        <div class="col-10">
            <select type="text" class="form-control" id="reportCycle" name="id_cycle">
                <option disabled selected></option>
                <?php // foreach($cycles as $cycle) : ?>
                    <option value="<?php // echo $cycle['id_cycle'];?>" <?php // if(isset($id_cycle) && $cycle['id_cycle']==$id_cycle):?> selected <?php // endif;?>>
                        <?php // echo $cycle['cycle_title_fr'];?>
                    </option>
                <?php // endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="reportEvent" class="col-2 col-form-label">Restreindre à l'événement</label>
        <div class="col-10">
            <select type="text" class="form-control" id="reportEvent" name="id_event">
                <option disabled selected></option>
                <?php // foreach($events as $event) : ?>
                    <option value="<?php // echo $event['id_event'];?>" <?php // if(isset($id_event) && $event['id_event']==$id_event):?> selected <?php // endif;?>>
                        <?php // echo $event['event_title_fr'];?>
                    </option>
                <?php // endforeach; ?>
            </select>
        </div>
    </div>-->
    <div class="form-group row">
        <label for="reportComment" class="col-sm-2 col-form-label">Remarques</label>
        <div class="col-10">
            <textarea class="form-control" id="reportComment" name="comment"> <?php if(isset($comment)) {echo $comment;} ?> </textarea>
        </div>
    </div>
</fieldset>