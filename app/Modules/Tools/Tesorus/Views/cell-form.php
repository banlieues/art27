<!-- <div class="form-group row">
    <label class="col-2 col-form-label"> Référence </label>
    <div class="col-10">
        <input type="text" name="ref" class="form-control" value="<?php if(isset($cell->ref)) echo $cell->ref;?>"
            <?php if(isset($cell->ref)):?> disabled <?php endif;?>
            />
    </div>
</div>
-->
<?php if(isset($cell)):?>
    <input type="hidden" name="ref" value="<?php if(isset($cell->ref)) echo $cell->ref;?>"/>
<?php endif;?>
<div class="row mb-2">
    <label class="col-2 col-form-label"> <?php echo t("Label", $namespace);?> </label>
    <div class="col-10">
        <?php if(isset($cell->label_fr)):?>
            <input type="text" name="label_fr" class="form-control disabled" value="<?php echo $cell->label_fr;?>" readonly disabled/>
        <?php else:?>
            <input id="cellLabelFr" type="text" name="label_fr" class="form-control" value=""/>
            <div id="cellLabelFrInvalid" class="invalid-feedback d-none">
                <?php echo t("La thématique existe déjà.", $namespace);?>
            </div>
            <script type="text/javascript">
                $('#cellLabelFr').autocomplete({
                    minLength: 0,
                    source: window.location.origin + '/tesorus/cells/get',
                    select : function() {
                        $(this).addClass('is-invalid');
                        $('#cellLabelFrInvalid').removeClass('d-none');
                    }
                });

                $.get(window.location.origin + '/tesorus/cells/get', function(data) {
                    const cells = JSON.parse(data);
                    $('input[name="label_fr"]').on('input', function() {
                        const text = $(this).val().trim();
                        console.log($.inArray(text, cells));
                        if($.inArray(text, cells)>=0) {
                            $(this).addClass('is-invalid');
                            $('#cellLabelFrInvalid').removeClass('d-none');
                        } else {
                            $(this).removeClass('is-invalid');
                            $('#cellLabelFrInvalid').addClass('d-none');
                        }
                    });
                });
            </script>

            <div class="alert alert-warning my-2">
                <?php echo t("Une fois encodé, il ne sera plus possible de modifier le label FR.", $namespace);?>
            </div>
        <?php endif;?>
    </div>
</div>
<!-- <div class="row mb-2">
    <label class="col-2 col-form-label"> Label NL </label>
    <div class="col-10">
        <input type="text" name="label_nl" class="form-control" value="<?php if(isset($cell->label_nl)) echo $cell->label_nl;?>"/>
    </div>
</div> -->
<!-- <hr>
<div class="row mb-2">
    <label class="col-2 col-form-label"> Informations FR </label>
    <div class="col-10">
        <input type="text" name="annotation_fr" class="form-control" value="<?php if(isset($cell->annotation_fr)) echo $cell->annotation_fr;?>"/>
    </div>
</div>
<div class="row mb-2">
    <label class="col-2 col-form-label"> Informations NL </label>
    <div class="col-10">
        <input type="text" name="annotation_nl" class="form-control" value="<?php if(isset($cell->annotation_nl)) echo $cell->annotation_nl;?>"/>
    </div>
</div>
<hr> -->
<div class="row mb-2">
    <label class="col-2 col-form-label"> <?php echo t("Notes", $namespace);?> </label>
    <div class="col-10">
        <textarea name="comment" class="form-control h-auto" rows="3"><?php if(isset($cell->comment)) echo $cell->comment;?></textarea>
    </div>
</div>