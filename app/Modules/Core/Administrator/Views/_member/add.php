<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
 
    <div class="col-md-12">
        <form action="<?php echo base_url('member/add'); ?>" method="post" enctype="multipart/form-data">
            <div><?php echo csrf_field(); ?></div>

            <div class="float-end">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
                </button>
                <a class="btn btn-danger btn-sm" href="<?php echo base_url('member'); ?>">
                    <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                </a>
            </div>

            <h4><i class="fa fa-user-tie mt-1"></i> Créer un nouvel utilisateur</h4>

            <div class="card flex-fill border-top-theme mb-4">
             

                <div class="card-body-off">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12">
                            <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                    <div class="row">
                                        <label for="role_id" class="col-sm-3 col-form-label">
                                        </label>
                                        <div class="col-sm-9">
                                        <input <?php if(set_value("role_id")==1):?>checked<?php endif;?> value="1" type="radio" name="role_id"> Administrateur <input  <?php if(set_value("role_id")==2||set_value("role_id")==0):?>checked<?php endif;?> value="2" type="radio" name="role_id"> Utilisateur 
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'role_id') : '' ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="username" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> Login
                                        </label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off"  type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>" placeholder="<?php echo lang('Member.username'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'username') : '' ?></span>
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="nom" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> Nom
                                        </label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off"  type="text" class="form-control" id="nom" name="nom" value="<?php echo set_value('nom'); ?>" placeholder="Nom...">
                                        </div>
                                    </div> 
                                </li>
                             
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="prenom" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> Prénom
                                        </label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off"  type="text" class="form-control" id="prenom" name="prenom" value="<?php echo set_value('prenom'); ?>" placeholder="Prénom...">
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="email" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('email'); ?>" style="width:20px;"></i> Email 
                                        </label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off" type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="<?php echo lang('Member.email'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email') : '' ?></span>
                                        </div>
                                    </div> 
                                </li>

                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="website" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('website'); ?>" style="width:20px;"></i> Site Web
                                        </label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off" type="website" class="form-control" id="website" name="website" value="<?php echo set_value('website'); ?>" placeholder="Site web ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'website') : '' ?></span>

                                        </div>
                                    </div> 
                                </li>

                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="phone" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('phone'); ?>" style="width:20px;"></i> Téléphone
                                        </label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off" type="phone" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="Téléphone ...">
                                        </div>
                                    </div> 
                                </li>

                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="phone" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('mobile'); ?>" style="width:20px;"></i> GSM
                                        </label>
                                        <div class="col-sm-9">
                                            <input autocomplete="off" type="GSM" class="form-control" id="GSM" name="gsm" value="<?php echo set_value('GSM'); ?>" placeholder="GSM ...">
                                        </div>
                                    </div> 
                                </li>

                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="password" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('password'); ?>" style="width:20px;"></i> Mot de passe
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="<?php echo lang('Member.password'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'password') : '' ?></span>
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="confirm" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('password'); ?>" style="width:20px;"></i> Confirmer mot de passe
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="confirm" name="confirm" value="<?php echo set_value('confirm'); ?>" placeholder="<?php echo lang('Member.confirm_password'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'confirm') : '' ?></span>
                                        </div>
                                    </div> 
                                </li>

                              
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="is_actif" class="col-sm-3 col-form-label">
                                        </label>
                                        <div class="col-sm-9">
                                        <input <?php if(set_value("is_actif")==1||!isset($validation)):?>checked<?php endif;?> value="1" type="radio" name="is_actif"> Actif <input  <?php if(set_value("is_actif")==0&&isset($validation)):?>checked<?php endif;?> value="0" type="radio" name="is_actif"> Non actif 
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'is_actif') : '' ?></span>
                                        </div>
                                    </div>
                                </li>
                               
                            </ul>
                        </div>
                    </div>
                </div>
          
            </div>
        </form>
    </div>
</div>

<?php $this->endSection(); ?>
