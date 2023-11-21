<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<!--
<style>
.sticky_action_bar {
    position: fixed;
    top: 10px;
    right: 24px;
    z-index: 1;
}
</style>
-->

<div class="row">
    <div class="col-md-12">
        <form action="<?php echo base_url('documentstemplates/save'); ?>" method="post" enctype="multipart/form-data">
            <div><input type="hidden" id="id" name="id" value="<?php echo $template_id ?? null; ?>"></div>
            <div><input type="hidden" name="page" value="<?php echo $page; ?>"></div>
            <div><?php echo csrf_field(); ?></div>

            <div class="card-header p-1 p-xl-1 sticky_button bg-light">
                <div class="row">   
                            <div class="col-sm-12 col-md-12 col-lg-auto align-self-center">
                                <h3 class="fs-4"><i class="<?php echo icon('file'); ?> mt-1 text-office"></i>  <?php echo $title.' : '.$subtitle; ?>
                                    <small> <a class="modalView text-office" data-view-title="Liste de tags" href="<?=base_url()?>/documentsgenerator/getListTag">[Tags]</a></small>
                                </h3>
                            </div>  
                            <div class="col align-self-center">

                                <div class="float-end" id="action_bar">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
                                    </button>
                                    <a class="btn btn-danger btn-sm" href="<?php echo base_url('documentstemplates').'?page='.$page; ?>">
                                        <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                                    </a>
                                </div>
                            </div>
                </div>            
            </div>

            <?php if ($template_id): ?>
            <div class="card flex-fill border-top-office mb-4">
                <h5 class="card-header">
                    <?php echo lang('DocumentsTemplates.currenttemplate'); ?> : <?php echo $documents_template['label']; ?>
                    <div class="float-end">
                        <i class="<?php echo icon('file'); ?> mt-1"></i>
                    </div>
                </h5>
                <div class="card-body-off">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="template_id" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('id'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.id'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control" id="template_id" name="template_id" value="<?php echo $documents_template['id']; ?>" placeholder="<?php echo lang('DocumentsTemplates.id'); ?> ...">
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="label" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.label'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="label" name="label" value="<?php echo $documents_template['label']; ?>" placeholder="<?php echo lang('DocumentsTemplates.label'); ?> ...">
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="description" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.description'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $documents_template['description']; ?>" placeholder="<?php echo lang('DocumentsTemplates.description'); ?> ...">
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="rank" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.content'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <textarea id="content" class="form-control" name="content"><?php echo $documents_template['content']; ?></textarea>
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'content') : '' ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="email_subject" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('mail-open'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.email_subject'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="email_subject" name="email_subject" value="<?php echo $documents_template['email_subject']; ?>" placeholder="<?php echo lang('DocumentsTemplates.email_subject'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email_subject') : '' ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="email_body" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('mail-text'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.email_body'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <textarea id="email_body" class="form-control" name="email_body"><?php echo $documents_template['email_body']; ?></textarea>
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email_body') : '' ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="actived" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('actived'); ?>" style="width:20px;"></i> Fitre type ASBL
                                        </label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="id_type_asbl">
                                            <?php foreach($liste_type_asbl as $statut_asbl):?>
                                                <option <?php if($documents_template['id_type_asbl']==$statut_asbl->id):?>selected<?php endif;?> value="<?=$statut_asbl->id?>"><?=$statut_asbl->label?></option>
                                             <?php endforeach;?>  
                                             </select> 
                                        </div>
                                    </div> 
                                </li>
                               
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="actived" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('actived'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.actived'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="actived">
                                            <?php foreach($list_statut_template as $statut_template):?>
                                                <option <?php if($documents_template['actived']==$statut_template->id):?>selected<?php endif;?> value="<?=$statut_template->id?>"><?=$statut_template->label?></option>
                                             <?php endforeach;?>  
                                             </select> 
                                        </div>
                                    </div> 
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-body-secondary"></div>
            </div>
            <?php else: ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo lang('Errors.invalidrequest'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?=view("DocumentsTemplates\Views\/js")?>

<?php $this->endSection(); ?>
