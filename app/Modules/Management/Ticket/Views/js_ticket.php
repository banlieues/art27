<script>

$(document).off("click",".ajouter_ticket_modal").on("click",".ajouter_ticket_modal", function(e){
     
    $("#GererTicketModalCRM").modal("hide");
        $(".container_GererTicketModalCRM").html(null);

    var id_demande=$(this).attr("id_demande");
    $(".drop_zone_id_demande").val(id_demande);
    $("#AjouterTicketModal").modal({
    keyboard: false,
    backdrop: 'static'

});
    $("#AjouterTicketModal").modal("show");

    return false;
});

$(document).off("click",".btn_submit_upload").on("click",".btn_submit_upload", function(e){

    $("#my-dropzone").submit();

    return false;
});

$(document).off("click",".close_AjouterTicketModal").on("click",".close_AjouterTicketModal", function(e){
        
        $("#AjouterTicketModal").modal("hide");
     
          location.reload(true);
     
      
      
       
       
        return false;
      });


</script>