<?php $this->extend("Layout\index"); ?>

<?php $this->section("body"); ?>

<div class="row banData">

    <div class="col-md-12">

        <h4> <i class="fa fa-user-tie mt-1"></i> Liste des utilisateurs</h4>
       
        <div class="card flex-fill border-top-theme mb-4">

            <div class="card-header sticky_button">
                    <form class="form_with_order">
                        <div class="row">

                            <div class="col-lg-auto p-1 align-self-center">
                                <!-- <h5 class='card-title mb-0'><?php //echo $users_total?> utilisateur<?php //echo plurial_s($pager->getTotal())?></h5> -->
                            </div>
                            
                            <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                <div class="input-group input-group-navbar text-lg-end">
                                    <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                    <button class="btn btn-primary text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>

                            <?php if(session('loggedUserRoleId')==1):?>
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-primary btn-sm float-end" title="Add" href="<?php echo base_url('user/add'); ?>">
                                        <i class="<?php echo icon('add'); ?>"></i> Créer un nouvel utilisateur
                                    </a>
                                </div>
                            <?php endif;?>

                        </div>
                        <input type="hidden" name="orderby" type="text" value="">
                    </form>
                </div> 




            <div class="card-body-off">
                <?php if($users_total>0):?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="dataTable">
                        <thead class="">
                            <tr>
                                <?=$getTh?>
                            </tr>
                         
                        </thead>
                        <tbody>
                            <?php foreach($users_infos as $index => $value): ?>
                            <tr>
                                <td> <a class="btn btn-danger btn-sm" title="Delete" href="<?php echo base_url('memberslist/delete?id='.$value['id'].''); ?>">
                                            <i class="<?php echo icon('delete'); ?>"></i>
                                        </a></td>
                                <td><?php echo $value['id']; ?></td>
                                <th><?=$value["role"]?></th>
                                <td><?php echo $value['nom']; ?></td>
                                <td><?php echo $value['prenom']; ?></td>
                                <td>
                                <a class="btn btn-primary btn-sm" title="Details" href="<?php echo base_url('user/contacts/list?id_user=' . $value['id']);?>">
                                    <i class="fa fa-user"></i> <?php echo $value['username']; ?>
                            </a>
                                </td>
                                <td><?php echo $value['email']; ?></td>
                                <!-- <td class="text-nowrap"><?php // echo $value['created_at']; ?></td> -->
                                <td class="text-nowrap"><?php echo convert_date_en_to_fr_with_h($value['updated_at'],FALSE); ?></td>
                                <!-- <td class="text-nowrap"><?php // echo $value['created_by']; ?></td> -->

                           

                                <th>
                                    <form action="<?php echo base_url('memberslist/activate'); ?>" method="post" enctype="multipart/form-data">
                                        <div><input type="hidden" id="id" name="id" value="<?php echo $value['id'] ?? null; ?>"></div>
                                        <div><input type="hidden" id="is_actif" name="is_actif" value="<?php echo $value['is_actif'] ?? null; ?>"></div>
                                        <div><input type="hidden" name="page" value="<?php echo $page; ?>"></div>
                                        <div><?php echo csrf_field(); ?></div>
                                        <?php if ($value['is_actif']): ?>
                                           <span class="text-success">Actif</span>
                                        <?php else: ?>
                                            <span class="text-danger">Non actif</span>
                                        <?php endif; ?>
                                    </form>
                                </th>

                                <!-- <td><?php // echo $value['role_id']; ?></td> -->

                             
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot style="display:none" class="table-dark">
                            <tr>
                                <th><?php echo lang('Member.id'); ?></th>
                                <th class="text-nowrap"><?php echo lang('Member.username'); ?></th>
                                <th><?php echo lang('Member.email'); ?></th>
                                <!-- <th class="text-nowrap"><?php // echo lang('Member.created_at'); ?></th> -->
                                <th class="text-nowrap"><?php echo lang('Member.updated_at'); ?></th>
                                <!-- <th class="text-nowrap"><?php // echo lang('Member.created_by'); ?></th> -->
                                <th class="text-nowrap"><?php echo lang('Member.updated_by'); ?></th>
                                <th><?php echo lang('Member.is_actif'); ?></th>
                                <!-- <th class="text-nowrap"><?php // echo lang('Member.role_id'); ?></th> -->
                                <th><div class="float-end"><?php echo lang('Member.actions'); ?></div></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé d'utilisateurs</h3></div>  
                <?php endif;?>
                <?php //if ($pager->getPageCount() > 1): ?>
                    <?php //echo $pager->links('default', 'bs_full'); ?>
                <?php //endif;?>
            </div>
            <div class="card-footer text-muted"></div>
        </div>

    </div>
</div>

<?php $this->endSection(); ?>
