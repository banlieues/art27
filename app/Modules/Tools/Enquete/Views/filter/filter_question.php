<?php $id_question = session('filter') && isset(session('filter')->id_question) && isset(session('filter')->id_question->value) ? session('filter')->id_question->value : null;?>
<?php $ids_answer = session('filter') && isset(session('filter')->id_question) && isset(session('filter')->id_question->ids_answer) ? session('filter')->id_question->ids_answer : null;?>

<div class="row mb-2">
    <label for="enqueteIdQuestion" class="col-form-label col-4"> <strong> Question </strong> </label>
    <div class="col-8">
        <select  name="id_question" id="enqueteIdQuestion" onchange="js_answers_by_question_get(this, 'onchange');"
            class="form-control <?php if(!empty($id_question)):?> highlighted <?php endif;?>"
            >
            <option class="form-control" value="">Choisir un filtre</option>
            <?php foreach ($question_list as $question):?>
                <option class="form-control" value="<?php echo $question->id_question?>"
                    <?php if(!empty($id_question)):?> selected <?php endif;?>
                    >
                    <?php echo $question->question_fr;?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="row mb-2" id="enqueteFilterAnswer" 
    <?php if(empty($id_question)):?> hidden <?php endif;?>
    >
    <label for="enqueteTypeQuestion" class="col-form-label col-4"> <strong> Réponse </strong> </label>
    <div class="col-8">
        <select id="enqueteAnswer" name="ids_answer[]" 
            class="form-control bs-multi-select <?php if(!empty($ids_answer)):?> highlighted <?php endif;?>" 
            multiple title="Choisir une réponse"
            >
        </select>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        js_answers_by_question_get($('#enqueteNumQuestion'), 'onload');
    });
    $('enqueteNumQuestion').on('change', function() {
        js_answers_by_question_get($(this), 'onchange');
    });
    function js_answers_by_question_get(elem, onevent)
    {
        if($(elem).val().length>0) {
            const id_question = $(elem).val();
            // $('#enqueteAnswer').bsMultiSelect('destroy');
            $('#enqueteAnswer').html('');
            $.get(
                window.location.origin + '/enquete/answers/by/question/' + id_question + '/' + onevent, 
                function(options) {
                    options = JSON.parse(options);
                    for (let option of options) {
                        $('#enqueteAnswer').append($('<option>').val(option.value).attr('selected', option.selected).text(option.label));
                    }
                    $('#enqueteAnswer').closest('#enqueteFilterAnswer').attr('hidden', false);
                    $('#enqueteAnswer').bsMultiSelect('Update');
                }
            );
        } else {
            $('#enqueteFilterAnswer').attr('hidden', true);
            $('#enqueteAnswer').val('');
        }
    }
</script>