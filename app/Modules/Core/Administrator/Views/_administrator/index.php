<?php $this->extend("Layout\index"); ?>

<?php $this->section("body"); ?>
<?php debugd('debug view Administrator\administrator/index');?>
<?php if(session('loggedUserRoleId')!=1):?>
    <div class="row">
        <div class="col-md-3">
            <?php echo view('Layout\user-sidebar');?>
        </div>
        <div class="col-md-9">
            <?php $this->renderSection("admin_body"); ?>
        </div>
    </div>
<?php else:?>        
    <?php $this->renderSection("admin_body"); ?>
<?php endif;?>


<?php $this->endSection(); ?>
