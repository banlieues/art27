<?php $this->extend("Layout\index"); ?>

<?php $this->section("body"); ?>

<form 
    <?php if($user->id==session('loggedUserId')):?>
        action="<?php echo base_url("user/profile/save");?>"
    <?php else:?>
        action="<?php echo base_url('user/profile/save?id_user=' . $user->id);?>"
    <?php endif;?>
    method="post" 
    enctype="multipart/form-data"
    >
    <div><input type="hidden" id="id" name="id" value="<?php echo $user->id ?? null; ?>"></div>

    <div class="float-end">
        <button type="submit" class="btn btn-success btn-sm">
            <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
        </button>
        <a class="btn btn-danger btn-sm" href="<?php echo base_url('user/profile/index/' . $user->id);?>">
            <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
        </a>
    </div>

    <h4> 
        <i class="<?php echo icon('profile'); ?>"></i>
        <?php if($user->id!=session('loggedUserId')):?>
            Editer le profil de
            <?php echo $user->prenom;?> <?php echo $user->nom;?>
        <?php else:?>
            Editer mon profil
        <?php endif;?>
    </h4>

    <div class="card flex-fill mb-4">
        
        <input type="hidden" name="avatar_path" value="<?php echo AVATAR_PATH;?>">
        <input type="hidden" name="avatar_name" value="<?php echo $user->avatar;?>">
        <input type="hidden" name="username" value="<?php echo $user->username;?>">

        <div class="card-body-off">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
                    <div class="text-center m-2">
                        <img src="<?php echo base_url(AVATAR_PATH . $user->avatar); ?>" alt="Avatar" 
                            class="img-fluid img-thumbnail"
                        />
                    </div>
                </div>

                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <label for="gsm" class="col-sm-3 col-form-label">
                                </label>
                                <div class="col-sm-9">
                                    <input <?php if($user->role_id==1):?>checked<?php endif;?> value="1" type="radio" name="role_id"/> Administrateur
                                    <input  <?php if($user->role_id==2):?>checked<?php endif;?> value="2" type="radio" name="role_id"/> Utilisateur 
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <label for="nom" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> Nom
                                </label>
                                <div class="col-sm-9">
                                    <input autocomplete="off" type="text" class="form-control" id="nom" name="nom" value="<?php echo $user->nom; ?>" placeholder="Nom..."/>
                                </div>
                            </div> 
                        </li>
                        
                        <li class="list-group-item">
                            <div class="row">
                                <label for="prenom" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> Prénom
                                </label>
                                <div class="col-sm-9">
                                    <input autocomplete="off"  type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $user->prenom; ?>" placeholder="Prénom...">
                                </div>
                            </div> 
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <label for="email" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('email'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.email'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user->email; ?>" placeholder="">
                                    <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email') : '' ?></span>
                                </div>
                            </div> 
                        </li>


                        <li class="list-group-item">
                            <div class="row">
                                <label for="website" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('website'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.website'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="website" name="website" value="<?php echo $user->website; ?>" placeholder="">
                                    <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'website') : '' ?></span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row">
                                <label for="phone" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('phone'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.phone'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="phone" class="form-control" id="phone" name="phone" value="<?php echo $user->phone;?>" placeholder="">
                                    <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'phone') : '' ?></span>
                                </div>
                            </div>
                        </li>
                        <!-- <li class="list-group-item">
                            <div class="row">
                                <label for="gsm" class="col-sm-3 col-form-label">
                                    <i class="<?php //echo icon('gsm'); ?>" style="width:20px;"></i> <?php //echo lang('UserProfile.gsm'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="gsm" class="form-control" id="gsm" name="gsm" value="<?php //echo $profile_infos['gsm']; ?>" placeholder="">
                                    <span class="text-danger"><?php //echo isset($validation) ? display_error($validation, 'gsm') : '' ?></span>
                                </div>
                            </div>
                        </li> -->
                        
                        <li class="list-group-item">
                            <div class="row">
                                <label for="gsm" class="col-sm-3 col-form-label">
                                    
                                </label>
                                <div class="col-sm-9">
                                    <input <?php if($user->is_actif==1):?>checked<?php endif;?> value="1" type="radio" name="is_actif"> Actif <input  <?php if($user->is_actif==0):?>checked<?php endif;?> value="0" type="radio" name="is_actif"> Non actif
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- <script>
document.addEventListener("DOMContentLoaded", function() {
    $('#gender_id').on('input', function(event) {
        event.preventDefault();
        var form = $(this);
        var gender_id = $("#gender_id").val();
        var country_id = $("#country_id").val();

        $.ajax({
            url: "<?php //echo base_url('user/profile/edit'); ?>",
            // type: 'POST',
            method: 'POST',
            dataType: 'json',
            data: {'gender_id': gender_id, 'country_id': country_id},
            // data: form.serialize(), 
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            success: function(data) {
                $('#gender_label').val(data.gender_label);
                $('#gender_description').val(data.gender_description);
            },
            error: function (data) {
                console.log(data);
            },
        });
    });
});
</script> -->

<!-- <script>
document.addEventListener("DOMContentLoaded", function() {
    $('#country_id').on('input', function(event) {
        event.preventDefault();
        var form = $(this);
        var gender_id = $("#gender_id").val();
        var country_id = $("#country_id").val();

        $.ajax({
            url: "<?php //echo base_url('user/profile/edit'); ?>",
            // type: 'POST',
            method: 'POST',
            dataType: 'json',
            data: {'gender_id': gender_id, 'country_id': country_id},
            // data: form.serialize(), 
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            success: function(data) {
                $('#country_label').val(data.country_label);
                $('#country_description').val(data.country_description);
                $('#capitale').val(data.country_capitale);
            },
            error: function (data) {
                console.log(data);
            },
        });
    });
});
</script> -->

<?php $this->endSection(); ?>
