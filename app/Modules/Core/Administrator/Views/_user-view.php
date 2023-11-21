<?php $this->extend("Layout\index"); ?>

<?php $this->section('navbarsub'); ?>
    <div class="container-fluid d-flex justify-content-between align-items-center py-2 border-bottom-<?php echo $themes->user->color;?>">
        <div class="h5 mb-0">
            <i class="<?php echo icon('profile'); ?>"></i>
            <?php echo $titleView;?>
            <img src="<?php echo base_url(AVATAR_PATH . $user->avatar);?>"
                alt="Avatar de l'utilisateur" 
                class="img-tiny rounded-circle avatar"
            />
        </div>
        <div class="d-flex">
            <button class="form_read btn btn-<?php echo $themes->user->color;?> btn-sm ms-2"
                onclick="js_form_update(this);"
                >
                <?php echo fontawesome('edit');?>
            </button>
            <button type="submit"
                class="form_update btn btn-<?php echo $themes->user->color;?> btn-sm ms-2" 
                form="UserUpdateForm"
                style="display: none;"
                title="Enregiqtrer les modifications"
                >
                <?php echo fontawesome('save');?>
            </button>
            <a role="button"
                class="form_update btn btn-sm btn-secondary ms-2"
                href="<?php echo current_url(); ?>"
                style="display: none;"
                title="Annuler les modifications"
                >
                <?php echo fontawesome('undo');?>
            </a>
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

<form id="UserUpdateForm"
    action="<?php echo base_url('user/profile?' . $id_user_get);?>"
    method="post" 
    enctype="multipart/form-data"
    class="my-2"
    >
    <input type="hidden" id="id" name="id" value="<?php echo $user->id ?? null; ?>">
    <!-- <input type="hidden" name="avatar_path" value="<?php echo AVATAR_PATH;?>">
    <input type="hidden" name="avatar_name" value="<?php echo $user->avatar;?>">
    <input type="hidden" name="username" value="<?php echo $user->username;?>"> -->

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="border rounded bg-light p-4">
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Avatar
                    </label>
                    <div class="col-sm-8">
                        <figure class="m-0">
                            <img class="figure-img img-fluid img-thumbnail preview"
                                src="<?php echo base_url(AVATAR_PATH . $user->avatar); ?>"
                                alt="<?php echo $avatar_name; ?>"
                            />
                        </figure>
                        <!-- <div class="form_read form-control-plaintext">
                            <?php echo $avatar_name; ?>
                        </div> -->
                        <div class="form_update" style="display: none;">
                            <input type="file"
                                class="form-control"
                                name="avatar"
                                >
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'avatar') : '' ?></span>
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Nom
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php echo $user->nom ?? '';?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <input type="text"
                                autocomplete="off"
                                class="form-control"
                                name="nom"
                                value="<?php echo $user->nom ?? '';?>"
                            />
                        </div>
                    </div>
                    <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'nom') : '' ?></span>
                </div> 
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Prénom
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php echo $user->prenom ?? '';?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <input type="text"
                                autocomplete="off"
                                class="form-control"
                                name="prenom"
                                value="<?php echo $user->prenom ?? '';?>"
                            />
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'prenom') : '' ?></span>
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Email
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php echo $user->email ?? '';?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <input type="email"
                                autocomplete="off"
                                class="form-control"
                                name="email"
                                value="<?php echo $user->email ?? '';?>"
                            />
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email') : '' ?></span>
                    </div>
                </div>
                <!-- <div class="row mb-2">
                    <label class="col-sm-4 col-form-label">
                        Siteweb
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php echo $user->website ?? '';?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <input type="text"
                                autocomplete="off"
                                class="form-control"
                                name="website"
                                value="<?php echo $user->website ?? '';?>"
                            />
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'website') : '' ?></span>
                    </div>
                </div> -->
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Téléphone
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php echo $user->phone ?? '';?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <input type="phone"
                                class="form-control"
                                name="phone"
                                value="<?php echo $user->phone ?? '';?>"
                            />
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'phone') : '' ?></span>
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Compte
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read">
                            <?php if(!empty($user->is_actif)): ?>
                                <span class="badge bg-success"> Actif </span>
                            <?php else: ?>
                                <span class="badge bg-warning"> Inactif </span>
                            <?php endif; ?>
                        </div>
                        <?php if(session('loggedUserRoleId')==1):?>
                            <div class="form_update" style="display: none;">
                                <div class="form-check form-check-inline">
                                    <input type="radio"
                                        class="form-check-input"
                                        name="is_actif"
                                        value="1"
                                        <?php if($user->is_actif==1):?> checked <?php endif;?>
                                    />
                                    <label class="form-check-label"> Actif </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio"
                                        class="form-check-input"
                                        name="is_actif"
                                        value="0"
                                        <?php if($user->is_actif==0):?> checked <?php endif;?>
                                    />
                                    <label class="form-check-label"> Non actif </label>
                                </div>
                                <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'is_actif') : '' ?></span>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Type
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php if(!empty($is_salle)):?>
                                Salle
                            <?php else :?>
                                Personne                        
                            <?php endif;?>
                        </div>
                        <div class="form_update form-check mt-2" style="display: none;">
                            <input type="checkbox"
                                class="input-nullable form-check-input" 
                                name="is_salle"
                                value="1"
                                <?php if(isset($is_salle) && $is_salle==1):?> checked <?php endif;?>
                            />
                            <label class="form-check-label"> Salle </label>
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'is_salle') : '' ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Utilisateur en backup
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php foreach($users_backup as $user_backup):
                                if(!empty($user->id_user_back_up) && $user->id_user_back_up==$user_backup->id):
                                    echo fullname($user_backup->prenom, $user_backup->nom);
                                    break;
                                endif;
                            endforeach;?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <select class="form-select"
                                name="id_user_back_up"
                                >
                                <option disabled selected> - Sélectionner - </option>
                                <?php foreach($users_backup as $user_backup):?>
                                    <option value="<?php echo $user_backup->id;?>"
                                        <?php if(!empty($user->id_user_back_up) && $user->id_user_back_up==$user_backup->id):?> selected <?php endif;?>
                                        >
                                        <?php echo fullname($user_backup->prenom, $user_backup->nom);?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'id_user_back_up') : '' ?></span>
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Mail automatique
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php if(!empty($user->is_mail_automatique)):?>
                                Activé
                            <?php endif;?>
                        </div>
                        <div class="form_update form-check mt-2" style="display: none;">
                            <input type="checkbox"
                                class="input-nullable form-check-input" 
                                name="is_mail_automatique"
                                value="1"
                                <?php if(!empty($user->is_mail_automatique)):?> checked <?php endif;?>
                            />
                            <label class="form-check-label"> Activer </label>
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'is_mail_automatique') : '' ?></span>
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Date début
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php if(!empty($user->date_debut_automatique)):?>
                                <?php echo convert_date_en_to_fr_with_h($user->date_debut_automatique, false);?>
                            <?php endif;?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <input type="text"
                                class="form-control datepicker" 
                                name="date_debut_automatique"
                                <?php if(!empty($user->date_debut_automatique)):?>
                                    value="<?php echo $user->date_debut_automatique;?>"
                                <?php endif;?>
                            />
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'date_debut_automatique') : '' ?></span>
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Date fin
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php if(!empty($user->date_fin_automatique)):?>
                                <?php echo convert_date_en_to_fr_with_h($user->date_fin_automatique, false);?>
                            <?php endif;?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <input type="text"
                                class="form-control datepicker" 
                                name="date_fin_automatique"
                                <?php if(!empty($user->date_fin_automatique)):?>
                                    value="<?php echo convert_date_en_to_fr_with_h($user->date_fin_automatique, false);?>"
                                <?php endif;?>
                            />
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'date_fin_automatique') : '' ?></span>
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-sm-4 col-form-label">
                        Message automatique
                    </label>
                    <div class="col-sm-8">
                        <div class="form_read form-control-plaintext">
                            <?php echo $user->message_automatique ?? '';?>
                        </div>
                        <div class="form_update" style="display: none;">
                            <textarea class="form-control" rows="3"
                                name="message_automatique"
                            ><?php echo $user->message_automatique ?? '';?></textarea>
                        </div>
                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'message_automatique') : '' ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <?php echo view('Administrator\user/autorisation-table');?>
        </div>
    </div>
</form>

<?php $this->endSection(); ?>
