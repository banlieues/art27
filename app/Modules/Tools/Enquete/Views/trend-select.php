<div class="row mb-4">
    <div class="col-auto">
        <div class="form-group row align-items-center">
            <label class="col-auto mb-0"> Courbe </label>
            <div class="col-auto">
                <select role="button" class="form-control" onchange="window.location = $(this).val();">
                    <option disabled selected> - Sélectionner une courbe de tendance - </option>
                    <?php foreach($trends as $t):?>
                        <option value="<?php echo base_url('enquete/trend/' . $t->reference);?>"
                            <?php if(!empty($reference) && $t->reference==$reference):?>    
                                selected
                            <?php endif;?>
                            >
                            <?php echo $t->title;?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-auto">
        <div class="form-group row align-items-center">
            <label class="col-auto mb-0"> Intervalle de temps </label>
            <div class="col-auto">
                <select role="button" class="form-control" name="timerange" onchange="set_timerange(this);">
                    <?php foreach($timerange_list as $tr):?>
                        <option value="<?php echo $tr->ref;?>" 
                            <?php if(
                                (
                                    session('trend') && 
                                    isset(session('trend')->timerange)  && 
                                    session('trend')->timerange==$tr->ref
                                ) || (
                                    !session('trend') && 
                                    $tr->ref=='month'
                                )
                            ):?> 
                                selected 
                            <?php endif;?>
                            > 
                            <?php echo $tr->label;?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <?php if(!empty($reference)):?>
        <div class="col-auto">
            <a 
                href="javascript:js_download_canvas_summary('trend')" 
                id="downloadTrends" 
                class="btn btn-sm" 
                title="Télécharger la courbe de tendance"
                > 
                <?php echo fontawesome('download');?>
            </a>
        </div>
    <?php endif;?>
</div>