<form id="reportSectionSearch">
    <p class="w-100 text-center mb-4"> Critères de recherche </p>
    <div class="form-group row">
        <label for="reportSectionTag" class="col-2 col-form-label"> Tags </label>
        <div class="col-10">
            <select id="reportSectionTag" name="tags[]" class="select-picker w-100" title=" " multiple data-size="5" data-live-search="true" data-style="" data-style-base="form-control">
                <option value=""></option>
                <?php foreach($tags as $tag):?>
                    <option value=<?php echo $tag['id_tag'];?>><?php echo $tag['name'];?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="reportSectionThematique" class="col-2 col-form-label"> Thématique </label>
        <div class="col-4">
            <select id="reportSectionThematique" name="id_thematique" class="select-picker w-100" title=" "  data-size="5" data-live-search="true" data-style="" data-style-base="form-control">
                <option value=""></option>
                <?php foreach($th_lists['liste_thematique']['elements'] as $elem):?>
                    <option value=<?php echo $elem->id;?>><?php echo $elem->label;?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="py-2"> </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group row justify-content-center">
                <button type="button" class="btn" data-bs-toggle="collapse" data-bs-target="#collapseSousThem"> 
                    <span class="pr-4"><?php echo fontawesome('chevron-down');?></span>Sous-thématiques <span class="pl-4"><?php echo fontawesome('chevron-down');?></span> 
                </button>
            </div>
            <div id="collapseSousThem" class="collapse">
                <?php foreach($th_lists as $table):?>
                    <?php if(preg_match('/^(th_)/', $table['label']) > 0):?>
                        <div class="form-group row">
                            <label for="section_<?php echo $table['label'];?>" class="col-4 col-form-label"> <?php echo ucfirst(str_replace('_', ' ', explode('th_', $table['label'])[1]));?> </label>
                            <div class="col-8">
                                <select id="section_<?php echo $table['label'];?>" name="<?php echo 'id_' . $table['label'];?>" class="select-picker w-100" title=" " data-size="5" data-live-search="true" data-style="" data-style-base="form-control">
                                    <option value=""></option>
                                    <?php foreach($table['elements'] as $elem):?>
                                        <option value="<?php echo $elem->id;?>"> <?php echo $elem->label;?> </option>
                                    <?php endforeach;?>    
                                </select>
                            </div>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>
        <div class="col-6 border-left">
            <div class="form-group row justify-content-center">
                <button type="button" class="btn" data-bs-toggle="collapse" data-bs-target="#collapseSousSousThem"> 
                    <span class="pr-4"><?php echo fontawesome('chevron-down');?></span>Sous-sous-thématiques <span class="pl-4"><?php echo fontawesome('chevron-down');?></span>
                </button>
            </div>
            <div id="collapseSousSousThem" class="collapse">
                <?php foreach($th_lists as $table):?>
                    <?php if(preg_match('/^(ths_)/', $table['label']) > 0):?>
                        <div class="form-group row">
                            <label for="section_<?php echo $table['label'];?>" class="col-4 col-form-label"> <?php echo ucfirst(str_replace('_', ' ', explode('ths_', $table['label'])[1]));?> </label>
                            <div class="col-8">
                                <select id="section_<?php echo $table['label'];?>" name="<?php echo 'id_' . $table['label'];?>" class="select-picker w-100" title=" " data-size="5" data-live-search="true" data-style="" data-style-base="form-control">
                                    <option value=""></option>
                                    <?php foreach($table['elements'] as $elem):?>
                                        <option value="<?php echo $elem->id;?>"> <?php echo $elem->label;?> </option>
                                    <?php endforeach;?>    
                                </select>
                            </div>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-sm btn-secondary" form="reportSectionSearch" onclick="section_search(this);"> 
            Rechercher
        </button>
    </div>
</form>
<div id="reportSectionResults">
</div>
