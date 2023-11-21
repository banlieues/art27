<div class="card mb-4">
    <div style="display:none" class="card-header"><?php echo lang('Administrator.submenu'); ?></div>
    <div class="list-group list-group-flush">
        <a style="display:none" 
            href="<?php echo base_url('administrator') ?>" 
            class="list-group-item list-group-item-action <?php echo $context == 'administrator' ? 'active' : null; ?>"
            >
            <i class="<?php echo icon('dashboard'); ?>"></i> 
            <?php //echo lang('Administrator.dashboard'); ?>
            </i> <?php if(!empty($user->id)) echo "Mon tableau de bord"; else echo "Celui de l'autre"; ?>
        </a>

        <div class="card-header">
            <?php echo lang('Administrator.management'); ?>
        </div>

        <a href="<?php echo base_url('memberslist') ?>" class="list-group-item list-group-item-action <?php echo $context == 'memberslist' ? 'active' : null; ?>">
            <i class="<?php echo icon('users'); ?>"></i> <?php echo lang('Administrator.memberslist'); ?>
        </a>

        <a href="<?php echo base_url('profileslist') ?>" class="list-group-item list-group-item-action <?php echo $context == 'profileslist' ? 'active' : null; ?>">
            <i class="<?php echo icon('profile'); ?>"></i> <?php echo lang('Administrator.profileslist'); ?>
        </a>

        <a href="<?php echo base_url('genderslist') ?>" class="list-group-item list-group-item-action <?php echo $context == 'genderslist' ? 'active' : null; ?>">
            <i class="<?php echo icon('genders'); ?>"></i> <?php echo lang('Administrator.genderslist'); ?>
        </a>

        <a href="<?php echo base_url('list/role') ?>" class="list-group-item list-group-item-action <?php echo $context == 'rolelist' ? 'active' : null; ?>">
            <i class="<?php echo icon('role'); ?>"></i> <?php echo lang('Administrator.roleslist'); ?>
        </a>
        <a href="<?php echo base_url('countrieslist') ?>" class="list-group-item list-group-item-action <?php echo $context == 'countrieslist' ? 'active' : null; ?>">
            <i class="<?php echo icon('country'); ?>"></i> <?php echo lang('Administrator.countrieslist'); ?>
        </a>

        <div class="card-header"><?php echo lang('Administrator.settings'); ?></div>

        <a href="<?php echo base_url('cropper/settings') ?>" class="list-group-item list-group-item-action <?php echo ($context == 'cropper' || $context == 'cropper_settings') ? 'active' : null; ?>">
            <i class="<?php echo icon('cropper'); ?>"></i> <?php echo lang('Administrator.cropper_settings'); ?>
        </a>

        <a href="<?php echo base_url('administrator/settings') ?>" class="list-group-item list-group-item-action <?php echo $context == 'administrator_settings' ? 'active' : null; ?>">
            <i class="<?php echo icon('settings'); ?>"></i> <?php echo lang('Administrator.administrator_settings'); ?>
        </a>

        <a href="<?php echo base_url('identification/logout') ?>" class="list-group-item list-group-item-action">
            <i class="<?php echo icon('power'); ?> text-danger"></i> <?php echo lang('Administrator.logout'); ?>
        </a>
    </div>
    <div class="card-footer"></div>
</div>
