<?php $this->extend('Custom\modal');?>

<?php $this->section('modal_size');?> modal-xl <?php $this->endSection();?> <!-- Size -->
<?php $this->section('modal_title');?> Détails de l'email <?php $this->endSection();?> <!-- Title -->
<?php $this->section('modal_close_text');?> Fermer <?php $this->endSection();?> <!-- Footer buttons -->
<?php if(!empty($error)) : $this->section('modal_error'); echo $error; $this->endSection(); endif;?> <!-- Error -->

<!-- ------------------------------------------------------------ -->
<!-- ------------------------------------------------------------ -->

<!-- Modal body -->
<?php if(empty($error)): $this->section('modal_body');?>

<fieldset id="emailRelationFieldset">
    <legend id="eventRelationLegend"> Informations </legend>
    <div class="row m-2">
        <div class="col-2 font-weight-bold"> Date d'envoi </div>
        <div class="col-10"> <?php if(isset($email->created_at)) echo $email->created_at;?> </div>
    </div>
    <div class="row m-2">
        <div class="col-2 font-weight-bold"> De </div>
        <div class="col-10"> 
            <?php echo $email->sender->name . ' ' . $email->sender->lastname;?>
            <?php if(!empty($email->sender->id_user)) echo fontawesome('h-square');?>
        </div>
    </div>
    <?php foreach(['to', 'cc', 'cci'] as $recip):?>
        <?php $selected = $recip . '_selected';?>
        <?php $text = $recip . '_text';?>
        <?php if(!empty($email->$selected) || !empty($email->$text)):?>
            <div class="row m-2">
                <div class="col-2 font-weight-bold"> <?php echo $recip=='to' ? 'À' : ucfirst($recip);?> </div>
                <div class="col-10"> 
                    <?php if(!empty($email->$selected)) : foreach($email->$selected as $to) :?>
                        <?php echo htmlspecialchars($to->name . ' ' . $to->lastname . '<' . $to->email . '>');?>
                        <br>
                    <?php endforeach; endif;?>
                    <?php if(!empty($email->$text)) : foreach($email->$text as $to) :?>
                        <?php echo htmlspecialchars($to);?>
                        <br>
                    <?php endforeach; endif;?>
                </div>
            </div>
        <?php endif;?>
    <?php endforeach;?>        
</fieldset>
<fieldset id="emailContentFieldset">
    <legend id="emailContentLegend"> Contenu </legend>
    <?php if(isset($email->reference)):?>
        <div class="row m-2">
            <div class="col-2 font-weight-bold"> Référence </div>
            <div class="col-10"> <?php echo $email->reference;?> </div>
        </div>
    <?php endif;?>
    <div class="row m-2">
        <div class="col-2 font-weight-bold"> Sujet </div>
        <div class="col-10"> <?php if(isset($email->subject)) echo $email->subject;?> </div>
    </div>
    <div class="row m-2">
        <div class="col-2 font-weight-bold"> Message </div>
        <div class="col-10"> 
            <div class="bg-light border my-2 p-4">
                <?php if(isset($email->message)) echo $email->message;?>
            </div>
        </div>
    </div>
</fieldset>
<?php if(!empty($email->attachs_doc) || !empty($email->attachs_img)) :?>            
    <fieldset id="emailAttachmentFieldset">
        <legend id="emailAttachmentLegend"> Pièces jointes </legend>
        <?php if(!empty($email->attachs_doc)):?>
            <div class="row m-2">
                <div class="col-2 font-weight-bold"> Documents </div>
                <div class="col-10"> 
                    <?php foreach($email->attachs_doc as $doc):?>
                        <a href="<?php echo base_url('file/read/doc/' . $doc->url);?>" target="_blank"> <?php echo $doc->name;?> </a> <br>
                    <?php endforeach;?>
                </div>
            </div>
        <?php endif;?>
        <?php if(!empty($email->attachs_img)):?>
            <div class="row m-2">
                <div class="col-2 font-weight-bold"> Images </div>
                <div class="col-10">
                    <div class="row no-gutters m-2">
                        <?php foreach($email->attachs_img as $img):?>
                            <div class="col-lg-2 col-md-3 col-sm-2 pr-2">
                                <div class="card h-100 pt-2 px-2 pb-0">
                                    <a href="<?php echo base_url('file/read/' . $img->url);?>" target="_blank">
                                        <img src="<?php echo base_url('file/read/' . $img->url);?>" class="card-img-top my-2" alt="" style="object-fit:contain;" />
                                    </a>
                                </div>
                            </div>
                        <?php endforeach;?> 
                    </div>   
                </div>
            </div>
        <?php endif;?>
    </fieldset>      
<?php endif;?>  
<!-- <?php if(!empty($email->attachment_doc) || !empty($email->attachment_img)) :?>            
    <fieldset id="emailAttachmentFieldset">
        <legend id="emailAttachmentLegend"> Pièces jointes </legend>
        <?php if(!empty($email->attachment_doc)):?>
            <div class="row m-2">
                <div class="col-2 font-weight-bold"> Documents </div>
                <div class="col-10"> 
                    <?php foreach($email->attachment_doc as $doc):?>
                        <a href="<?php echo $doc;?>" target="_blank"> <?php echo basename($doc);?> </a> <br>
                    <?php endforeach;?>
                </div>
            </div>
        <?php endif;?>
        <?php if(!empty($email->attachment_img)):?>
            <div class="row m-2">
                <div class="col-2 font-weight-bold"> Images </div>
                <div class="col-10">
                    <div class="row no-gutters m-2">
                        <?php foreach($email->attachment_img as $img):?>
                            <div class="col-lg-2 col-md-3 col-sm-2 pr-2">
                                <div class="card h-100 pt-2 px-2 pb-0">
                                    <a href="<?php echo $img;?>" target="_blank">
                                        <img src="<?php echo $img;?>" class="card-img-top my-2" alt="" style="object-fit:contain;" />
                                    </a>
                                </div>
                            </div>
                        <?php endforeach;?> 
                    </div>   
                </div>
            </div>
        <?php endif;?>
    </fieldset>      
<?php endif;?>       -->

<?php $this->endSection(); endif;?>

