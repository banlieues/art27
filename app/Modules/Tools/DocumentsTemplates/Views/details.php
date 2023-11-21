
<?php $request = \Config\Services::request(); ?>

<?php
if (!$request->isAJAX())
{
    $this->extend("Layout\index");
    $this->section("body");
}
?>   

<!--
<style>
.sticky_action_bar {
    position: fixed;
    top: 10px;
    right: 24px;
    z-index: 1;
}
</style>
-->

<div class="row">
    <div class="col-md-12">
        <div class="card-header p-1 p-xl-1 sticky_button bg-light">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-auto align-self-center">
                    <h3 class="fs-4"><i class="<?php echo icon('file'); ?> mt-1 text-office"></i>  <?php echo lang('DocumentsTemplates.currenttemplate'); ?>: Détail de <?php echo $documents_template['label']; ?></h3>
                </div>
                <div class="col align-self-center">
                    <div class="text-end">
                    <a class="btn btn-office btn-sm" href="<?php echo base_url('documentstemplates').'?page='.$page; ?>">
                        <i class="<?php echo icon('goback'); ?>"></i> <?php echo lang('Buttons.goback'); ?>
                    </a>
                    <a class="btn btn-office btn-sm" href="<?php echo base_url('documentstemplates/duplicate').'?id='.$template_id.'&page='.$page; ?>">
                        <i class="<?php echo icon('duplicate'); ?>"></i> <?php echo lang('Buttons.duplicate'); ?>
                    </a>
                    <a class="btn btn-office btn-sm" href="<?php echo base_url('documentstemplates/edit').'?id='.$template_id.'&page='.$page; ?>">
                        <i class="<?php echo icon('edit'); ?>"></i> <?php echo lang('Buttons.edit'); ?>
                    </a>
                    </div>
                </div>   
            </div>
        </div>

        <?php if ($template_id): ?>
        <div class="card flex-fill border-top-office mb-4">
            <div class="card-body-off">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="template_id" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('id'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.id'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="template_id" name="template_id" value="<?php echo $documents_template['id']; ?>" placeholder="<?php echo lang('DocumentsTemplates.id'); ?> ...">
                                    </div>
                                </div> 
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="label" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.label'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="label" name="label" value="<?php echo $documents_template['label']; ?>" placeholder="<?php echo lang('DocumentsTemplates.label'); ?> ...">
                                    </div>
                                </div> 
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="description" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.description'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="description" name="description" value="<?php echo $documents_template['description']; ?>" placeholder="<?php echo lang('DocumentsTemplates.description'); ?> ...">
                                    </div>
                                </div> 
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="rank" class="col-md-3 col-form-label">
                                        <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.content'); ?>
                                    </label>
                                    <div class="col-md-9 p-3">
                                        <div id="content" disabled class="p-3 border shadow_off" name="content"><?php echo $documents_template['content']; ?></div>
                                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'content') : '' ?></span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="email_subject" class="col-md-3 col-form-label">
                                        <i class="<?php echo icon('mail-open'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.email_subject'); ?>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="email_subject" name="email_subject" value="<?php echo $documents_template['email_subject']; ?>" placeholder="<?php echo lang('DocumentsTemplates.email_subject'); ?> ...">
                                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email_subject') : '' ?></span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="email_body" class="col-md-3 col-form-label">
                                        <i class="<?php echo icon('mail-text'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.email_body'); ?>
                                    </label>
                                    <div class="col-md-9 p-3">
                                        <!--
                                        <input type="text" class="form-control" id="email_body" name="email_body" value="<?php // echo $documents_template['email_body']; ?>" placeholder="<?php // echo lang('DocumentsTemplates.email_body'); ?> ...">
                                        -->
                                        <div id="email_body" disabled class="p-3 border shadow_off" name="email_body"><?php echo $documents_template['email_body']; ?></div>
                                        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email_body') : '' ?></span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="created_at" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.created_at'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="created_at" name="created_at" value="<?php echo $documents_template['created_at']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="updated_at" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.updated_at'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="updated_at" name="updated_at" value="<?php echo $documents_template['updated_at']; ?>"  placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="created_by" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.created_by'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="created_by" name="created_by" value="<?php echo $documents_template['created_by']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="updated_by" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.updated_by'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-0" id="updated_by" name="updated_by" value="<?php echo $documents_template['updated_by']; ?>"  placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="actived" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('actived'); ?>" style="width:20px;"></i> <?php echo lang('DocumentsTemplates.actived'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="number" disabled class="form-control bg-white border-0" id="actived" name="actived" value="<?php echo $documents_template['actived']; ?>" placeholder="">
                                    </div>
                                </div> 
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary"></div>
        </div>
        <?php else: ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo lang('Errors.invalidrequest'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!--
<script>
window.onscroll = function() {stickers()};
var navbar = document.getElementById("action_bar");
var sticky = navbar.offsetTop - 10;
function stickers() {
    if (window.pageYOffset >= sticky) {navbar.classList.add("sticky_action_bar")}
    else {navbar.classList.remove("sticky_action_bar");}
}
</script>
-->

<script>
// $(document).ready(function() {
document.addEventListener("DOMContentLoaded", function() {
    // $('#content').content('destroy');
    // $('#content, #email_body').summernote({
    $('#content_off').summernote({
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
        // dialogsInBody: true,
        // dialogsFade: true,
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
            // ['insert', ['link', 'picture', 'video', 'hr']],
            ['insert', ['link', 'picture', 'hr']],
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
document.addEventListener("DOMContentLoaded", function() {
    $('#email_body_off').summernote({
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
                '{{NOM_CONTACT}}', 
                '{{PRENOM_CONTACT}}',
                '{{EMAIL_CONTACT}}',
                '{{ADRESSE_CONTACT}}',
                '{{CODE_POSTAL_CONTACT}}',
                '{{LOCALITE_CONTACT}}',
                '{{CIVILITE_CONTACT}}',
                '{{INTITULE_ACTIVITE}}',
                '{{REFERENCE_ACTIVITE}}',
                '{{DATE_DEBUT_ACTIVITE}}',
                '{{DATE_FIN_ACTIVITE}}',
                '{{DATES_ACTIVITE}}',
                '{{LIEU_ACTIVITE}}',
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

<?php if (!$request->isAJAX()) {$this->endSection();} ?>
