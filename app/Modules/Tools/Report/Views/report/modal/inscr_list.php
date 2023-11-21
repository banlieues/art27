<?php if(isset($inscrs[0])):?>
    <div class="col-12"> Sélectionnez les inscriptions pour le publipostage du rapport. </div>
    <div class="col-12 py-2 px-4 check-all-group">
        <div class="form-check">
            <input type="checkbox"
                class="form-check-input checkboxList check-all-input"
                id="reportInscrAll"
            />
            <label for="reportInscrAll" class="form-check-label">Tous</label>
        </div>
        <?php foreach($inscrs as $inscr):?>
            <div class="form-check">
                <input type="checkbox"
                    class="form-check-input checkboxList check-all-target"
                    id="reportInscr_<?php echo $inscr['id_inscr'];?>"
                    name="inscr_ids"
                    value="<?php echo $inscr['id_inscr'];?>"
                />
                <label class="form-check-label" for="reportInscr_<?php echo $inscr['id_inscr'];?>"><?php echo $inscr['fullname'];?> <?php echo htmlspecialchars('<'.$inscr['email'].'>');?></label>
            </div>
        <?php endforeach;?>
        <input type="hidden" name="inscr_ids" value="" class="checkboxList"/>
    </div>
<?php else:?>
    <div class="col-12"> Il n'y a pas d'inscription selon les critères choisis. </div>
<?php endif;?>