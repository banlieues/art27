<div class="form-group row">
    <div class="col-12">
        Sélectionnez les critères de sélection pour lister les inscriptions.
    </div>
</div>
<!--Cycle-->
<div class="form-group row">
    <label for="reportModalCycleSelect" class="col-sm-2 col-form-label">Cycle</label>
    <div class="col-sm-10">
         <select type="text" class="form-control" id="reportModalCycleSelect" name="id_cycle" onchange="js_get_inscrs_by_cycle(this);">
             <option class="empty" disabled selected></option>
             <?php foreach($cycles as $cycle) : ?>
                 <option value="<?php echo $cycle['id_cycle'];?>"><?php echo $cycle['cycle_title_fr'];?></option>
             <?php endforeach; ?>
         </select>
    </div>
</div>

<!--Event-->
<div class="form-group row">
    <label for="reportModalEventSelect" class="col-2 col-form-label">Evénement</label>
    <div class="col-10">
        <select type="text" class="form-control" id="reportModalEventSelect" name="id_event" onchange="js_get_inscrs_by_event(this);">
            <option class="empty" disabled selected></option>
            <?php foreach($events as $event) : ?>
                <option value="<?php echo $event['id_event'];?>"><?php echo $event['event_title_fr'];?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<!--<div class="form-group row">
    <div class="col-10 offset-2">
        <div class="form-check form-check-inline">
            <label class="form-check-label">Présents à la formation ?</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="presence" id="reportEventPresenceYes" value="yes">
            <label class="form-check-label" for="reportEventPresenceYes">Oui</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="presence" id="reportEventPresenceNo" value="no">
            <label class="form-check-label" for="reportEventPresenceNo">Non</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="presence" id="reportEventPresence">
            <label class="form-check-label" for="reportEventPresence" checked>Peu importe</label>
        </div>
    </div>
</div>-->

<!--Inscriptions-->
<div class="form-group row reportInscrList"></div>

