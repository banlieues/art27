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
            <?php $i=0;?>
            <?php foreach($buildings as $building):?>
                <?php if($i==0):?>
                    <tr>
                        <th scope="row" class="align-top"> 
                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    name="id_building"
                                    checked
                                />
                                <label class="form-check-label">
                                    Pas de bien à relier
                                </label>
                            </div>
                        </th>
                        <td></td>
                        <td></td>                       
                        <td class="col-1"></td>                
                        <td class="col-1"></td>                
                    </tr>
                <?php elseif(!empty($building->id_building)):?>
                    <tr>
                        <th scope="row" class="align-top"> 
                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    group=<?php echo $i;?>
                                    name="id_building" 
                                    value="<?php echo $building->id_building;?>"
                                    onchange="select_dublon_building(this);" 
                                />
                                <label class="form-check-label">
                                    <a role="button" class="btn btn-sm btn-<?php echo $themes->bien->color;?>"
                                        target="_blank"
                                        href="<?php echo base_url("bien/fiche/$building->id_building");?>" >
                                        <small> N°<?php echo $building->id_building;?> </small>
                                    </a>
                                    <?php if(!empty($building->id_contact_profil) || !empty($building->id_contact)) :?>
                                        <br> <small> <?php echo fontawesome('user');?> <?php echo $building->contact_name . ' ' . $building->contact_lastname;?> </small>
                                    <?php endif;?>
                                </label>
                            </div>
                        </th>
                        <td> <?php echo $building->address_fr ?? '';?> </td>
                        <td> <?php echo $building->address_nl ?? '';?> </td>                       
                        <td class="col-1"> <?php echo $building->address_box ?? '';?> </td>                
                        <td class="col-1"> <?php echo $building->address_floor ?? '';?> </td>                
                    </tr>
                <?php endif;?>
                <?php $i++;?>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php echo $js_brugis;?>
