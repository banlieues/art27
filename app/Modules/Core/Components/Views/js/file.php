<script type="text/javascript">
     $(document).ready(function() 
    { 
// --------------------------
// File
// --------------------------
$("form#my-dropzone").dropzone({ 
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
 
function modal_file_upload(elem) {
    const group = $(elem).closest('.file-group');
    const input = $('input[type="file"]', group);
    if(typeof $(input)[0].files[0]==='undefined') {
        $('.upload_error', group).html("Vous n'avez pas choisi de fichier à importer.").attr('hidden', false);
    }
    else {
        $('.upload_error', group).html('').attr('hidden', true);
        $('.modal-title','#modal').html('<h4>Importation de fichier</h4>');
        $('.modal-body','#modal').html('<p>Un fichier va être envoyé vers la base de donnée. Veuillez attendre la fin du téléchargement avant d\'entamer une nouvelle action.</p>');
        $('.modal-footer','#modal').prepend('' +
            '<button type="button" class="btn btn-sm btn-success modal-close" data-dismiss="modal"' +
                'ref="' + $(group).attr('ref') + '" ' +
                'pk_value="' + $(group).attr('pk_value') + '" ' +
                'controller="' + $(group).attr('controller') + '" ' +
                'path="' + $(group).attr('path') + '" ' +
                'onclick="file_upload(this);"> Importer ' +
            '</button>'
        );
        $('#modal').modal('show');
    }
}

// function file_upload(elem) 
// {
//     const ref = $(elem).attr('ref');
//     const controller = $(elem).attr('controller');
//     const group = $('.file-group[ref="' + ref + '"');
//     let formdata = new FormData();
//     const uploads = $('input[type="file"]', group)[0].files;
//     for (let i=0; i < uploads.length; i++) {
//         formdata.append(ref + '[]', uploads[i]);
//     }
//     formdata.append('ref', ref);
//     formdata.append('pk_value', $(elem).attr('pk_value'));
//     formdata.append('path', $(elem).attr('path'));
    
//     $(group).html('<?php //echo fontawesome('spinner');?>');
//     $.ajax({
//         url: window.location.origin + '/' + controller + '/file_upload',
//         type: 'POST',
//         method: 'POST',
//         processData: false, // important
//         contentType: false, // important
//         dataType : 'html',
//         data: formdata,
//         cache: false,
//         error: function(xhr, status, error){
//             let errorMessage = xhr.status + ': ' + xhr.statusText;
//             alert('Error - ' + errorMessage);
//         },
//         success: function(view){
//             $(group).html(view);
//         }
//     });
// }

function modal_file_delete(elem, id_file) {
    const group = $(elem).closest('.file-group');
    const pk_value = $(group).attr('pk_value');
    $('.modal-title','#modal').html('<h4> Supprimer un fichier</h4>');
    $('.modal-body','#modal').html('<p> Vous allez supprimer un fichier associé à l\'événement. <br> Veuillez confirmer votre action. </p>');
    $('.modal-footer','#modal').prepend('' +
        '<button type="button" class="btn btn-danger modal-close" data-dismiss="modal" ' +
            'ref="' + $(group).attr('ref') + '" ' +
            'pk_value="' + $(group).attr('pk_value') + '" ' +
            'controller="' + $(group).attr('controller') + '" ' +
            'path="' + $(group).attr('path') + '" ' +
            'onclick="file_delete(this, ' + id_file + ');"> Supprimer ' + 
        '</button>');
    $('#modal').modal('show');
}

function file_delete(elem, id_file) 
{
    const ref = $(elem).attr('ref');
    const controller = $(elem).attr('controller');
    const group = $('.file-group[ref="' + ref + '"');
    
    let data = {};
    data.ref = ref;
    data.pk_value = $(elem).attr('pk_value');
    data.path = $(elem).attr('path');

    $(group).html('<?php echo fontawesome('spinner');?>');
    $.post(window.location.origin + '/' + controller + '/file_delete/' + id_file, data, function(view) {
        $(group).html(view);
    });
}

function download_file(filename, content) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/csv;charset=iso-8859-1,' + escape(content));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}

// --------------------------
// Get cell text of table
// --------------------------

// used in Gasap CRM
function table_export_csv(table_container_id, filename)
{
    const table = $('table', $('#' + table_container_id));
    let today = new Date();
    today = today.toISOString().slice(0,10).replace(/-/g, "");
    filename = today + '_' + filename + '.csv';
    GetTextFromTable(table, function(data) {
        let csvContent = "";
        data.forEach(function(rowArray) {
            let row = rowArray.join('";"');
            csvContent += '"' + row + '"\r\n';
        });
        download_file(filename, csvContent);
    });
}

function GetTextFromTable(table, callback)
{
    let data = [];
    let row = [];

    $('tr', table).each(function () {
        row = [];
        $('th, td', this).each(function() {
            let text = $(this).text().trim();
            // text = text.replace("\n", "");
            text = text.replace(/\s{2,}/gi, "\n");
            text = text.replace(/\s+:\s+/gi, " : ");
            text = text.replace(/dispo\s*:\s+/gi, "Dispo : ");
            text = text.replace(/horaire\s*:\s+/gi, "Horaire : ");
            text = text.replace(/jour\s*:\s+/gi, "Jour : ");
            text = text.replace(/local\s*:\s+/gi, "Local : ");
            text = text.replace(/max\s*:\s+/gi, "Max : ");
            text = text.replace(/nb\s*:\s+/gi, "Nb : ");
            text = text.replace(/prochaine date\s*:\s+/gi, "Prochaine date : ");
            text = text.replace(/(\d+)\.\s+/gi, "$1. ");
            // text = text.replace(/(\d*)\nMAX\n(\d*)\n(\d*)/g, "NB : $1\nMAX : $2\nDISPO : $3");
            console.log(text);
            row.push(text);
        });
        data.push(row);
    });
    callback(data);
}
});
</script>
