<?php $this->extend("Layout\index"); ?>

<?php $this->section("script_foot_injected"); ?>
    <?php echo view('Administrator\user/js_contact');?>
<?php $this->endSection(); ?>

<?php $this->section("body"); ?>

<div class="card flex-fill mb-4">
    <div class="card-header">
        <div class="row justify-content-between">
            <div class="col-sm-auto">
                <h5>
                    <?php if(!empty($id_user)):?>
                        Contacts gérés par
                        <?php echo $user->prenom;?> <?php echo $user->nom;?>
                        <small>(<?php echo $user->username;?>)</small>
                    <?php else:?>
                        Mes contacts
                    <?php endif;?>
                </h5>    
            </div>    
            <div class="col-sm-auto">
                <?php if ($user->role_id==1):?>
                    <a class="btn btn-sm btn-primary" href="<?php echo base_url("user/contacts/link/$user->id")?>">
                        <?php echo fontawesome('link');?>
                        Associer des contacts
                    </a>
                <?php endif;?> 
            </div>
        </div>
    </div>

    <div class="card-body">
        
        <?php if(!empty($contacts)):?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach($contacts as $contact):?>
                    
                    <?php echo view("Administrator\user/contact_view", ['contact' => $contact]);?>  

                <?php endforeach?>
            </div>
        <?php else:?>
            <p>Pas de contacts trouvés</p>  
        <?php endif;?>
    </div>
</div>

<?php $this->endSection(); ?>
