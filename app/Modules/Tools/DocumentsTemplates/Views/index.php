<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row banData">
    <div class="col-12">
        <div class="card border-top-office mb-4">
            <div class="card-header sticky_button bg-light">
                <div class="row">
                    <div class="col-lg-auto p-1 align-self-center">
                        <h5 class="card-tile mb-0">
                            <i class="<?php echo icon('file'); ?> mt-1 text-office"></i> <?php echo $documents_template_total; ?> modèle<?=plurial_s($pager->getTotal())?>
                        </h5>
                    </div>
                    <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                        <form class="form_with_order">
                            <div class="input-group input-group-navbar text-lg-end">
                                <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                <button class="btn btn-office text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </form>    
                    </div>
                    <div class="col-lg-auto p-1 align-self-center text-lg-end">
                        <a class="btn btn-office btn-sm float-end" href="<?php echo base_url('documentstemplates/add'); ?>">
                            <i class="<?php echo icon('add'); ?>"></i> <?php echo lang('Buttons.adddocument'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body-off">
                <div class="table-responsive">
                    <table class="table table-striped table-hover my-0 table-sm" id="dataTable">
                    <thead>
                            <tr>
                                <?=$getTh?>
                            </tr>
                        </thead>
      
                        <tbody>
                            
                            <?php foreach($documents_template as $index => $value): ?>
                            <tr>
                                <td><?php echo $value['id']; ?></td>
                                <td>
                                    <a class="btn btn-office btn-sm text-nowrap text-white" href="<?php echo base_url('documentstemplates/details?id='.$value['id'].'&page='.$page); ?>">
                                        <i class="<?php echo icon("file"); ?>"></i> <?php echo $value['label']; ?>
                                    </a>
                                </td>
                                <td><?php echo $value['type_asbl']; ?></td>
                                <td><?php echo $value['description']; ?></td>
                                <!-- <td><?php // echo $value['content']; ?></td> -->
                                <td><?php echo $value['email_subject']; ?></td>
                                <!-- <td><?php // echo $value['email_body']; ?></td> -->
                                <!-- <td><?php // echo $value['created_at']; ?></td> -->
                                <td><?php echo convert_date_en_to_fr_with_h($value['updated_at']); ?></td>
                                <!-- <td><?php // echo $value['created_by']; ?></td> -->
                                <td>
                                    <img src="<?=base_url()?>/images/avatars/<?=$value["avatar_updated"]?>" alt="" class="img-tiny rounded-circle avatar"> <?php echo $value['name_updated']; ?>
                                </td>
                                <td>
                                    <form action="<?php echo base_url('documentstemplates/activate'); ?>" method="post" enctype="multipart/form-data">
                                        <div><input type="hidden" id="id" name="id" value="<?php echo $value['id'] ?? null; ?>"></div>
                                        <div><input type="hidden" id="actived" name="actived" value="<?php echo $value['actived'] ?? null; ?>"></div>
                                        <div><input type="hidden" name="page" value="<?php echo $page; ?>"></div>
                                        <div><?php echo csrf_field(); ?></div>
                                        <?php if ($value['actived']): ?>
                                        <button type="submit" class="btn btn-success border-0 badge"><?php echo lang('DocumentsTemplates.active'); ?></button>
                                        <?php else: ?>
                                        <button type="submit" class="btn btn-danger border-0 badge"><?php echo lang('DocumentsTemplates.inactive'); ?></button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td class="text-nowrap">
                                    <div class="float-end">
                                        <!-- <button onclick="window.print()">Print</button> -->
                                        <!-- BUTTON TRIGGER MODAL -->
                                        <!-- MODAL -->
                                        <div class="modal fade" id="sendmail_<?php echo $value['id'] ?? null; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sendmail_label" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered-off modal-lg-off">
                                                <div class="modal-content">
                                                    <form action="<?php echo base_url('documentstemplates/sendmail'); ?>" method="post" enctype="multipart/form-data">
                                                        <div><input type="hidden" id="id" name="id" value="<?php echo $value['id'] ?? null; ?>"></div>
                                                        <div><input type="hidden" id="page" name="page" value="<?php echo $page ?? null; ?>"></div>
                                                        <div><?php echo csrf_field(); ?></div>
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title" id="sendmail_label">
                                                                <i class="<?php echo icon('file'); ?> mt-1"></i> <?php echo $value['label']; ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label for="email" class="col-form-label"><?php echo lang('DocumentsTemplates.sendto'); ?> :</label>
                                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email adress ..." required>
                                                            <!--
                                                            <label for="email_subject" class="col-form-label"><?php // echo lang('DocumentsTemplates.email_subject'); ?></label >
                                                            <input type="text" readonly="" class="form-control" id="email_subject" name="email_subject" value="<?php // echo $value['email_subject']; ?>">
                                                            <label for="email_body" class="col-form-label"><?php // echo lang('DocumentsTemplates.email_body'); ?></label >
                                                            <textarea readonly="" id="email_body" class="form-control" name="email_body" rows="3"><?php // echo $value['email_body']; ?></textarea>
                                                            -->
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="<?php echo icon('send-email'); ?>"></i> <?php echo lang('Buttons.send'); ?>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                                                                <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="btn-group btn-sm" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-office btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog"></i> Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li>
                                                    <a class="dropdown-item" title="Pdf" href="<?php echo base_url('documentstemplates/dompdf?id='.$value['id']); ?>&attachment=true">
                                                        <i class="<?php echo icon('file-pdf'); ?>"></i> Télécharger le pdf
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" title="View " target="blank" href="<?php echo base_url('documentstemplates/dompdf?id='.$value['id']); ?>&attachment=false">
                                                        <i class="<?php echo icon('view'); ?>"></i> Prévisualiser le pdf
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url("documentstemplates/testmail/".$value['id']); ?>" class="dropdown-item modalView" data-view-title="Envoyer par mail">
                                                        <i class="<?php echo icon('send-email'); ?>" ></i> Envoyer par mail
                                                    </a>

                                                </li>    
                                                <li>
                                                    <a class="dropdown-item" title="Duplicate" href="<?php echo base_url('documentstemplates/duplicate?id='.$value['id'].'&page='.$page); ?>">
                                                        <i class="<?php echo icon('duplicate'); ?>"></i> Dupliquer
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" title="Edit" href="<?php echo base_url('documentstemplates/edit?id='.$value['id'].'&page='.$page); ?>">
                                                        <i class="<?php echo icon('edit'); ?>"></i> Modifier
                                                    </a>
                                                </li>
                                                <?php if (!in_array($value["id"],[1, 2, 25])): ?>
                                                <li>
                                                    <a class="dropdown-item" title="Delete" href="<?php echo base_url('documentstemplates/delete?id='.$value['id'].'&page='.$page); ?>">
                                                        <i class="<?php echo icon('delete'); ?>"></i> Effacer
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($pager->getPageCount() > 1): ?>
                    <?php echo $pager->links('default', 'bs_office'); ?>
                <?php endif;?>
            </div>
            <div class="card-footer text-body-secondary"></div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

