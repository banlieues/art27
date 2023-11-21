<script>
// $(document).ready(function() {
document.addEventListener("DOMContentLoaded", function() {
    // $('#content').content('destroy');
    $('#content').summernote({
        // airMode: false,
        // toolbar: true,
        lang: 'fr-FR',
        height: 300,
        // minHeight: null,
        // maxHeight: null,
        // focus: true,
        // tabsize: 2,
        placeholder: 'Type your text here ...',
        // fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
        // fontSizeUnits: ['px', 'pt'],
        // disableDragAndDrop: true
        // shortcuts: false,
        // tabDisable: false,
        // codeviewFilter: false,
        // codeviewIframeFilter: true,
        // disableGrammar: false,
        toolbar: [
            ['style', ['style']],
            ['view', ['undo', 'redo']],
            // ['font', ['bold', 'underline', 'clear', 'backcolor', 'forecolor']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            // ['insert', ['link', 'picture', 'video']],
            ['insert', ['link', 'picture', 'hr']],
            // ['view', ['fullscreen', 'codeview', 'help']],
            ['view', ['codeview']],
            // ['HelloButton', ['hello']],
            ['TagsButton', ['Tags']],
            ['IconsButton', ['Icons']],
            
        ],
        // buttons: {hello: HelloButton},
        buttons: {Tags: TagsButton},

    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    $('#email_body').summernote({
        // airMode: false,
        // toolbar: true,
        lang: 'fr-FR',
        height: 150,
        // minHeight: null,
        // maxHeight: null,
        // focus: true,
        // tabsize: 2,
        placeholder: 'Type your text here ...',
        // fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
        // fontSizeUnits: ['px', 'pt'],
        // disableDragAndDrop: true
        // shortcuts: false,
        // tabDisable: false,
        // codeviewFilter: false,
        // codeviewIframeFilter: true,
        // disableGrammar: false,
        // dialogsInBody: true,
        // dialogsFade: true,
        toolbar: [
            ['style', ['style']],
            ['view', ['undo', 'redo']],
            // ['font', ['bold', 'underline', 'clear', 'backcolor', 'forecolor']],
            // ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            // ['table', ['table']],
            // ['insert', ['link', 'picture', 'video', 'hr']],
            ['insert', ['link', 'hr']],
            // ['view', ['fullscreen', 'codeview', 'help']],
            ['view', ['codeview']],
            // ['HelloButton', ['hello']],
            ['TagsButton', ['Tags']],
        ],
        // buttons: {hello: HelloButton},
        buttons: {Tags: TagsButton},
    });
});
</script>

<script>
var TagsButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.buttonGroup([
        ui.button({
            className: 'dropdown-toggle',
            contents: '<i class="fa fa-tags"></i> <i class="note-icon-caret"></i>',
            tooltip: 'Custom Tags',
            container: 'body', // Fix Tooltip
            data: {toggle: 'dropdown'},
        }),
        ui.dropdown({
            className: 'drodown-style',
            items: [
                '#INSERER_SAUT_PAGE#',
                '#LOGO_CEMEA#', 
                '#ADRESSE_FENETRE#',
                '#ADRESSE_ENFANT#',
                'ADRESSE_FACTURE',
                '#NOM_CONTACT#',
                '#PRENOM#',
                '#TITRE#',
                '#DATES_RA#',
                '#LIEU#',
                '#SOLDE#',
                '#IDACT#',
                '#PIED_SJ#',
                '#PIED_EP#',
                '#PIED_BX#',
       
            ],
            callback: function (items) {
                $(items).find('a.note-dropdown-item').on('click', function(event) {
                    context.invoke("editor.insertText", $(this).html());
                    event.preventDefault(); // Fix Goto Top
                })
            }
        })
    ]);

    return button.render();
}
</script>



<script>
/*
var HelloButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.button({
        contents: '<i class="far fa-smile"/> Hello',
        tooltip: 'Hello',
        container: 'body', // Fix Tooltip
        click: function () {
            context.invoke('editor.insertText', 'Hello World!');
        }
    });

    return button.render();
}
*/
</script>

<script>
/* Finally ajaxs isn't really required here ... */
/*
// $("#idForm").submit(function(e) {});
$(document).on("click", "#saveData", function(event) {
    event.preventDefault();
    var form = $(this);
    var myData = $("#myData").text();

    $.ajax({
        url: '<URL>',
        type: 'POST',
        dataType: 'json',
        data: { 'myData': myData },
        data: form.serialize(), 
        success: function(data){
            alert('Save');
        },
        error: function (data) {
            console.log(data);
        },
    });
});
*/
</script>