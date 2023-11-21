/* 
 * Author: Karol Butcher
 * v1 2021
 */




jQuery(document).ready(function()
{
    //Dimension automatic of textarea and adapation size textarea if typing text
    $.each($('textarea'), function() {
        var offset = this.offsetHeight - this.clientHeight;
        var resizeTextarea = function(e) {
            $(e).css('height', 'auto').css('height', e.scrollHeight + offset);
        };
        $(this).on('keyup input', function() { resizeTextarea(this); });
        resizeTextarea(this);
    });

    //Submit change select without click to button send
     $(document).on("change",".select_submit",function(){ $(this).closest("form").submit(); })

     //change_url
     $(document).on("change",".url_change",function(){ var url_change=$(this).attr("url_change"); window.location.href = url_change;})
     
  
/*  ----------------------------------REMPLACE ALERT FROM JS -------------------------------
    * Need components/ban_modal.php to works
     * --- Element need the following attribute (cf infra with a exemple)
     * id="ChangeSelectInscription<?=$inscription->id_inscription?>" = id of element
     * data-type-confirm="select"  = type of element
     * data-value-origin="<?=$inscription->statutsuivi?>" = value before the change
     * data-alert-titre="Changement d'un statut d'inscription" = title of modal
     * data-alert-content=" modifier le statut de l'inscription de <?=$inscription->nom?> <?=$inscription->prenom?> à <?=$inscription->idact?>" = message of model
     * ---- Class select_change_submit initialiae js
     * class="select_change_submit" 
     * ---- Element has balise form with action to send
    
*/


     $(document).on("change",".select_change_submit",function(){ 
        //inject value 
       // alert();
        $("#dataAlertTitre").html($(this).attr("data-alert-titre"))
        $("#dataAlertContent").html($(this).attr("data-alert-content"))

        $("#ModalAlertConfirm").attr("data-type-confirm",$(this).attr("data-type-confirm"))
        $("#ModalAlertConfirm").attr("data-id-from",$(this).attr("id"))

        $("#ModalAlertCancel").attr("data-type-confirm",$(this).attr("data-type-confirm"))
        $("#ModalAlertCancel").attr("data-id-from",$(this).attr("id"))
        $("#ModalAlertCancel").attr("data-value-origin",$(this).attr("data-value-origin"))

        //open modal
        $("#modalAlert").modal('show')
     })


     $(document).on("click","#ModalAlertCancel",function(){ 
        //change for previous state
        if($("#ModalAlertCancel").attr("data-type-confirm")=="select")
        {
            var idFrom="#"+$("#ModalAlertCancel").attr("data-id-from");
            var dataValueOrigin=$("#ModalAlertCancel").attr("data-value-origin"); 
            $(idFrom).val(dataValueOrigin);
        }
        $("#modalAlert").modal('hide')
    })
    $(document).on("click","#ModalAlertConfirm",function(){ 
        //submitform
        var idFrom="#"+$("#ModalAlertConfirm").attr("data-id-from")
        var container="#c"+$("#ModalAlertConfirm").attr("data-id-from");
        var url=$(idFrom).closest("form").attr("action")
        var dataString=$(idFrom).closest("form").serialize()
        
        $(container).html('<div style="text-align:center"><i class="fas fa-circle-notch fa-spin"></i></div>' );
        $("#modalAlert").modal('hide');
        //alert(url); alert(dataString);
        jQuery.ajax
        ({	
                type:'POST',
                data:dataString,
                url: url,
                cache: false,
                success: function(html)
                { 
                    $(container).html(html);
                }

        }); 
        return false;
    })
    //delete value when modal is closed
    $('#modalAlert').on('hide.bs.modal', function () {
        $("#dataAlertTitre").html(null)
        $("#dataAlertModal").html(null)

        $("#ModalAlertConfirm").attr("data-type-confirm",null)
        $("#ModalAlertConfirm").attr("data-id-from",null)

        $("#ModalAlertCancel").attr("data-type-confirm",null)
        $("#ModalAlertCancel").attr("data-id-from",null)
        $("#ModalAlertCancel").attr("data-value-origin",null)
    })	   
    //submit a form entity -> hidden buttons and display loader
    $('#form-entity').on('submit', function () {
        $(".zone-button-form").hide();
        $(".zone-submit-loading").show();
    })  

      //submit a form entity -> hidden buttons and display loader
      $('.btn-entity').on('submit', function () {
        $(".zone-button-form").hide();
        $(".zone-submit-loading").show();
    })  

  
});
/* ---------------------------------END REMPLACE ALERT FROM JS -------------------------------*/

