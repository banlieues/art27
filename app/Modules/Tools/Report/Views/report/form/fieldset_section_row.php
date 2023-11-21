<div id="reportSection_<?php echo $i;?>" class="form-group row plusminus-row reportSection" list-order="<?php echo $i;?>">
    <?php if(isset($id_report)):?> 
        <input type="hidden" name="sections[<?php echo $i;?>][id_report]" value="<?php echo $id_report;?>"> 
        <input type="hidden" name="sections[<?php echo $i;?>][id_file]" value="<?php if(isset($section['id_file'])) echo $section['id_file'];?>"/>
        <input type="hidden" name="sections[<?php echo $i;?>][isOld]" value="<?php if(isset($section['isOld'])) echo $section['isOld'];?>"/>
        <input type="hidden" name="i" value="<?php echo $i;?>"/>
    <?php endif;?>
    <label for="reportSectionRank_<?php echo $i;?>" class="col-1 reportSectionRank mt-1"> <?php echo $i+1;?> </label>
    <div class="col-10">
        <div class="row align-items-center">
            <div class="col-1"> 
                <button type="button" class="btn btn-link text-dark search-button" title="Rechercher un bloc par critères" onclick="show_section_search_modal(this);"> 
                    <?php echo fontawesome('info-circle');?> 
                </button> 
            </div>
            <div class="col-9">
                <div class="d-flex align-items-center">
                    <input class="form-control reportSectionName" value="<?php if(isset($section['section_name'])) echo $section['section_name'];?>" disabled/>
                    <input type="hidden" class="reportSectionId" name="sections[<?php echo $i;?>][id_section]" value="<?php if(isset($section['id_section'])) echo $section['id_section'];?>"/>
                </div>
            </div>
            <?php if(isset($section['needChoice']) && $section['needChoice']==1):?> 
                <div class="col-1">
                    <button type="button" class="btn text-danger" id_report=<?php echo $id_report;?> rank=<?php echo $section['rank'];?> onclick="js_show_section_version_choice_modal(this);" title="Le bloc a été mise à jour. Veuillez choisir quelle version vous souhaitez garder pour ce rapport. Sans confirmation, la version initiale sera retenue pour la publication du rapport."> <?php echo fontawesome('exclamation');?> </button>
                </div>
            <?php endif;?>
            <div class="col-1">
                <button type="button" class="btn reportSectionPreviewButton <?php if(!isset($section['id_file'])):?> d-none <?php endif;?>" 
                    <?php if(isset($section['id_file'])):?> id_file=<?php echo $section['id_file'];?> <?php endif;?>
                    onclick="js_show_section_html_modal(this);" title="Aperçu du bloc"> <?php echo fontawesome('eye');?> </button>
            </div>
        </div>
        <?php if(isset($section['isOld']) && $section['isOld']==1):?>
            <small class="text-secondary"> Cette version est une version antérieure du bloc. </small>
        <?php endif;?>
    </div>
    <div class="col-1">
        <div class="d-flex justify-content-between">
            <button type="button" class="btn remove-row pr-1" onclick="js_remove_row(this)"> <?php echo fontawesome('trash-alt');?> </button>
            <button type="button" class="btn add-row pl-1" onclick="sections_add_row(this)"> <?php echo fontawesome('plus');?> </button>
        </div>
    </div>
    <hr>
</div>
