<main class="content">
    <div class="container-fluid">

        <!-- Flashdata -->
        <?php echo $this->include('Layout\flash'); ?>

        <?php if(empty($errorPage)):?>
            <?php if(
                session('loggedUserId')!=$user->id ||
                $user->role_id!=1
                ):?>
                <div class="row p-2">
                    <div class="col-md-3">

                        <?php echo $this->include('Layout\user-sidebar');?>

                    </div>

                    <div class="col-md-9">
                        
                        <?php echo $this->renderSection('body'); ?>

                    </div>
                </div>
            <?php else:?>

                <!-- Body -->
                <?php echo $this->renderSection('body'); ?>
                        
            <?php endif;?>
        <?php else:?>

            <!-- Body -->
            <?php echo $this->renderSection('body'); ?>
                        
        <?php endif;?>

    </div>
</main>