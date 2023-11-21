<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<!-- <h4 class="mb-4"><?php // echo $title.' : '.$subtitle; ?></h4> -->

<div class="row row-cols-1 row-cols-md-2">
    <div class="col">
        <div class="card flex-fill border-top-theme mb-4">
            <div class="card-header">
                Codeigniter 4 <i class="fas fa-fire float-end mt-1 text-theme"></i>
            </div>
            <img src="public/images/tutorials/default.jpg" class="card-img-top" alt="Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo lang('Tutorials.ci4'); ?></h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="<?php echo base_url('tutorials/ci4') ?>" class="btn btn-primary"><?php echo lang('Tutorials.findmore'); ?></a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card flex-fill border-top-theme mb-4">
            <div class="card-header">
                Bootstrap 5 <i class="fab fa-bootstrap float-end mt-1 text-theme"></i>
            </div>
            <img src="public/images/tutorials/default.jpg" class="card-img-top" alt="Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo lang('Tutorials.bs5'); ?></h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="<?php echo base_url('tutorials/bs5') ?>" class="btn btn-primary"><?php echo lang('Tutorials.findmore'); ?></a>
            </div>
        </div>
    </div>
    <!--
    <div class="col"></div>
    <div class="col"></div>
    -->
</div>

<?php $this->endSection(); ?>
