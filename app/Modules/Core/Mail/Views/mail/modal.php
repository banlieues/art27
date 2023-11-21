<?php $this->extend('Custom\modal');?>

<?php $this->section('modal_size');?> modal-xl <?php $this->endSection();?>
<?php $this->section('modal_title');?> Modifier le brouillon <?php $this->endSection();?>

<?php $this->section('modal_buttons');?>
    <?php if(!empty($id_email)) echo $button_delete;?> 
    <?php echo $button_send . $button_save;?>
<?php $this->endSection();?>

<?php $this->section('modal_close_text');?>
    Annuler
<?php $this->endSection();?>

<!-- Error -->
<?php if(!empty($error)) : $this->section('modal_error');?>
    [@developer] Les paramètres d'envoi de mail sont incomplets. Veuillez entrer des valeurs pour : <br>
    <br>
    <?php foreach($error as $er):?>
        - <?php echo $er;?> <br>
    <?php endforeach;?> 
<?php $this->endSection(); endif;?> 

<!-- ------------------------------------------------------------ -->
<!-- ------------------------------------------------------------ -->

<?php if(empty($error)) : $this->section('modal_body');?>

<!-- Fontawesome -->
<link rel="stylesheet" href="<?php echo base_url('node_modules/@fortawesome/fontawesome-free/css/all.min.css'); ?>">
<script src="<?php echo base_url('node_modules/@fortawesome/fontawesome-free/js/all.min.js');?>"></script>

<!-- Summernote -->
<link rel="stylesheet" href="<?php echo base_url('node_modules/summernote/dist/summernote-lite.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('styles/summernote_custom.css'); ?>">
<script src="<?php echo base_url('node_modules/summernote/dist/summernote-lite.js'); ?>"></script>
<script src="<?php echo base_url('node_modules/summernote/dist/lang/summernote-fr-FR.min.js'); ?>"></script>


<!-- Bootstrap -->
<link rel="stylesheet" href="<?php echo base_url('node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>">
<script src="<?php echo base_url('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>

<!-- Invalid fields -->
<div id="invalid_fields"></div>

<!-- Form -->
<?php echo view('Mail\mail/form');?>

<?php echo view('Mail\js/js_mail');?>
<?php echo view('Components\js/summernote');?>

<?php $this->endSection(); endif?>