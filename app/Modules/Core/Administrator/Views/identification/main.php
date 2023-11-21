<?php $this->extend('Layout\identification'); ?>
<?php $this->section("body"); ?>

<?php //on regarde si on est dans un contexte d'appel distant
        $col_container=6;
        $col_form=7;
        $is_image=TRUE;
        $col_offset=3;
        if(session('is_distant'))
        {
            $col_container=12;
            $col_form=12;
            $is_image=FALSE;
            $col_offset=12;
        }
           

?>

<div class="row mt-5">
    <div class="col-md-<?=$col_container?> offset-md-<?=$col_offset?>">
        <?php echo $this->include('Layout\flash'); ?>
        <div class="card mb-3 border-top-<?php echo $themes->main->color;?>">
            <div class="row g-0">
                <?php if($is_image):?>
                <div class="col-md-5">
                    <img src="<?php echo base_url(get_random_image('images/login'));?>" alt="<?php echo $themes->main->name;?> images" class="img-cover" />
                </div>
                <?php endif;?>
                <div class="col-md-<?=$col_form?>">
                    <div class="card-body">
                        <img src="<?php echo base_url($themes->main->logo);?>"
                            alt="<?php echo $themes->main->name;?> Logo"
                            style="max-height:100px"
                        />
                        <h3 class="pt-3"><?php echo $title; ?><i class="fa fa-lock float-end"></i></h3>
                        <p><?php echo $subtitle; ?></p>
                        
                        <?php $this->renderSection('identification-body');?>

                    </div>
                </div>
            </div>
        </div>

        <?php echo view('Administrator\identification/copyright'); ?>

    </div>
</div>

<?php $this->endSection(); ?>
