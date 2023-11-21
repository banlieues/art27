<nav class="
    navbar navbar-expand-md navbar-light bg-white bg-gradient-off fixed-top-off border-bottom"
    >
    <div class="container-fluid d-flex justify-content-between">
        <?php if(session('loggedUserRoleId')==1):?>
            <a href="#menutoggle" class="btn btn-light bg-white btn-sm border me-2" id="menutoggle">
                <!--times--> <!--TOGGLE-->
                <i class="<?php echo icon('menu'); ?>"></i> <span class="fw-bold"><?php echo mb_strtoupper(lang('Navbar.menu')); ?></span>
            </a>
        <?php endif;?>

        <a class="navbar-brand py-0 fw-bold mx-1 <?php if(session('loggedUserRoleId')==1):?>d-none<?php endif;?>" 
            href="<?php echo base_url() ?>"
            >
            <img src="<?php echo base_url($themes->main->logo);?>" alt="<?php echo $themes->main->name;?> Logo" height="40px"/>
        </a>
        <?php if(session('loggedUserRoleId')==1):?>
            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarRight"
                aria-controls="navbarRight" aria-expanded="false" aria-label="Toggle navigation"
                >
                <span class="<?php echo icon('menu'); ?>"></span>
            </button>
        <?php endif;?>
        <div class="collapse navbar-collapse" id="navbarRight">
            <ul class="navbar-nav me-auto mb-2 mb-md-0"></ul>

            
            <?php if(session('loggedUserRoleId')==1):?>
                <ul class="navbar-nav me-auto mb-2 mb-md-0">

                    <li class="nav-item dropdown mx-2" >
                        <a class="nav-link py-0 dropdown-toggle-off" href="<?=base_url()?>outlook/liste_message/1" id="dropdown01">
                        <i class="<?php echo icon('download'); ?>"></i> <!--Alerts-->
                            <span class="badge bg-danger text-white text-center fw-bold indicator_mail_non_traite"><i class="fa fa-circle-notch fa-spin"></i></span>
                            <small>Mails importés</small>
                        </a>
                    </li>

                    <li class="nav-item dropdown  mx-2" >
                        <a role="button"
                            class="nav-link py-0 dropdown-toggle-off"
                            id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="<?php echo icon('envelope-empty'); ?>"></i> <!--Alerts-->
                            <span class="badge bg-danger text-white text-center fw-bold indicator_mail_non_lu"><i class="fa fa-circle-notch fa-spin"></i></span>
                            <small>Mails non lus</small>
                        </a>
                    
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="dropdown02">
                            <div style="max-height: 450px; overflow-y: auto;" class="list-group">
                                
                            </div>
                        </div>
                    </li>

                    <li class="nav-item mx-2" >
                        <a class="nav-link py-0" href="<?=base_url()?>/outlook/">
                        <i class="<?php echo icon('user-no-fill'); ?>"></i> <i class="<?php echo icon('envelope-empty'); ?>"></i> <!--Alerts-->
                            <small>Tous mes mails</small>
                        </a>
                    
                        
                    </li>


                    <li class="nav-item dropdown  mx-2" >
                         <a role="button"
                            class="nav-link py-0 dropdown-toggle-off"
                            id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="<?php echo icon('sticky-note-empty'); ?>"></i> <!--Alerts-->
                            <span class="badge bg-danger text-white text-center fw-bold indicator_messagerie_non_lu"><i class="fa fa-circle-notch fa-spin"></i></span>
                            <small>Notes non lues</small>
                        </a>
                    
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="dropdown02">
                            <div id="liste_message_non_lu" style="min-height: 100px; max-height: 450px; overflow-y: auto;" class="list-group">
                                
                            </div>
                        </div>
                    </li>

                    <li class="nav-item mx-2" >
                        <a class="nav-link py-0" href="<?=base_url()?>/messagerie/">
                        <i class="<?php echo icon('user-no-fill'); ?>"></i> <i class="<?php echo icon('sticky-note-empty'); ?>"></i> <!--Alerts-->
                            <small>Toutes mes notes</small>
                        </a>
                    
                        
                    </li>

            
                </ul>
            <?php endif;?>
        <div class="d-flex align-items-center" id="navbarRight">
            <?php if (session()->has('loggedUserId')): ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a role="button"
                            class="nav-link py-0 dropdown-toggle active"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            >
                            <img src="<?php echo base_url(AVATAR_PATH . session('loggedUserAvatar'));?>" alt="Avatar" class="img-tiny rounded-circle avatar" /> 
                            <?php echo session('loggedUserName');?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="p-2 text-center">
                                <img src="<?php echo base_url(AVATAR_PATH . session('loggedUserAvatar'));?>"
                                    alt="Avatar" class="img-fuild img-thumbnail rounded-circle avatar" 
                                    />
                                <br>
                                <div class="badge bg-light text-body-secondary border mt-2">
                                    Hello <?php echo session('loggedUserName'); ?>
                                </div>
                            </li>

                            <li><hr class="dropdown-divider"></li>


                            <li>
                                <a class="dropdown-item" href="<?php echo base_url('user') ?>">
                                    <i class="<?php echo icon('user'); ?>"></i> Mon compte
                                </a>
                            </li>
                            <!-- <li class="text-center">
                                <?php //echo view('Translator\language-select');?>
                            </li> -->

                            <?php if (session()->has('loggedUserRoleId') && session('loggedUserRoleId') <= BACKEND_ACCESS_LEVEL) : ?>
                                <li> 
                                    <a class="dropdown-item
                                        <?php echo (!empty($context) && in_array($context, ['administrator', 'memberslist', 'profileslist', 'avatarslist', 'flagslist', 'imageslist', 'rolelist', 'genderlist', 'countrylist', 'paths_settings', 'cropper', 'avatar_settings', 'administrator_settings'])) ? 'active' : null; ?>" href="<?php echo base_url('administrator') ?>
                                        "
                                        >
                                        <i class="<?php echo icon('administrator'); ?>"></i>
                                        Listes des utilisateurs
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li> 
                                <a class="dropdown-item" href="<?php echo base_url('identification/logout') ?>">
                                    <i class="<?php echo icon('power'); ?> text-danger"></i> <?php echo lang('Navbar.logout'); ?> 
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <a href="<?php echo base_url('identification/logout') ?>" class="btn btn-danger btn-sm">
                    <i class="<?php echo icon('power'); ?>"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
