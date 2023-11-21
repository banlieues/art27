<?php $request = \Config\Services::request(); ?>

<?php if (!$request->isAJAX()):?>
    <?php $this->extend("Layout\index"); ?>
    <?php $this->section("body"); ?>
<?php endif;?>

<div class="card">
    <div class="card-header">
            <small style="font-size:12px"  class="text-body-secondary">
                        <span class="message_date">
                            Message du :
                            <?php if(!empty($message->received_datetime)):?>
                                <?=convert_date_en_to_fr_with_h($message->received_datetime);?>
                            <?php else:?>
                                 <?=convert_date_en_to_fr_with_h($message->send_datetime);?>
                            <?php endif;?>
                        </span>  
                            <small>(#<?=$message->id_primary?>)
                        </small>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        De: <b><span class="message_a"><?=$message->sender_mail?></span></b>
                    </div>
                    <div class="col-md-12">
                        A: <b><?=$message->to_mail?></b>
                    </div>
                </div>
            
                Objet:<b><span class="message_sujet"><?=$message->subject?></span></b>
                
            </small>     
            <?php if($id_demande>0):?>
                
                <a href="<?=base_url()?>demande/fiche/<?=$id_demande?>/<?=$message->id_primary?>" class="btn btn-amethyst btn-sm float-end"> <i class="fa fa-link"></i> Demande n°<?=$id_demande?></a>
                
            <?php else:?>

                <a href="<?=base_url();?>demande/new/outlook/<?=$message->id_primary;?>" class='btn btn-sm btn-secondary float-end'> <i class="fa fa-link"></i>Ajouter à une demande</a>

            <?php endif;?>

    </div>

  <?php echo view("Outlook\message_view_document",["documents"=>$documents])?>

    <div class="card-body">
        <div class="message_body"><?php echo nettoye_body_mail_image($message->body_content)?></div>
    </div>
</div>

<?php if (!$request->isAJAX()):?>
    <?php $this->endSection(); ?>
<?php endif;?>