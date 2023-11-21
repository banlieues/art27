<div class="border rounded mb-4">
    <table class="table table-sm table-bordered mb-0" id="depositBuildingTable">
        <thead class="thead-light">
            <tr>
                <th scope="col" class="col-2 text-center text-<?php echo $themes->bien->color;?>"> 
                    <?php echo $themes->bien->icon;?>
                    <b> BIEN </b> 
                </th>
                <th scope="col" class="col-auto text-center"> Adresse FR </th>
                <th scope="col" class="col-auto text-center"> Adresse NL </th>
                <th scope="col" class="col-auto text-center"> Boîte </th>
                <th scope="col" class="col-auto text-center"> Etage </th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;?>
            <?php foreach($buildings as $building):?>
                <?php if($i==0 || !empty($building->id_building)):?>
                    <tr>
                        <th scope="row" class="align-top"> 
                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    group=<?php echo $i;?>
                                    name="id_building" 
                                    <?php if($i>0):?> value="<?php echo $building->id_building;?>" <?php endif;?>
                                    onchange="select_dublon_building(this);" 
                                    <?php if($i==0):?> checked <?php endif;?>
                                />
                                <label class="form-check-label">
                                    <?php if($i==0):?> 
                                        Nouveau
                                    <?php else:?>
                                        <a role="button" class="btn btn-sm btn-<?php echo $themes->bien->color;?>"
                                            target="_blank"
                                            href="<?php echo base_url("bien/fiche/$building->id_building");?>" >
                                            <small> N°<?php echo $building->id_building;?> </small>
                                        </a>
                                        <?php if(!empty($building->id_contact_profil) || !empty($building->id_contact)) :?>
                                            <br> <small> <?php echo fontawesome('user');?> <?php echo $building->contact_name . ' ' . $building->contact_lastname;?> </small>
                                        <?php endif;?>
                                    <?php endif;?>
                                </label>
                            </div>
                        </th>
                        <td>
                            <?php if($i == 0):?>
                                <div class="building_new_brugis" style="display: none;">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control brugis-search bg-warning" autocomplete="false"
                                            name="address_fr"
                                            group=0
                                            value="<?php echo $building->address_fr ?? '';?>"
                                        />
                                        <button type="button" class="btn btn-dark btn-sm" title="Recherche Brugis FR" onclick="$(this).parent().find('input').click();">
                                            <?php echo fontawesome('magnifying-glass');?>
                                            FR
                                        </div>
                                    </div>
                                    <?php if(!empty($building->address_fr)):?>
                                        <div class="small"> Adresse encodée : <?php echo $building->address_fr;?> </div>
                                    <?php endif;?>
                                </div>
                                <div class="building_existing">
                                    <?php echo $building->address_fr ?? '';?>
                                </div>
                            <?php else:?>
                                <?php echo $building->address_fr ?? '';?>
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if($i == 0):?>
                                <div class="building_new_brugis" style="display: none;">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control brugis-search bg-warning" autocomplete="false"
                                            name="address_nl"
                                            group=0
                                            value="<?php echo $building->address_nl ?? '';?>"
                                        />
                                        <button type="button" class="btn btn-dark btn-sm" title="Recherche Brugis NL" onclick="$(this).parent().find('input').click();">
                                            <?php echo fontawesome('magnifying-glass');?>
                                            NL
                                        </button>
                                    </div>
                                    <?php if(!empty($building->address_nl)):?>
                                        <div class="small"> Adresse encodée : <?php echo $building->address_nl;?> </div>
                                    <?php endif;?>
                                </div>
                                <div class="building_existing">
                                    <?php echo $building->address_nl ?? '';?>
                                </div>
                            <?php else:?>
                                <?php echo $building->address_nl ?? '';?>
                            <?php endif;?>
                        </td> 
                        <td>
                            <?php if($i==0):?>
                                <div class="building_new_brugis" style="display: none;">
                                    <input type="text" class="form-control form-control-sm" name="address_box"/>
                                </div>
                                <div class="building_existing">
                                    <?php echo $building->address_box ?? '';?>
                                </div>
                            <?php else:?>
                                <?php echo $building->address_box ?? '';?>
                            <?php endif;?>
                        </td>
                        <td>
                            <?php if($i==0):?>
                                <div class="building_new_brugis" style="display: none;">
                                    <input type="text" class="form-control form-control-sm" name="address_floor"/>
                                </div>
                                <div class="building_existing">
                                    <?php echo $building->address_floor ?? '';?>
                                </div>
                            <?php else:?>
                                <?php echo $building->address_floor ?? '';?>
                            <?php endif;?>
                        </td>     
                    </tr>
                <?php endif;?>
                <?php $i++;?>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php echo $js_brugis;?>
