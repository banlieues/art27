/* 
 * Author: Karol Butcher
 * v1 2021
 */

jQuery(document).ready(function()
{
/*  ----------------------------------Load content of view in modal -------------------------------
    * Need components/ban_modal.php to works
     * --- Element need the following attribute 
     * href -> link to charge views in ajax
     * data-view-title -> Tite for modal header
     * class -> modalView
*/
     
     $(document).on("click",".modalView",function(){ 
        //change for previous state
        var title=$(this).attr("data-view-title");
        var url=$(this).attr("href")
        $("#modalViewTitle").html(title) 
        $("#modalView").modal('show')
        $("#modalViewBody").html('<div class="text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></div>')
        jQuery.ajax
        ({	
                type:'POST',
                url: url,
                cache: false,
                success: function(html)
                { 
                    $("#modalViewBody").html(html);
                }

        }); 
        return false;
    });

    $(document).on("click",".confirmDelete",function(){ 
        //change for previous state
        var title=$(this).attr("data-view-title");
        var message=$(this).attr("data-view-message");
        var url=$(this).attr("href")
        $("#dataAlerDeleteTitre").html(title) 
        $("#dataAlertContentDelete").html(message) 
        
        $("#modalAlertDelete").modal('show')
        $("#ModalAlertDeleteConfirm").attr("href",url);
       
        return false;
    });

    $(document).on("click","#ModalAlertConfirmation",function(){ 
        //submitform
        var idFrom="#"+$("#ModalAlertConfirm").attr("data-id-from")
        $(idFrom).closest("form").submit()
        $("#modalAlert").modal('hide')
        
       
    })


    $(document).on("click","#ModalAlertDeleteCancel",function(){ 
        //submitform
        
        $("#modalAlertDelete").modal('hide')
       
    })


    //delete value when modal is closed
    $('#modalAlertDelete').on('hide.bs.modal', function () {
        $("#dataAlerDeleteTitre").html(null) 
        $("#dataAlertContentDelete").html(null) 
        $("#ModalAlertDeleteConfirm").attr("href",null) 
    })	


    $('#modalView').on('hide.bs.modal', function () {
        $("#modalViewBody").html(null)
        $("#modalViewTitle").html(null)  
        
    })	
 /* ---------------------------------END REMPLACE ALERT FROM JS -------------------------------*/
 
 /* ---------------------------------Modal for previsualisation INJECTED FORM -------------------------------*/

/*-- Cas où l'on veut voir le formulaire à quoi il ressemble sans enter d'id article --*/
$(document).on("click",".previsualisationInjectedFormOnly",function(){ 
    var srcFrame=$(this).attr("href");
    var titleForm=$(this).closest("tr").find(".titleForm").text();
    $("#modalViewTitleIFrame").html("Prévisualisation "+titleForm);
    $("#iframeSrc").attr("src",srcFrame);
    $("#modalViewFrame").modal("show");
    
    return false;
})


/*-- Cas où l'on veut voir le formulaire à tester avec un id article --*/
 $(document).on("click",".previsualisationInjectedForm",function(){ 
    $("#modalAlertInjectedForm").modal('show');
    $("#iframeSrcInput").val($(this).attr("href"));
    return false;
})

$(document).on("click","#ModalCancelPrevisualisation",function(){ 
    $("#modalAlertInjectedForm").modal('hide');
    return false;
})

$(document).on("click",".previsualisationInjectedForm",function(){ 
    $("#modalAlertInjectedForm").modal('show');
    return false;
})



/*-- Donnéé à effacer si on ferme les modals --*/

$('#modalAlertInjectedForm').on('hide.bs.modal', function () 
{
    $("#iframeIdActivite").val("");
})

$('#modalViewFrame').on('hide.bs.modal', function () 
{
    var src_default=$('#modalViewFrame').attr("src-default");
    $("#iframeSrc").attr("src",src_default);
})	

 /* ---------------------------------Modal for SAVE REQUEST -------------------------------*/
 $(document).on("click",".aModalSaveQuery",function(){ 
    $("#modalSaveQuery").modal('show');
    return false;
})

$(document).on("click","#ModalCancelSaveQuery",function(){ 
    $("#modalSaveQuery").modal('hide');
    return false;
})

$(document).on("click","#ModalConfirmSaveQuery",function(){ 
    $("#formSaveQuery").submit();
    $("#modalSaveQuery").modal('hide');
    $("#loading_layer").show();
})

 /* ---------------------------------Modal for Update REQUEST -------------------------------*/
 $(document).on("click",".aModalUpdateQuery",function(){ 
    $("#modalUpdateQuery").modal('show');
    return false;
})

$(document).on("click","#ModalCancelUpdateQuery",function(){ 
    $("#modalUpdateQuery").modal('hide');
    return false;
})

$(document).on("click","#ModalConfirmUpdateQuery",function(){ 
    $("#formUpdateQuery").submit();
    $("#modalUpdateQuery").modal('hide');

    $("#loading_layer").show();

})


});



