<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <div class="col-md-12">
        <a class="btn btn-primary btn-sm float-end" title="Add" href="<?php echo base_url('depositbox/add'); ?>">
            <i class="<?php echo icon('add'); ?>"></i> <?php echo lang('Buttons.addfile'); ?>
        </a>

        <h4><?php echo $title.' : '.$subtitle; ?></h4>
        <div class="clearfix"></div>

        <div class="card flex-fill border-top-lightseagreen mb-4">
            <h5 class="card-header">
                <i class="<?php echo icon('file'); ?> float-end mt-1"></i>
                <?php echo lang('DepositBox.totalfiles'); ?> :  <?php echo $deposit_box_total; ?> 
                (<?php echo $total_filesize.' of '.$depositbox_quota.' allowed'; ?>) <?php echo $percentage; ?>
            </h5>

            <div class="card-body-off">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th><?php echo lang('DepositBox.id'); ?></th>
                                <th><?php echo lang('DepositBox.label'); ?></th>
                                <!-- <th><?php // echo lang('DepositBox.description'); ?></th> -->
                                <!-- <th><?php // echo lang('DepositBox.comment'); ?></th> -->
                                <!-- <th><?php // echo lang('DepositBox.keywords'); ?></th> -->

                                <th class="text-nowrap"><?php echo lang('DepositBox.filename'); ?></th>
                                <th class="text-nowrap"><?php echo lang('DepositBox.filesize'); ?></th>

                                <!-- <th class="text-nowrap"><?php // echo lang('DepositBox.created_at'); ?></th> -->
                                <th class="text-nowrap"><?php echo lang('DepositBox.updated_at'); ?></th>
                                <!-- <th class="text-nowrap"><?php // echo lang('DepositBox.created_by'); ?></th> -->
                                <th class="text-nowrap"><?php echo lang('DepositBox.updated_by'); ?></th>
                                <th><?php echo lang('DepositBox.actived'); ?></th>
                                <th><div class="float-end"><?php echo lang('DepositBox.actions'); ?></div></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($deposit_box as $index => $value): ?>
                            <tr>
                                <td><span class="badge rounded-pill bg-secondary"><?php echo $value['id']; ?></span></td>
                                <td><?php echo $value['label']; ?></td>
                                <!-- <td><?php // echo $value['description']; ?></td> -->
                                <!-- <td><?php // echo $value['comment']; ?></td> -->
                                <!-- <td><?php // echo $value['keywords']; ?></td> -->
                                
                                <td><?php echo $value['filename']; ?></td>
                                <td><?php echo $value['filesize']; ?></td>

                                <!-- <td class="text-nowrap"><?php // echo $value['created_at']; ?></td> -->
                                <td class="text-nowrap"><?php echo $value['updated_at']; ?></td>
                                <!-- <td><?php // echo $value['created_by']; ?></td> -->
                                <td><?php echo $value['updated_by']; ?></td>
                                <td>
                                    <form action="<?php echo base_url('depositbox/activate'); ?>" method="post" enctype="multipart/form-data">
                                        <div><input type="hidden" id="id" name="id" value="<?php echo $value['id'] ?? null; ?>"></div>
                                        <div><input type="hidden" id="actived" name="actived" value="<?php echo $value['actived'] ?? null; ?>"></div>
                                        <div><input type="hidden" name="page" value="<?php echo $page; ?>"></div>
                                        <div><?php echo csrf_field(); ?></div>
                                        <?php if ($value['actived']): ?>
                                        <button type="submit" class="btn btn-success border-0 badge"><?php echo lang('DepositBox.active'); ?></button>
                                        <?php else: ?>
                                        <button type="submit" class="btn btn-danger border-0 badge"><?php echo lang('DepositBox.inactive'); ?></button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td class="text-nowrap">
                                    <div class="float-end">
                                        <a class="btn btn-primary btn-sm" title="Details" href="<?php echo base_url('depositbox/details?id='.$value['id'].'&page='.$page); ?>">
                                            <i class="<?php echo icon('info'); ?>"></i>
                                        </a>
                                        <!-- force_download -->
                                        <a class="btn btn-primary btn-sm" title="Download" href="<?php echo base_url($depositbox_path.$value['filename']); ?>" download>
                                            <i class="<?php echo icon('download'); ?>"></i>
                                        </a>
                                        <a class="btn btn-primary btn-sm" title="Edit" href="<?php echo base_url('depositbox/edit?id='.$value['id'].'&page='.$page); ?>">
                                            <i class="<?php echo icon('edit'); ?>"></i>
                                        </a>
                                        <?php // echo base_url('depositbox/delete?id='.$value['id'].''); ?>
                                        <a class="btn btn-danger btn-sm" title="Delete" href="<?php echo base_url('depositbox/delete?id='.$value['id'].'&page='.$page); ?>" role="button" aria-disabled="true">
                                            <i class="<?php echo icon('delete'); ?>"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th><?php echo lang('DepositBox.id'); ?></th>
                                <th><?php echo lang('DepositBox.label'); ?></th>
                                <!-- <th><?php // echo lang('DepositBox.description'); ?></th> -->
                                <!-- <th><?php // echo lang('DepositBox.comment'); ?></th> -->
                                <!-- <th><?php // echo lang('DepositBox.keywords'); ?></th> -->

                                <th><?php echo lang('DepositBox.filename'); ?></th>
                                <th><?php echo lang('DepositBox.filesize'); ?></th>

                                <!-- <th class="text-nowrap"><?php // echo lang('DepositBox.created_at'); ?></th> -->
                                <th class="text-nowrap"><?php echo lang('DepositBox.updated_at'); ?></th>
                                <!-- <th class="text-nowrap"><?php // echo lang('DepositBox.created_by'); ?></th> -->
                                <th class="text-nowrap"><?php echo lang('DepositBox.updated_by'); ?></th>
                                <th><?php echo lang('DepositBox.actived'); ?></th>
                                <th><div class="float-end"><?php echo lang('DepositBox.actions'); ?></div></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php if ($pager->getPageCount() > 1): ?>
                    <?php echo $pager->links('default', 'bs_full'); ?>
                <?php endif;?>
            </div>
            <div class="card-footer text-body-secondary"></div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
