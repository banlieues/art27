<div class="position-fixed">
    <div class="list-group">
        <div class="list-group-item list-group-item-action list-group-item-secondary p-2 d-flex justify-content-between"
            data-bs-toggle="collapse" data-bs-target="#sidebarAccount"
            >
            <div>
                <?php $themes->user->icon;?>
                <?php if($id_user!=session('loggedUserId')) echo "Gestion du compte"; else echo "Mon compte";?>
            </div>
            <div>
                <?php echo fontawesome('caret-down');?>
            </div>
        </div>
        <div id="sidebarAccount" 
            class="collapse show"
            >
            <a href="<?php echo base_url("user/profile?$id_user_get");?>" 
                class="list-group-item list-group-item-action <?php echo $context_sub == 'profile' ? 'list-group-item-' . $themes->user->color : 'list-group-item-action' ?>"
                >
                <i class="<?php echo icon('profile'); ?>"></i>
                <?php echo lang('User.profile'); ?>
            </a>
            <?php if(session('loggedUserRoleId')==1):?>
                <a href="<?php echo base_url("user/autorisation?$id_user_get") ?>" 
                    class="list-group-item list-group-item-action <?php echo $context_sub == 'autorisation' ? 'list-group-item-' . $themes->user->color : 'list-group-item-action' ?>"
                    >
                    <?php echo $themes->autorisation->icon;?>
                    Autorisations
                </a>
            <?php endif;?>        
            <!-- <a href="<?php echo base_url("user/avatar?$id_user_get");?>"
                class="list-group-item list-group-item-action <?php echo $context_sub == 'avatar' ? 'list-group-item-' . $themes->user->color : 'list-group-item-action' ?>"
                >
                <i class="<?php echo icon('avatar'); ?>"></i>
                <?php echo lang('User.avatar'); ?>
            </a>
            <?php if($user->id==session('loggedUserId')):?>
                <a href="<?php echo base_url("user/password?$id_user_get");?>"
                    class="list-group-item list-group-item-action <?php echo $context_sub == 'password' ? 'list-group-item-' . $themes->user->color : 'list-group-item-action' ?>"
                    >
                    <i class="<?php echo icon('password'); ?>"></i>
                    <?php echo lang('User.password'); ?>
                </a>
            <?php endif;?> -->
        </div>

        

        <!-- <hr class="dropdown-divider"> -->
        <!-- <div class="card-header bg-dark text-body-secondary"><?php // echo lang('Administrator.title'); ?></div> -->


        <?php //if(empty($id_user)):?>
            <!-- <a href="<?php //echo base_url('identification/logout') ?>"
                class="list-group-item list-group-item-action"
                >
                <i class="<?php //echo icon('power'); ?> text-danger"></i> <?php //echo lang('User.logout'); ?>
            </a> -->
        <?php //endif;?>
    </div>
    <div class="text-center mt-4">
        <img class="figure-img img-fluid img-thumbnail preview"
            src="<?php echo base_url(AVATAR_PATH . $user->avatar); ?>"
            alt="<?php echo $avatar_name; ?>"
        />
        <div>
            <?php if(!empty($user->is_actif)): ?>
                <div class="badge bg-success"> Actif </div>
            <?php else: ?>
                <div class="badge bg-warning"> Inactif </div>
            <?php endif; ?>
        </div>
        <!-- <div class="badge bg-dark"> <?php echo $user->role_label; ?> </div> -->
    </div>
</div>