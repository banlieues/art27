$("form#my-dropzone").dropzone({ 
    addRemoveLinks:true,
    dictRemoveFile: "Retirer",

    init: function() {

        var myDropzone = this;
        $('#AjouterDocumentModal').on('hide.bs.modal', function () 
        {
        myDropzone.removeAllFiles();
        });

        $('#AjouterTIcketModal').on('hide.bs.modal', function () 
        {
        myDropzone.removeAllFiles();
        });
       
    }
});




// Dropzone.options.myDropzone = {
//     addRemoveLinks:true,
//     dictRemoveFile: "Retirer",

//     init: function() {

//         var myDropzone = this;
//         $('#AjouterDocumentModal').on('hide.bs.modal', function () 
//         {
//         myDropzone.removeAllFiles();
//         });
       
//     }


//   };

  

