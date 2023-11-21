<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    <?php echo $title;?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="text-center text-secondary mt-5">
    <h2 class="display-1 text-dark fw-bold">
        <?php echo fontawesome('ghost');?>
    </h2>
    <h3>
        Accès refusé
    </h3>
    <p>
        <?php if (!empty($message) && $message !== '(null)') : ?>
            <?php echo nl2br(esc($message)) ?>
        <?php else : ?>
            Désolé, vous ne pouvez accéder à la page recherchée <b> <?php echo $url;?> </b>.
        <?php endif ?>
    </p>
</div>

<?php $this->endSection();?>