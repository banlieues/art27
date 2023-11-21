<!-- Recipient -->
<div class="mb-2 row">
    <label for="mail<?php echo ucfirst($recip_type);?>" class="col-sm-2 col-form-label"> 
        <!-- <button type="button" class="toggle_button btn btn-sm p-0 align-text-bottom" onclick="$('#mailCc_group').fadeToggle();"> 
            <small> Cc </small> 
        </button>
        <button type="button" class="toggle_button btn btn-sm p-0 align-text-bottom" onclick="$('#mailCci_group').fadeToggle();"> 
            <small> Cci </small>
        </button> -->
        <span class="ml-1"> <?php echo $title;?> </span> 
    </label>
    
    <div class="col-10 plusminus-group" id="mail<?php echo ucfirst($recip_type);?>">
        <?php if(!empty($recip_selected)):?>
            <div class="mb-1 row">
                <div class="col-10">
                    <!-- selected recipients -->
                    <?php echo view('Mail\mail/form/recip_select_multiple', [
                        'recips' => $recip_selected, 
                        'name_option' => $recip_type . '_option',
                        'selected' => true,
                    ]);?>
                </div>
            </div>
        <?php endif;?>

        <!-- text recipients -->
        <?php if(!empty($recip_text)): foreach($recip_text as $recip):?>
            <div class="row plusminus-row mb-1">
                <?php echo view('Mail\mail/form/recip_text', array('recip' => $recip));?>
            </div>
        <?php endforeach; endif;?>

        <div class="row plusminus-row mb-1">
            <?php echo view('Mail\mail/form/recip_text', array('recip' => null));?>
        </div>
        <div class="row plusminus-row plusminus-model mb-1" style="display: none;">
            <?php echo view('Mail\mail/form/recip_text', array('recip' => null));?>
        </div>
        
        <!-- unselected recipients -->
        <?php if(!empty($recip_unselected)):?>
            <div class="mb-1 row">
                <div class="col-10">
                    <!-- selected recipients -->
                    <?php echo view('Mail\mail/form/recip_select_multiple', [
                        'recips' => $recip_unselected, 
                        'name_option' => $recip_type . '_option',
                        'selected' => false,
                    ]);?>
                </div>
            </div>
        <?php endif;?> 
    </div>
</div>