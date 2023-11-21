<script type="text/javascript">

let zindex_note_modal;

$(document).on('show.bs.modal', '.modal', function() {
    $('.note-modal').each(function() {
        zindex_note_modal = $(this).css('z-index');
        console.log(zindex_note_modal);
        $(this).css('z-index', $.maxZIndex);
    });
});

function summernote_modal(elem)
    {
        const target = $(elem).attr('target');
        const html = $('div[contenteditable][name="' + target + '"]').html();
        $('.modal-dialog','#modal').addClass('modal-xl');
        $('.modal-title','#modal').html('Editer le contenu');
        $('.modal-body','#modal').html(''
            + '<div class="alert alert-success mb-2"> Pour faire une saut à la ligne sans marge inférieure : <kbd> alt + enter </kbd> </div>'
            + '<div class="summernote"> ' + html + ' </div>'
            );
        if(!$('#summernoteEdit').length) {
            $('.modal-footer','#modal').prepend(''
            + '<button id="summernoteEdit" type="button" class="btn btn-primary modal-close" onclick="summernote_modal_edit_content(this, \'' + target + '\');" data-dismiss="modal"> Valider </button>'
            );
        }

        $('#modal').modal('show');  
    }

    function summernote_modal_edit_content(elem, target)
    {
        const html = $('.summernote', '#modal').summernote('code');
        const div = $('div[contenteditable][name="' + target + '"]');
        $(div).html(html);
        $(div).focus();
    }

    (function() {
        $('.summernote').each(function() {
            set_summernote(this);
        });
    })();
    $(document).on('shown.bs.modal','#modal', function (e) {
        $('.summernote', this).each(function() {
            set_summernote(this);
        });
    });
    function set_summernote(elem) {
        $(elem).summernote({
            lang: 'fr-FR',
            height: 230,
            tabsize: 2,
            toolbar: [
                ['style', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['height', ['height']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                // ['insert', ['link', 'picture', 'emoji']],
                ['view', ['fullscreen', 'codeview']]
            ],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
            dialogsInBody: true,
            tableClassName: function()
                {
                    $(this).addClass('table table-bordered')
                        .attr('cellspacing', 0)
                        .attr('border', 1)
                        .css('borderCollapse', 'collapse')
                        .css('width', '99%');
                    $(this).find('td').css('borderColor', '#ccc');
                },
            callbacks: {
                onImageUpload: function(image) {
                    summernoteUploadImage(elem, image[0]);
                },
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

                    e.preventDefault();

                    // Firefox fix
                    setTimeout(function () {
                        document.execCommand('insertText', false, bufferText);
                    }, 10);
                },
            }
        });
    }

    function summernoteUploadImage(elem, image) {
        var data = new FormData();
        data.append('img', image);
        $.ajax({
            url: "<?php echo base_url('file/summernote/upload/image');?>",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(result) {
                if(result.src) {
                    let imageDiv = $('<img>').attr('src', result.src.trim()).attr('id_attach', result.id_attach);
                    $(elem).summernote('insertNode', imageDiv[0]);
                } else {
                    alert('Une erreur est survenue lors du chargement de la photo. Seuls les formats JPEG, PNG et GIF sont autorisés.');
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

</script>



