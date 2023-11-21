<?php $this->extend("Layout\index"); ?>

<?php $this->section("body"); ?>

<?php if(session('loggedUserRoleId')==1 && $id_user==session('loggedUserId')):?>
    <div class="row p-2">
        <div class="col-md-3">
            <?php echo $this->include('Layout\user-sidebar');?>
        </div>
        <div class="col-md-9">
            <?php echo $this->renderSection('user-body');?>
        </div>
    </div>
<?php else:?>
    <?php echo $this->renderSection('user-body');?>
<?php endif;?>

<?php $this->endSection(); ?>
