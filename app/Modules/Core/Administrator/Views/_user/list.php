<?php $this->extend("Layout\index"); ?>

<?php $this->section('navbarsub'); ?>
    <div class="container-fluid d-flex justify-content-between align-items-center py-2 border-bottom-<?php echo $themes->user->color;?>">
        <div class="d-flex align-items-center">
            <div class="h5 mb-0"> <?php echo $themes->user->icon;?> <?php echo $titleView;?> </div>
            <div class="badge bg-dark ms-2 mt-1"> <?php echo count($users_infos);?> </div>
            <?php if(!empty($itemSearch)):?>
                <div class="ms-2" title="Filtre : <?php echo $itemSearch;?>">
                    <?php echo fontawesome('filter-warning');?>
                </div>
            <?php endif;?>
        </div>
        <div> <?php echo view('DataView\buttons/pagination');?> </div>
        <div> <?php echo view('DataView\buttons/search', ['color' => $themes->user->color,]);?> </div>
        <?php if(session('loggedUserRoleId')==1):?>
            <div class="d-flex">
                <a class="btn btn-<?php echo $themes->user->color;?> btn-sm ms-2" 
                    title="Ajouter un nouvel utilisateur" 
                    href="<?php echo base_url('user/add'); ?>">
                    <?php echo fontawesome('plus');?>
                </a>
            </div>
        <?php endif;?>
    </div>
<?php $this->endSection(); ?>

<?php $this->section("body"); ?>
    <?php if($users_total>0):?>
        <div id="users-list" class="banData table-responsive table-fullview"> 
            <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
                <thead class="table-light sticky-top" style="z-index:99;">
                    <tr>
                        <?php echo $getTh;?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users_infos as $index => $value): ?>
                        <tr>
                            <!-- <td> 
                                <button class="ban_deleteForm btn btn-sm btn-link link-danger"
                                    id_delete="<?php echo $value->id;?>"
                                    <?php if($value->id==session('loggedUserId')):?>
                                        href="<?php echo base_url("user/delete");?>"
                                    <?php else:?>
                                        href="<?php echo base_url("user/delete?id_user=" . $value->id);?>"
                                    <?php endif;?>
                                    text_alert="le compte utilisateur <strong><?php echo fullname($value->prenom, $value->nom);?></strong>"
                                    title="Désactiver le compte utilisateur"
                                    >
                                    <?php echo fontawesome('trash-alt');?>
                                </button>
                            </td> -->
                            <td><?php echo $value->id; ?></td>
                            <!-- <td><?php echo $value->role?></td> -->
                            <td><?php echo $value->nom; ?></td>
                            <td><?php echo $value->prenom; ?></td>
                            <td>
                                <a 
                                    <?php if(session('loggedUserId')==$value->id):?>
                                        class="btn btn-sm btn-secondary" 
                                    <?php else:?>
                                        class="btn btn-sm btn-<?php echo $themes->user->color;?>"
                                    <?php endif;?>
                                    title="Details de ce compte utilisateur"
                                    <?php if(session('loggedUserId')==$value->id):?>
                                        href="<?php echo base_url("user/profile");?>"
                                    <?php else:?>
                                        href="<?php echo base_url('user/profile?id_user=' . $value->id);?>"
                                    <?php endif;?>
                                    >
                                        <i class="fa fa-user"></i>
                                        <?php echo $value->username; ?>
                                </a>
                            </td>
                            <td><?php echo $value->email; ?></td>
                            <!-- <td class="text-nowrap"><?php // echo $value->created_at; ?></td> -->
                            <td class="text-nowrap"><?php echo convert_date_en_to_fr_with_h($value->updated_at,FALSE); ?></td>
                            <!-- <td class="text-nowrap"><?php // echo $value->created_by; ?></td> -->

                        

                            <th>
                                <form action="<?php echo base_url('memberslist/activate'); ?>" method="post" enctype="multipart/form-data">
                                    <div>
                                        <input type="hidden" id="id" name="id" value="<?php echo $value->id ?? null; ?>">
                                    </div>
                                    <div>
                                        <input type="hidden" id="is_actif" name="is_actif" value="<?php echo $value->is_actif ?? null; ?>">
                                    </div>
                                    <div>
                                        <!-- <input type="hidden" name="page" value="<?php //echo $page; ?>"> -->
                                    </div>
                                    <div><?php echo csrf_field(); ?></div>
                                    <?php if ($value->is_actif): ?>
                                        <span class="text-success">Actif</span>
                                    <?php else: ?>
                                        <span class="text-danger">Non actif</span>
                                    <?php endif; ?>
                                </form>
                            </th>

                            <!-- <td><?php // echo $value->role_id; ?></td> -->

                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else:?>
        <div class="text-center m-5"><h3>Je n'ai pas trouvé d'utilisateurs</h3></div>  
    <?php endif;?>
<?php $this->endSection(); ?>
