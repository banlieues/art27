<?php $this->extend("Layout\index"); ?>

<?php $this->section("script_foot_injected"); ?>
    <?php echo view('Administrator\user/js_contact');?>
<?php $this->endSection(); ?>

<?php $this->section("body"); ?>

<h4>
    <?php if($user->id!=session('loggedUserId')):?>
        Associer des contacts à
        <?php echo $user->prenom ;?> <?php echo $user->nom;?>
    <?php else:?>
        Associer des contacts à mon compte
    <?php endif;?>
</h4>

<div class="card-header sticky_button border-bottom-0">
    <div class="row align-items-center">
        <div class="col text-begin"> 
            <form>
                <div class="row">
                    <div class="col-lg-auto p-1"> 
                        <div class="input-group input-group-sm input-group-navbar text-lg-end">
                            <input name="itemSearch" type="text" class="form-control"
                                placeholder="Rechercher un contact" 
                                aria-label="Rechercher" 
                                <?php if($itemSearch!==FALSE):?> value="<?php echo $itemSearch;?>" <?php endif;?>
                            />
                            <button class="btn btn-primary text-white btn-sm" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col text-end">
            <a id="submit_contacts_link" class="btn btn-sm btn-primary">
                <?php echo fontawesome('link');?>
                Associer les contacts au compte
            </a>
            <a class="btn btn-sm btn-danger"
                href="<?php echo base_url("user/contacts/list?id_user=$user->id");?>"
                >
                Annuler
            </a>
        </div>
    </div>    
</div> 

<div class="card flex-fill mb-4">
    <div class="card-body">
        <?php if(!empty($futurContacts)):?>
            <form id="form_ajout_contacts" method="post" action="<?php echo base_url("user/contacts/link/set")?>">
                <input type="hidden" name="id_user" value="<?php echo $user->id;?>"/>
                <input type="hidden" name="itemSearch" value="<?php echo $itemSearch;?>"/>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach($futurContacts as $contact):?>

                        <?php echo view('Administrator\user/contact_view', [
                            'contact' => $contact,
                            'selectable' => true,
                        ]);?>

                    <?php endforeach?>
                </div>
            </form>    

            <?php if ($pager->getPageCount() > 1): ?>
                <?php echo $pager->links('default', 'bs_office'); ?>
            <?php endif;?>
        
        <?php else:?>
            <p>Pas de contacts trouvés</p>  
        <?php endif;?>
    </div> 
</div>

<?php $this->endSection(); ?>
