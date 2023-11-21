<!-- Emodels -->
<?php if(!empty($templates)):?>
    <div class="mb-2 row">
        <label for="mailTemplate" class="col-sm-2 col-form-label">Modèles d'email</label>
        <div class="col-10">
            <select type="text" class="form-control" id="mailTemplate"
                onchange="template_select(this);"
                name="id_template"
                >
                <option value="" selected> - Pas de modèle d'email - </option>
                <?php foreach($templates as $template):?>
                    <option value="<?php echo $template->id_template;?>">
                        <?php echo $template->label;?>
                    </option>
                <?php endforeach; ?>
            </select>            
        </div>
    </div>
<?php endif;?>
<hr>

<!-- Subject -->
<div class="mb-2 row">
    <label for="mailSubject" class="col-sm-2 col-form-label">Sujet</label>
    <?php if(!empty($reference)):?>
        <!-- Reference -->
        <div class="col-2">
            <input type="text" class="form-control font-italic" name="reference" value="<?php echo $reference;?>" readonly/>
        </div>
        <div class="col-8">
            <input type="text" class="form-control" id="mailSubject" name="subject" value="<?php if(isset($subject)) {echo $subject;} ?>"/>
        </div>
    <?php else:?>
        <div class="col-10">
            <input type="text" class="form-control" id="mailSubject" name="subject" value="<?php if(isset($subject)) {echo $subject;} ?>"/> 
        </div>            
    <?php endif;?>
</div>

<!-- Message -->
<div class="mb-2 row">
    <label for="mailMessage" class="col-sm-2 col-form-label"> 
        Message 
        <span class="ml-1" data-bs-toggle="tooltip" data-html="true" title="
            <div class='p-1 text-left'>
                ASTUCES : <br>
                - Pour faire une saut à la ligne sans marge inférieure : <code> SHIFT + ENTER </code> <br>
            </div>
        "><?php echo $themes->info->icon;?></span>
    </label>
    <div class="col-sm-10">
        <div class="invalid-feedback">
        </div>
        <div class="summernote" id="mailMessage" name="message"> <?php if(isset($message)) echo $message;?> </div>
    </div>
</div>

<!-- Attachments -->
<div class="mb-2 row">
    <label for="mailSubject" class="col-sm-2 col-form-label">Pièces jointes</label>
    <div class="col-10 plusminus-group" id="mailAttachments">
        <!-- Attachments linked to this email -->
        <?php echo view('Mail\mail/form/attachment_selected');?>

        <!-- Attachments to upload -->
        <div class="row plusminus-row mt-2">
            <?php echo view('Mail\mail/form/attachment_model');?>
        </div>        
        <div class="row plusminus-row plusminus-model" style="display: none;">
            <?php echo view('Mail\mail/form/attachment_model');?>
        </div>
    </div>       
</div>

<!-- Signature -->
<div class="mb-2 row justify-content-between">
    <div class="col-sm-10 offset-2">
        <div class="d-flex pt-2">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="isSignature" value="1"
                    <?php if(!empty($isSignature)):?> checked <?php endif;?>
                    />
                <label class="form-check-label text-left text-nowrap"> Insérer la signature </label>
            </div>
            <div class="px-4" id="mailSignature"><?php echo $signature;?></div>
        </div>
    </div>
</div>


