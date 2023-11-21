<script>
    // --------------------------
    // Modal
    // --------------------------
    /* 
    * Author: Karol Butcher
    * v1 2021
    */


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
    $(document).on('hide.bs.modal', '#modalAlertDelete', function () {
        $("#dataAlerDeleteTitre").html(null) 
        $("#dataAlertContentDelete").html(null) 
        $("#ModalAlertDeleteConfirm").attr("href",null) 
    })	

    $(document).on('hide.bs.modal', '#modalView', function () {
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

    $(document).ready(function() 
    {
        $(".ban_viewModal").click(function() {

            const modal_title = $(this).attr("modal-title");
            $("#modalViewTitle").html(modal_title);

            if($(this).attr('modal-ajax')) {
                const url = $(this).attr('modal-ajax');
                $.get(url, function(view) {
                    $("#modalViewBody").html(view);
                    $("#modalView").modal('show');
                });
            } else if($(this).attr('modal-body')) {
                const modal_body = $(this).attr("modal-body");
                $("#modalViewBody").html(modal_body);
                $("#modalView").modal('show');
            }
        });
    });

    // --------------------------
    // Modal waiting mask
    // --------------------------
    function modal_show_waiting_mask(message='')
    {
        let html = '<div class="alert alert-warning text-center"><div class="mb-4"> Merci de patienter... <br> ' + message + '</div><div> <i class="fa fa-spin fa-spinner"></i> </div></div>';
        $('.modal-body').html(html);
        $('#modal').modal('show');
    }

    // --------------------------
    // Modal show
    // --------------------------
    function modal_show(elem, url, formdata, message='', callback)
    { 
        if(formdata == undefined) { formdata = new FormData(); }

        // let formdata = new FormData(); 
        // if($(elem).attr('form')) {
        //     const form = $('#' + $(elem).attr('form'))[0];
        //     formdata = new FormData(form);
        // }
        // if($(elem)[0].hasAttribute('post-data')) {
        //     let datas = JSON.parse($(elem).attr('post-data'));
        //     for(let data in datas) {
        //         formdata.append(data, datas[data]);
        //     }
        // }
        if($('#modal').hasClass('show')) {
            let is_first = 1;
            $('#modal').modal('hide');
            $('#modal').on('hidden.bs.modal', function() {
                if(is_first == 1) {
                    is_first = 0;
                    modal_hide();
                    ajax_modal(url, formdata, message, callback);
                }
            });
        } else {
            ajax_modal(url, formdata, message, callback);
        }
    }

    // --------------------------
    // Modal ajax call
    // --------------------------
    function ajax_modal(url, formdata, message, callback=null)
    {
        modal_show_waiting_mask(message);
        $.ajax({
            url: url,
            data: formdata,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(result) {
                result = JSON.parse(result);
                if(result.dialog_size) { $('.modal-dialog','#modal').addClass('modal-' + result.dialog_size); }
                if(result.dialog_is_full_height) { $('.modal-dialog','#modal').attr('style', 'height: calc(100% - 3.5rem)'); }
                if(result.header) { $('.modal-title','#modal').text(result.header); }
                if(result.body) { $('.modal-body', '#modal').html(result.body); }
                if(result.footer) { $('.modal-footer','#modal').prepend(result.footer); }
                if(result.close_button) { $('.modal-footer .modal-close','#modal').outerHtml(result.close_button); }
                if(result.close_text) { $('.modal-footer .modal-close','#modal').text(result.close_text); }
                tags_control();
                if(callback) { callback(); }
            }
        });
    }

    // ------------------------------------------------------------------
    // Modal cleaning when hidden
    // ------------------------------------------------------------------
    $(document).on('hidden.bs.modal','#modal', function (e) {
        modal_hide();
    });
    function modal_hide()
    {
        const modal = $('#modal');
        $('.modal-dialog', modal).attr('class','modal-dialog modal-dialog-centered modal-dialog-scrollable');
        $('.modal-dialog','#modal').removeAttr('style');
        $('.modal-title', modal).html('');
        $('.modal-body', modal).html('<div class="text-center"><i class="fa fa-spin fa-spinner"></i> </div>');
        $('.modal-footer', modal).attr('class', 'modal-footer d-flex align-items-end m-2');
        $('.modal-footer', modal).html('<button type="button" class="btn btn-sm btn-outline-secondary modal-close" data-bs-dismiss="modal">Annuler</button>');
        $('.plusminus-group', modal).each(function() {
            _set_plusminus(this);
        });
    }
</script>