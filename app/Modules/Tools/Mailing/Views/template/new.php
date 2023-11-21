<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Nouveau modèle d'email
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<form id="templateNewForm" method="post" action="<?php echo base_url('mailing/template/new');?>">
    <?php view('Mailing\template/form');?>
</form>

<?php $this->endSection(); ?>






