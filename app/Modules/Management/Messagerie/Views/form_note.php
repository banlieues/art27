<?php $request = \Config\Services::request(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>


<div class="row">
    <div class="col-md-12 col-sm-12 mt-2  <?php if($entity!="demande"):?>pb-5<?php endif;?>">
        <?php if($entity!="demande"):?>
            <?php $uri="set_note_no_ajax" ?>
        <?php else:?>
            <?php $uri="set_note" ?>
        <?php endif;?>
        <form id="form_note" action="<?=base_url()?>messagerie/<?=$uri?>">
       
            <div>
            <i>En option, vous pouvez indiquer un ou plusieurs destinataires</i>
            <?php echo $dataview->getElementFormByIndex("user_note","note");?>
            </div>
            <hr>
            <input type="hidden" name="entity" value="<?=$entity?>">
            <input type="hidden" name="id_entity" value="<?=$id_entity?>">
            <?php echo $dataview->getElementFormByIndex("subject_note","note");?>
            <?php echo $dataview->getElementFormByIndex("object_note","note");?>
           
        <?php if($entity!="demande"):?>

            <button class="btn btn-success btn-sm" type="submit">Enregistrer</button>
            <button class="btn btn-danger btn-sm">Annuler</button>

        <?php endif;?>
        </form>
    </div>
</div>


<script>

$.each($("textarea[name='object_note']"), function() 
			{
				var offset = this.offsetHeight - this.clientHeight;
				var resizeTextarea = function(e) {
					$(e).css('height', 'auto').css('height', e.scrollHeight + offset);
				};
				$(this).on('keyup input', function() { resizeTextarea(this); });
				resizeTextarea(this);
    		});
</script>