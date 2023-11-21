<form id="<?php echo $form_id;?>" method="post" action="<?php echo base_url('report/block_choice');?>">
    <input type="hidden" name="id_report_block" value="<?php echo $id_report_block;?>"/>
    <div class="row">
        <div class="col-6 border-right">
            <div class="form-check">
                <input type="radio" class="form-check-input" name="is_old" value="1" 
                    onclick="$('button[form=&quot;<?php echo $form_id;?>&quot]').removeClass('d-none');"
                />
                <label class="form-check-label"> 
                    Ancienne version <span class="badge badge-warning">Non mis à jour</span>
                </label>
            </div>
            <hr>
            <?php echo $file_current_html;?>
        </div>
        <div class="col-6">
            <div class="form-check">
                <input type="radio" class="form-check-input" name="is_old" value="0"
                    onclick="$('button[form=&quot;<?php echo $form_id;?>&quot]').removeClass('d-none');"
                />
                <label class="form-check-label"> Version mise à jour </label>
            </div>
            <hr>
            <?php echo $file_html;?>
        </div>
    </div>
</form>