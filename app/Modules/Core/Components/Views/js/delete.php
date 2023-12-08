<script>
    // --------------------------
    // Delete
    // --------------------------

    function delete_form()
    {
        $(".ban_deleteForm").click(function() {
          
            const id_delete = $(this).attr("id_delete");
            const action = $(this).attr("href");
            const text_alert = $(this).attr("text_alert");

            $("#dataAlertFormContentDelete").html(text_alert);
            $("#idDelete").val(id_delete);
            $("#formDelete").attr("action", action);

            $("#modalAlertDeleteForm").modal('show');

            return false;
        });

        $("#ModalAlertFormDeleteCancel").click(function() {
            $("#modalAlertDeleteForm").modal('hide');
            $("#dataAlertFormContentDelete").html(null);
            $("#idDelete").val(0);
            $("#formDelete").attr("action",'#');
        });  
        
        $('#ModalAlertFormDeleteCancel').on('hide.bs.modal', function () {
            $("#dataAlertFormContentDelete").html(null);
            $("#idDelete").val(0);
            $("#formDelete").attr("action",'#');
        })	

        $("#ModalAlertFormDeleteConfirmTTTESS").click(function() {
        
            var dataString = $(this).closest("#formDelete").serialize();
            var url=$(this).closest("#formDelete").attr("action");
            $(this).closest("#formDelete").hide();
            $(".ModalFormDeleteLoader").show();

            return false;
        });
    }

    $(document).ready(function() 
    {   
        delete_form();
    });
</script>