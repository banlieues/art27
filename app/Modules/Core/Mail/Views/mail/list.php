<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> <?php echo $titleView;?> </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Mail\js/js_example');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------- -->
<!-- ------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="container">
    <table class="table my-4">
        <thead>
            <tr>
                <th> Statut </th>
                <th> Sujet </th>
                <th> Expéditeur </th>
                <th> Destinataires </th>
                <th> Action </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($emails as $email):?>
                <tr>
                    <td> 
                        <?php if(!isset($email->isSended) || $email->isSended==0):?> Brouillon
                        <?php elseif($email->isSended==1):?> Envoyé
                        <?php endif;?>
                    </td>
                    <td> <?php if(isset($email->subject)) echo $email->subject;?> </td>
                    <td> 
                        <?php if(isset($email->sender)):?> 
                            <div title="<?php echo $email->sender->email;?>"> 
                                <?php echo $email->sender->name;?> 
                                <?php echo $email->sender->lastname;?> 
                            </div> 
                        <?php endif;?>
                    </td>
                    <td> 
                        <?php if(!empty($email->to_selected)): foreach($email->to_selected as $recip):?>
                            <div title="<?php echo $recip->email;?>"> 
                                <?php echo $recip->name;?> 
                                <?php echo $recip->lastname;?> 
                            </div>
                        <?php endforeach; endif;?> 
                        <?php if(!empty($email->cc_selected)): foreach($email->cc_selected as $recip):?>
                            <div title="<?php echo $recip->email;?>"> 
                                <?php echo $recip->name;?> 
                                <?php echo $recip->lastname;?> 
                            </div>
                        <?php endforeach; endif;?> 
                        <?php if(!empty($email->cci_selected)): foreach($email->cci_selected as $recip):?>
                            <div title="<?php echo $recip->email;?>"> 
                                <?php echo $recip->name;?> 
                                <?php echo $recip->lastname;?> 
                            </div>
                        <?php endforeach; endif;?> 
                    </td>
                    <td> 
                        <?php if(!isset($email->isSended) || $email->isSended==0):?>
                            <button type="button" class="btn" onclick="module_mail_modal_edit(<?php echo $email->id_email;?>);">
                                <?php echo $themes->edit->icon;?>
                            </button>
                            <button type="button" class="btn" onclick="module_mail_modal_delete(<?php echo $email->id_email;?>);">
                                <?php echo $themes->delete->icon;?>
                            </button>
                        <?php elseif($email->isSended==1):?>
                            <button type="button" class="btn" onclick="module_mail_modal_info(<?php echo $email->id_email;?>);">
                                <?php echo $themes->info->icon;?>
                            </button>
                            <button type="button" class="btn" onclick="module_mail_modal_archive(<?php echo $email->id_email;?>);">
                                <?php echo fontawesome('archive');?>
                            </button>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php $this->endSection(); ?>
