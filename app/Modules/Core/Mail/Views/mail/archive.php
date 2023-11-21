<?php $this->extend('Custom\modal');?>

<?php $this->section('modal_title');?> Archiver l'email <?php $this->endSection();?> <!-- Title -->
<?php $this->section('modal_close_text');?> Annuler <?php $this->endSection();?> <!-- Close button text -->
<?php $this->section('modal_buttons'); echo $button_archive_confirm; $this->endSection();?> <!-- Footer buttons -->
<?php if(!empty($error)) : $this->section('modal_error'); echo $error; $this->endSection(); endif;?> <!-- Error -->

<!-- ------------------------------------------------------------ -->
<!-- ------------------------------------------------------------ -->

<!-- Modal body -->
<?php if(empty($error)): $this->section('modal_body');?>

Vous êtes sur le point d\'archiver un email : <br><br>
<span class="mx-4"></span> - Auteur : <?php echo $email->sender->name;?> <?php echo $email->sender->lastname;?> <br>
<span class="mx-4"></span> - Sujet : <?php echo $email->subject;?> <br>
<span class="mx-4"></span> - Date d'envoi : <?php echo $email->created_at;?> <br><br>
Veuillez confirmer votre action.

<?php $this->endSection(); endif;?>

