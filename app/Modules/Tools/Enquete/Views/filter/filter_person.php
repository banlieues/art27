<?php $id_contact = session('filter') && isset(session('filter')->id_contact) && isset(session('filter')->id_contact->value) ? session('filter')->id_contact->value : null;?>
<?php $text = session('filter') && isset(session('filter')->id_contact) && isset(session('filter')->id_contact->text) ? session('filter')->id_contact->text : null;?>

<div class="row mb-2">
    <label for="enqueteIdPerson" class="col-form-label col-4"> <strong> Demandeur </strong> </label>
    <div class="col-8">
        <input type="text" id="enquetePerson" 
            class="form-control <?php if(!empty($id_contact)):?> highlighted <?php endif;?>" 
            autocomplete="off"
            value="<?php echo $text;?>" 
        />
        <input type="hidden" id="enqueteIdPerson" 
            name="id_person" 
            value="<?php echo $id_contact;?>"
        />
    </div>

    <style>
    .ui-autocomplete {
        z-index: 9999 !important;
    }
    </style>
    
    <script type="text/javascript">
        $('#modal').on('show.bs.modal', function() {
            const input_auto = $('#enquetePerson', this);
            const hidden_id = $('#enqueteIdPerson', this);
            $(input_auto).autocomplete({
                minLength: 0,
                source: <?php echo json_encode($person_list);?>,
                select: function (event, ui) {
                    event.preventDefault();
                    $(input_auto).val(ui.item.label);
                    $(hidden_id).val(ui.item.id);
                    return false;
                },
            });
        } );
    </script>
</div>