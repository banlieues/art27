<table class="table table-sm table-bordered mb-0" id="depositDemandeTable">
    <thead class="thead-light">
    <th scope="col" class="col-2 text-center text-<?php echo $themes->demande->color;?>">
        <?php echo $themes->demande->icon;?> 
        <b> DEMANDE </b> 
    </th>
    <th scope="col"> Détails</th>
    </thead>
    <tbody>
        <?php $i = 0;?>
            <?php foreach($demandes as $demande):?>
                <?php if($i==0 || !empty($demande->id_demande)):?>
                    <tr>
                        <th class="px-2"> 
                            <div class="form-check">
                                <input type="radio" class="form-check-input" 
                                    name="id_demande"
                                    group=<?php echo $i;?>
                                    <?php if($i>0):?> value="<?php echo $demande->id_demande;?>" <?php endif;?>
                                    <?php if($i==0):?> checked <?php endif;?>
                                    onclick=select_dublon_demande(this);
                                />
                                <label class="form-check-label text-left"> 
                                    <?php if($i==0):?>
                                        Nouveau
                                    <?php else:?>
                                        <a role="button" class="btn btn-sm btn-<?php echo $themes->demande->color;?>"
                                            target="_blank"
                                            href="<?php echo base_url("demande/fiche/$demande->id_demande");?>" >
                                            <small> <?php echo $demande->id_demande;?> </small>
                                            <span class="badge badge-light"> 
                                                <?php echo $demande->id_demande_statut->label;?> 
                                            </span>
                                        </a>
                                        <?php if(!empty($demande->id_contact_profil)) :?>
                                            <br> <small> <?php echo fontawesome('user');?> <?php echo $demande->contact_name . ' ' . $demande->contact_lastname;?> </small>
                                        <?php endif;?>
                                        <?php if(!empty($demande->id_building)) :?>
                                            <br> <?php echo fontawesome('map-marker-alt');?> <small> <?php echo $demande->address_fr;?> </small>
                                        <?php endif;?>
                                    <?php endif;?>
                                </label>
                            </div>
                                                        
                        </th>
                        <td class="px-2">
                            <div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b> Sujet </b> : <?php echo $demande->subject;?>
                                        <button type="button" 
                                            class="btn btn-sm" 
                                            data-bs-toggle="collapse" data-bs-target="#depositComment<?php echo $i;?>Collapse"
                                            >
                                            <?php echo fontawesome('ellipsis-h');?>
                                        </button>
                                    </div>
                                    <div>
                                        <small> Daté du <?php echo date('d/m/y à H:i', strtotime($demande->gf_date_created));?> </small>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="depositComment<?php echo $i;?>Collapse"
                                onclick="$('#depositComment<?php echo $i;?>Collapse', '#depositDemandeTable').collapse('hide');"
                                >
                                <hr>
                                <b> Contenu </b> : <br>
                                <small> 
                                    <?php 
                                        if(!is_html($demande->comment)) echo nl2br($demande->comment);
                                        else echo preg_replace("/<p[^>]*>(?:\s|&nbsp;)*<\/p>/", '', $demande->comment);
                                    ;?> 
                                </small>
                            </div>
                        </td>
                    </tr>
                <?php endif;?>
                <?php $i++;?>
            <?php endforeach;?>
        </div>            
    </tbody>
</table>