<table class="table table-sm table-bordered mb-0" id="depositPersonTable">
    <thead class="thead-light">
        <tr>
            <th scope="col" class="col-2 text-center text-<?php echo $themes->contact->color;?>">
                <?php echo $themes->contact->icon;?>
                <b> CONTACT </b> 
            </th>
            <th scope="col" class="text-center"> Prénom </th>
            <th scope="col" class="text-center"> Nom </th>
            <th scope="col" class="text-center"> Email </th>
            <th scope="col" class="text-center"> Ajouter un email </th>
            <th scope="col" class="text-center"> Téléphone </th>
            <th scope="col" class="text-center"> Ajouter un téléphone </th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0;?>
        <?php foreach($profils as $profil):?>
            <?php if($i==0 || (!empty($profil->id_contact_profil) || !empty($profil->id_contact))):?>
                <tr>
                    <th scope="row" class="align-top"> 
                        <div class="form-check">
                            <?php if($i==0 || !empty($profil->id_contact_profil)):?>
                                <input type="radio" class="form-check-input"
                                    group=<?php echo $i;?>
                                    name="id_contact_profil" 
                                    value="<?php echo $i>0 ? $profil->id_contact_profil : 0;?>"
                                    onchange="select_dublon_profil(this, 'id_contact_profil');" 
                                    <?php if($i==0):?> checked <?php endif;?>
                                />
                            <?php elseif(!empty($profil->id_contact)):?>
                                <input type="radio" class="form-check-input"
                                    group=<?php echo $i;?>
                                    name="id_contact" 
                                    value="<?php echo $i>0 ? $profil->id_contact : 0;?>"
                                    onchange="select_dublon_profil(this, 'id_contact');" 
                                    <?php if($i==0):?> checked <?php endif;?>
                                />
                            <?php endif;?>
                            <label class="form-check-label">
                                <?php if($i==0):?> 
                                    Nouveau
                                <?php else:?>
                                    <a role="button" class="btn btn-sm btn-<?php echo $themes->contact->color;?>"
                                        target="_blank"
                                        href="<?php echo base_url("contact/fiche/$profil->id_contact");?>" >
                                        <small> N°<?php echo $profil->id_contact;?> </small>
                                    </a>
                                    <?php if(!empty($profil->id_building)) :?>
                                        <br> <small> <?php echo fontawesome('map-marker-alt');?> <?php echo $profil->address_fr;?> </small>
                                    <?php endif;?>
                                <?php endif;?>
                            </label>
                        </div>
                    </th>
                    <?php foreach(['contact_name', 'contact_lastname', 'contact_email', 'contact_email2', 'contact_phone', 'contact_phone2'] as $ref):?>
                        <td>
                            <?php switch($ref):
                                case 'contact_email2':?>
                                <?php case 'contact_phone2':?>
                                    <?php $ref_1 = explode('2', $ref)[0];?>
                                    <?php if($i>0 && (empty($profil->$ref_1) || $profil->$ref_1!=$profils[0]->$ref_1)):?>
                                        <input type="radio" class="form-check-input d-none" 
                                            group=<?php echo $i;?>
                                            name="<?php echo $ref_1;?>"
                                            <?php if(!empty($profil->id_contact_profil)):?> 
                                                id_contact_profil="<?php echo $profil->id_contact_profil;?>" 
                                            <?php endif;?>
                                            value="<?php echo $profils[0]->$ref_1;?>"
                                            <?php if($i==0):?> checked <?php endif;?>
                                            disabled
                                        />
                                        <label class="form-check-label"> 
                                            <?php echo $profils[0]->$ref_1;?>
                                        </label>
                                    <?php endif;?>
                                <?php break;?>
                                <?php case 'contact_email':?>
                                <?php case 'contact_phone':?>
                                    <input type="radio" class="form-check-input d-none" 
                                        group=<?php echo $i;?>
                                        <?php if($i==0):?>
                                            name="<?php echo $ref;?>"
                                        <?php else:?>
                                            name="<?php echo $ref;?>2"
                                        <?php endif;?>
                                        <?php if(!empty($profil->id_contact_profil)):?> 
                                            id_contact_profil="<?php echo $profil->id_contact_profil;?>" 
                                        <?php endif;?>
                                        value="<?php echo $profil->$ref;?>"
                                        <?php if($i>0):?> disabled <?php endif;?>
                                        <?php if($i==0):?> checked <?php endif;?>
                                    />
                                    <label class="form-check-label"> 
                                        <?php echo $profil->$ref;?>
                                    </label>
                                <?php break;?>
                                <?php default:?>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input d-none" 
                                            group=<?php echo $i;?>
                                            name="<?php echo $ref;?>"
                                            <?php if(!empty($profil->id_contact_profil)):?> 
                                                id_contact_profil="<?php echo $profil->id_contact_profil;?>" 
                                            <?php endif;?>
                                            value="<?php echo $profil->$ref;?>"
                                            <?php if($i>0):?> disabled <?php endif;?>
                                            <?php if($i==0):?> checked <?php endif;?>
                                        />
                                        <label class="form-check-label"> 
                                            <?php echo $profil->$ref;?>
                                        </label>
                                    </div>
                                <?php break;?>
                            <?php endswitch;?>
                        </td>
                    <?php endforeach;?>
                </tr>
            <?php endif;?>
            <?php $i++;?>
        <?php endforeach;?>
    </tbody>
</table>
