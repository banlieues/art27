<?php $this->extend('Layout\index'); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="text-center text-secondary mt-5">
    <h2 class="display-1 text-dark fw-bold">
        <?php echo fontawesome('ghost');?>
    </h2>
    <h3>
        <?php echo $titleView;?>
    </h3>
    <p>
        <?php if (!empty($message) && $message !== '(null)') : ?>
            <?php echo nl2br(esc($message)) ?>
        <?php else : ?>
            <?php echo t("Désolé, la page recherchée n'existe pas.", $namespace);?> <br>
            <b> <?php echo $url;?> </b>
        <?php endif ?>
    </p>
</div>

<?php $this->endSection();?>