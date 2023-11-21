<div class="col">
    <div class="card h-100">
        <div class="card-body" id_contact="<?php echo $contact->id_contact;?>">
            <div class="d-flex align-items-center justify-content-between">

                <?php if(!empty($selectable)):?>
                    <input type="checkbox" name="id_contacts[]" 
                        value="<?php echo $contact->id_contact?>"
                    />
                <?php else:?>
                    <div> </div>
                <?php endif;?>

                <div>
                    <?php if($contact->nb_profile>0):?>
                        <button type="button" onclick="contactView(this, <?php echo $contact->id_contact;?>);"
                            class="btn btn-sm btn-outline-success border-0 ms-1 p-0"
                            title="Voir le(s) profil(s) du contact"
                            >
                            <?php echo $themes->watch->icon;?>
                        </button>
                    <?php else:?>
                        <button type="button" class="btn btn-sm border-0 ms-1 p-0 invisible">
                            <?php echo $themes->watch->icon;?>
                        </button>
                    <?php endif;?>

                    <?php if(empty($selectable)):?>
                        <?php if(session('loggedUserRoleId')==1):?>
                            <a href="<?php echo base_url('user/updateContact/' . $contact->id_contact . '/' . $user->id);?>" 
                                class="btn btn-sm btn-outline-primary border-0 ms-1 p-0 btn_update_contact_user"
                                title="Modifier le contact"
                                >
                                <?php echo $themes->edit->icon;?>
                            </a>
                        <?php endif;?>

                        <button type="button" class="btn btn-sm btn-outline-danger border-0 ms-1 p-0 confirmDelete"
                            title="Supprimer la gestion du contact
                                <?php echo fullname($contact->prenom_contact, strtoupper($contact->nom_contact));?>
                                <?php if($user->id!=session('loggedUserId')):?>
                                    de l'utilisateur <?php echo $user->prenom;?> <?php echo $user->nom;?>
                                <?php endif;?>
                            "
                            data-view-title="Supprimer la gestion d'un contact"
                            data-view-message="la gestion du contact
                                <?php echo fullname($contact->prenom_contact, strtoupper($contact->nom_contact));?>
                                <?php if($user->id!=session('loggedUserId')):?>
                                    de l'utilisateur <?php echo $user->prenom;?> <?php echo $user->nom;?>
                                <?php endif;?>
                            "
                            href="<?php echo base_url('user/contact/unlink/' . $contact->id_contact . '/' . $user->id);?>"
                            >
                            <?php echo fontawesome('unlink');?>
                        </button>
                    <?php endif;?>
                </div>
            </div>

            <div class="h5">
                <?php echo fullname($contact->prenom_contact, strtoupper($contact->nom_contact));?>
            </div>

            <div class="contactProfiles"></div>
        </div>
    </div>
</div>