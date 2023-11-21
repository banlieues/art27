<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <div class="col-md-12">
        <form action="<?php echo base_url('documentstemplates/add'); ?>" method="post" enctype="multipart/form-data">
            <div><?php echo csrf_field(); ?></div>


        <div class="card-header p-1 p-xl-1 sticky_button bg-light">
            <div class="row"> 
                    <div class="col-sm-12 col-md-12 col-lg-auto align-self-center">
                        <h3 class="fs-4"><i class="<?php echo icon('file'); ?> mt-1 text-office"></i>  <?php echo $title.' : '.$subtitle; ?>
                            <small> <a class="modalView text-office" data-view-title="Liste de tags" href="<?=base_url()?>/documentsgenerator/getListTag">[Tags]</a></small>
                        </h3>
                       
                    </div> 
                    <div class="col align-self-center">
                        <div class="text-end" id="action_bar">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
                            </button>
                            <a class="btn btn-danger btn-sm" href="<?php echo base_url('documentstemplates'); ?>">
                                <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                            </a>
                        </div>
                    </div>   
            </div>  
        </div>

            <div class="card flex-fill border-top-office mb-4">
                <h5 class="card-header">
                    <?php echo lang('DocumentsTemplates.currenttemplate'); ?> : <?php echo lang('DocumentsTemplates.newtemplate'); ?>
                    <div class="float-end">
                        <i class="<?php echo icon('file'); ?> mt-1"></i>
                    </div>
                </h5>
                <div class="card-body-off">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="label" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.label'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="label" name="label" value="<?php echo set_value('label'); ?>" placeholder="<?php echo lang('DocumentsTemplates.label'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'label') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="description" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.description'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="description" name="description" value="<?php echo set_value('description'); ?>" placeholder="<?php echo lang('DocumentsTemplates.description'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'description') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="rank" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.content'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <textarea id="content" class="form-control" name="content"><?php echo set_value('email_body'); ?></textarea >
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'content') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="email_subject" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('mail-open'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.email_subject'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="email_subject" name="email_subject" value="<?php echo set_value('email_subject'); ?>" placeholder="<?php echo lang('DocumentsTemplates.email_subject'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email_subject') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="email_body" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('mail-text'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.email_body'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <textarea id="email_body" class="form-control" name="email_body"><?php echo set_value('email_body'); ?></textarea>
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email_body') : ''; ?></span>
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
                                                <option value="<?=$statut_asbl->id?>"><?=$statut_asbl->label?></option>
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
                                                <option <?php if($statut_template->id==0):?>selected<?php endif;?> value="<?=$statut_template->id?>"><?=$statut_template->label?></option>
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
        </form>
    </div>
</div>

<?=view("DocumentsTemplates\Views\/js")?>
<?php $this->endSection(); ?>
