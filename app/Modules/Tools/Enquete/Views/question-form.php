<div class="row mb-2">
    <label for="num_question" class="col-2 col-form-label">N°</label>
    <div class="col-10">
        <input type="text" class="form-control w-auto" name="num_question" id="num_question" readonly
            <?php if($form=='details' && !empty($question->num_question)):?>
                value="<?php echo $question->num_question;?>"
            <?php elseif($form=='new'):?>
                value="<?php echo $next_num_question;?>"
            <?php endif;?>
        />
    </div>
</div>
<div class="row mb-2">
    <label for="name_question" class="col-2 col-form-label">Référence</label>
    <div class="col-10">
        <input type="text" class="form-control w-auto" name="name_question" id="name_question" 
            value="<?php if(!empty($question->name_question)) echo $question->name_question;?>" 
            <?php if($form=='details'):?> readonly <?php endif;?>
        />
        <?php if($form=='new'):?>
            <small> La référence doit être toute attachée et minuscule. Il sera impossible de la modifier ensuite. </small>
        <?php endif;?>
    </div>
</div>
<div class="row mb-2">
    <label for="type_question" class="col-2 col-form-label">Type</label>
    <div class="col-10">
        <select class="form-control w-auto" name="type_question" id="type_question" 
            value="<?php if(!empty($question->type_question)) echo $question->type_question;?>" 
            <?php if($form=='details'):?> readonly <?php endif;?>
            >
            <option value="" selected> - Sélectionner le type de question - </option>
            <?php foreach($type_question_list as $type):?>
                <option value="<?php echo $type->id;?>"
                    <?php if($form=='details' && $type->id == $question->type_question):?> selected <?php endif;?>
                    > 
                    <?php echo $type->label_fr;?> 
                </option>
            <?php endforeach;?>
        </select>
        <?php if($form=='new'):?>
            <small> Une fois enregistré, il sera impossible de modifier le type de la question. </small>
        <?php endif;?>
    </div>
</div>
<div class="row mb-2">
    <label for="question_fr" class="col-2 col-form-label">Question FR</label>
    <div class="col-10">
        <input type="text" class="form-control" name="question_fr" id="question_fr" value="<?php if(!empty($question->question_fr)) echo $question->question_fr;?>"/>
    </div>
</div>
<div class="row mb-2">
    <label for="aide_question_fr" class="col-2 col-form-label">Aide FR</label>
    <div class="col-10">
        <input type="text" class="form-control" name="aide_question_fr" id="aide_question_fr" value="<?php if(!empty($question->aide_question_fr)) echo $question->aide_question_fr;?>"/>
    </div>
</div>
<div class="row mb-2">
    <label for="question_nl" class="col-2 col-form-label">Question NL</label>
    <div class="col-10">
        <input type="text" class="form-control" name="question_nl" id="question_nl" value="<?php if(!empty($question->question_nl)) echo $question->question_nl;?>"/>
    </div>
</div>
<div class="row mb-2">
    <label for="aide_question_nl" class="col-2 col-form-label">Aide NL</label>
    <div class="col-10">
        <input type="text" class="form-control" name="aide_question_nl" id="aide_question_nl" value="<?php if(!empty($question->aide_question_nl)) echo $question->aide_question_nl;?>"/>
    </div>
</div>
<div class="row mb-2">
    <label for="is_not_required" class="col-2 col-form-label pt-0"> Eventuellement vide </label>
    <div class="col-10">
        <div class="form-check form-check-inline">
            <input class="checkboxIs form-check-input" type="checkbox" id="is_not_required" name="is_not_required" value="1"
                <?php if(!empty($question->is_not_required)):?> checked <?php endif;?>
                >
            <label class="form-check-label" for="is_not_required"> Oui </label>
        </div>
    </div>
</div>
<!--    <div class="row mb-2">
    <label for="has_comment_area" class="col-2 col-form-label pt-0">Commentaire</label>
    <div class="col-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input disabled" type="radio" name="has_comment_area" id="has_comment_area_1" value="1" disabled
                <?php // if(isset($question->has_comment_area) && $question->has_comment_area == 1):?> checked <?php // endif;?>
                >
            <label class="form-check-label" for="has_comment_area_1">Oui</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input disabled" type="radio" name="has_comment_area" id="has_comment_area_0" value="0" disabled
                <?php // if(isset($question->has_comment_area) && $question->has_comment_area == 0):?> checked <?php // endif;?>
                >
            <label class="form-check-label" for="has_comment_area_0">Non</label>
        </div>
    </div>
</div>-->
<?php if($form=='details'):?>
    <hr>
    <div class="row mb-2">
        <label for="aide_question_nl" class="col-2 col-form-label">Aperçu FR</label>
        <div class="col-10">
            <?php echo $question->preview->fr;?>
        </div>
    </div>
    <div class="row mb-2">
        <label for="aide_question_nl" class="col-2 col-form-label">Aperçu NL</label>
        <div class="col-10">
            <?php echo $question->preview->nl;?>
        </div>
    </div>
<?php endif;?>