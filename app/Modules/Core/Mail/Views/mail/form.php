<?php if(empty($error)):?>
    <form id="mailForm" class="needs-validation" novalidate enctype="multipart/form-data">
        <?php if(!empty($hidden_input)) : foreach($hidden_input as $key=>$value):?>
            <input type="hidden" name="<?php echo $key;?>" value="<?php echo $value;?>"/>
        <?php endforeach; endif;?>
        <?php echo view('Mail\mail/form/fieldset_relation');?>
        <?php echo view('Mail\mail/form/fieldset_content');?>
    </form>
<?php else:?>
    [@developer] Les paramètres d'envoi de mail sont incomplets. Veuillez entrer des valeurs pour : <br>
    <br>
    <?php foreach($error as $er):?>
        - <?php echo $er;?> <br>
    <?php endforeach;?> 
<?php endif;?>