<?php $this->extend("Layout\index"); ?>

<?php $this->section('navbarsub'); ?>
    <div class="container-fluid d-flex justify-content-between align-items-center py-2 border-bottom-<?php echo $themes->user->color;?>">
        <div class="h5 mb-0"> <?php echo $themes->user->icon;?> <?php echo $titleView;?> </div>
        <div class="d-flex">
            <button type="submit" form="UserNewForm" class="btn btn-sm btn-<?php echo $themes->user->color;?> ms-2">
                <?php echo fontawesome('save');?> Créer la fiche Utilisateur
            </button>
            <a role="button"
                class="btn btn-sm btn-<?php echo $themes->user->color;?> ms-2"
                href="<?php echo base_url('user/list'); ?>"
                title="Aller à la liste des utilisateurs"
                >
                <?php echo fontawesome('turn-up');?> <?php echo $themes->user->icon;?>
            </a>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section("body"); ?>
    <form id="UserNewForm"
        action="<?php echo base_url('user/add'); ?>"
        class="p-2"
        enctype="multipart/form-data"
        method="post"
        >
        <div class="row">
            <div class="col-3">
                <div class="sticky_button">
                    <label class="h4"> Données de l'utilisateur </label>
                    <div class="border rounded bg-light p-4">
                        <div><?php echo csrf_field(); ?></div>
                        <input type="hidden" name="role_id" value="1"/>
                        <!-- <div class="row mb-2">
                            <label for="role_id" class="form-label">
                            </label>
                            <div class="col-sm-9">
                            <input type="radio"
                                value="1"
                                name="role_id"
                                <?php if(set_value("role_id")==1):?>checked<?php endif;?>
                            />
                            Administrateur
                            <input type="radio"
                                name="role_id"
                                value="2"
                                <?php if(set_value("role_id")==2||set_value("role_id")==0):?>checked<?php endif;?>  
                            />
                            Utilisateur 
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'role_id') : '' ?></span>
                            </div>
                        </div> -->
                        <div class="row mb-2">
                            <input type="text"
                                autocomplete="off"
                                class="form-control"
                                name="username"
                                value="<?php echo set_value('username'); ?>"
                                placeholder="Login"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'username') : '' ?></span>
                        </div> 
                        <div class="row mb-2">
                            <input type="text"
                                    autocomplete="off"
                                class="form-control"
                                name="nom"
                                placeholder="Nom"
                                value="<?php echo set_value('nom'); ?>"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'nom') : '' ?></span>
                        </div> 
                        <div class="row mb-2">
                            <input type="text"
                                autocomplete="off"
                                class="form-control"
                                name="prenom"
                                placeholder="Prénom"
                                value="<?php echo set_value('prenom'); ?>"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'prenom') : '' ?></span>
                        </div> 
                        <div class="row mb-2">
                            <input type="email"
                                autocomplete="off"
                                class="form-control"
                                name="email"
                                placeholder="Email"
                                value="<?php echo set_value('email'); ?>"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email') : '' ?></span>
                        </div> 
                        <div class="row mb-2">
                            <input type="phone"
                                autocomplete="off"
                                class="form-control"
                                name="phone"
                                placeholder="Téléphone"
                                value="<?php echo set_value('phone'); ?>"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'website') : '' ?></span>
                        </div> 
                        <!-- <div class="row mb-2">
                            <input type="website"
                                autocomplete="off"
                                class="form-control"
                                name="website"
                                placeholder="Site web"
                                value="<?php echo set_value('website'); ?>"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'website') : '' ?></span>
                        </div>  -->
                        <div class="row mb-2">
                            <input type="password"
                                class="form-control"
                                name="password"
                                placeholder="Mot de passe"
                                value="<?php echo set_value('password'); ?>"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'password') : '' ?></span>
                        </div> 
                        <div class="row mb-2">
                            <input type="password"
                                class="form-control"
                                name="confirm"
                                placeholder="Confirmer le mot de passe"
                                value="<?php echo set_value('confirm'); ?>"
                            />
                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'confirm') : '' ?></span>
                        </div> 
                        <div class="mb-2">
                            <div class="form-check form-check-inline">
                                <input type="radio"
                                    class="form-check-input"
                                    value="1"
                                    name="is_actif"
                                    <?php if(set_value("is_actif")==1||!isset($validation)):?>checked<?php endif;?>
                                />
                                <label class="form-check-label"> Actif </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio"
                                    class="form-check-input"
                                    name="is_actif"
                                    value="0"
                                    <?php if(set_value("is_actif")==0&&isset($validation)):?>checked<?php endif;?>
                                />
                                <label class="form-check-label"> Non actif </label>
                            </div>
                            <!-- <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'is_actif') : '' ?></span> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <label class="h4"> Liste des autorisations </label>
                <?php echo view('Administrator\user-autorisation-table');?>
            </div>
        </div>
    </form>
<?php $this->endSection(); ?>
