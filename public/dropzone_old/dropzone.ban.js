Dropzone.options.myDropzone = {
    addRemoveLinks:true,
    dictRemoveFile: "Retirer",

    init: function() {

        var myDropzone = this;

        $('#AjouterDocumentModal').on('hide.bs.modal', function () 
        {
            myDropzone.removeAllFiles();
        });

        $('#AjouterTicketModal').on('hide.bs.modal', function () 
        {
        myDropzone.removeAllFiles();
        });
       
    }


  };



  

