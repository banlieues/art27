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
    <?php if($hierarchy == 'template'):?>
        <div class="form-group row">
            <label for="reportschema" class="col-sm-2 col-form-label">Schéma de base</label>
            <div class="col-10">
                <?php if(!empty($schema_name)):?>
                    <input type="text" class="form-control" value="<?php echo $schema_name;?>" disabled/>
                <?php else:?>
                    <select class="form-control reportschemaIdSelect" id="reportschemaIdSelect" name="id_base" onchange="js_get_schema_sections(this);">
                        <option selected></option>
                        <?php foreach($schema_list as $m) : ?>
                            <option value="<?php echo $m['id_report'];?>">
                                <?php echo $m['report_name'];?>
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
</fieldset>