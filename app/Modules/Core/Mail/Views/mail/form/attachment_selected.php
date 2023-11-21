<?php if(!empty($attachs_doc)):?>
    <div class="my-2">
        <?php foreach($attachs_doc as $doc):?>
            <div class="form-check row">
                <div class="col-10">
                    <input type="checkbox" class="form-check-input" name="ids_attach_selected[]" checked
                        value="<?php echo $doc->id_attach;?>"
                    />
                    <a href="<?php echo $doc->url;?>" target="_blank">
                        <label class="form-check-label"> <?php echo $doc->name;?> </label>
                    </a>
                </div>
            </div>
        <?php endforeach;?>
    </div>
<?php endif;?>
<?php if(!empty($attachs_img)):?>
    <div class="row no-gutters my-2">
        <?php foreach($attachs_img as $img):?>
            <div class="col-lg-2 col-md-3 col-sm-2 pr-2">
                <div class="card h-100 pt-2 px-2 pb-0">
                    <input type="checkbox" name="ids_attach_selected[]" value="<?php echo $img->id_attach;?>" checked/>
                    <a href="<?php echo $img->url;?>" target="_blank">
                        <img src="<?php echo $img->url;?>" class="card-img-top my-2" alt="" style="object-fit:contain;" />
                    </a>
                </div>
            </div>
        <?php endforeach;?> 
    </div>   
<?php endif;?>