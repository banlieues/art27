<script type="text/javascript">

// ------------------------------------------------------------------
// Filter graph management
// ------------------------------------------------------------------
$(document).on('click', '.filter-input', function() {
    const group = $(this).closest('.check-all-group');
    $('.filter-input', group).each(function() {
        let container_id = 'container-' + $(this).val();
        if($(this).prop('checked') === true) $('#' + container_id).attr('hidden', false);
        else if($(this).prop('checked') === false) $('#' + container_id).attr('hidden', true);
    });
});
$(document).on('hidden.bs.collapse', '.filter-collapse', function() {
    js_filter(this);
});
function js_filter(elem)
{
    const group = $(elem);
    const group_id = $(elem).attr('id');
    if(group_id.toLowerCase().includes('trend')) type = 'trend';
    else if (group_id.toLowerCase().includes('chart')) type = 'chart';
    let graphs = new Array();
    $('.filter-input:checked', group).each(function() {
        graphs.push($(this).val());
    });
    $.post("<?php echo base_url('enquete/set_filter/');?>", {'filter' : graphs, 'type' : type });  
}

// ------------------------------------------------------------------
// Resize iframe to fit content
// ------------------------------------------------------------------
$(document).on('shown.bs.tab', '#nav-prevfr-tab, #nav-prevnl-tab', function() {
    const target = $(this).attr('href');
    const iframe = $('iframe:visible', target);
    $(iframe).each(function() {
        iframe_resize(this);
    });
});
$(document).on('shown.bs.collapse', '#preview', function() {
    const iframe = $('iframe:visible', this);
    $(iframe).each(function() {
        iframe_resize(this);
    });
});
function iframe_resize(elem)
{
    const content = elem.contentWindow.document.body || elem.contentDocument.body;
    elem.style.height = content.scrollHeight + 'px';
}

// ------------------------------------------------------------------
// Init checkbox values
// ------------------------------------------------------------------
$(document).ready(function() {
    $('input[type="checkbox"].checkboxIs').each(function() {
        checkboxIs_init_value(this);
    });
});
 $(document).on('click','input[type="checkbox"].checkboxIs', function () {
    checkboxIs_set_value(this);
 });
$(document).on('show.bs.modal', '#modal', function () {
    $('input[type="checkbox"].checkboxIs', this).each(function() {
        checkboxIs_init_value(this);
    });
});
function checkboxIs_init_value(elem)
{
    $(elem).before('<input type="hidden" name=' + $(elem).attr('name') + ' value=0>');
    checkboxIs_set_value(elem);
}
function checkboxIs_set_value(elem)
{
    const hidden = $('input[type="hidden"][name="' + $(elem).attr('name') + '"]');
    if($(elem).is(':checked') || $(elem).prop('checked')===true){
        $(hidden).attr('checked', false);
        $(elem).attr('checked', true);
    }
    else {
        $(hidden).attr('checked', true);
        $(elem).attr('checked', false);
    }
}

// ------------------------------------------------------------------
// Modal cleaning when hidden
// ------------------------------------------------------------------
$(document).on('hidden.bs.modal','#modal, #myModalUpload', function (e) {
    reset_modal_when_hidden(this);
});
function reset_modal_when_hidden(elem)
{
    $('.modal-dialog', elem).attr('class','modal-dialog modal-dialog-scrollable');
    $('.modal-title', elem).html('');
    $('.modal-body', elem).html('');
    $('.modal-footer', elem)
        .removeClass('d-none').addClass('d-inline')
        .html('<button type="button" class="btn btn-outline-secondary modal-close" data-dismiss="modal">Annuler</button>');
    $('.plusminus-group', elem).each(function() {
        _set_plusminus(this);
    });    
}

// ------------------------------------------------------------------
// Dynamic fitting top margin
// ------------------------------------------------------------------
(function() {
    js_fit_div();
})();
$(window).on('resize', function() {
    // wrapper dynamically positionned under fixed banner
    js_fit_div();
});
function js_fit_div() {
    $('nav#sidebar').css({
        'position': 'fixed',
        'top': $('#nav_general').outerHeight()});
    $('#topbar').css({
        'top': $('#nav_general').outerHeight(),
        'left': $('nav#sidebar').outerWidth()});
    $('main').css({
//        'margin-top': $('#nav_general').outerHeight(),
        'margin-left': $('nav#sidebar').outerWidth()});
    let height = $('#nav_general').outerHeight() + $('#topbar').outerHeight() + 10;
    $('.sticky-top').css({
        'top': $('#nav_general').outerHeight() + $('#topbar').outerHeight() + 20});
};

// ------------------------------------------------------------------
// Dynamic margin-top for anchor when click on menu to appear under top banner
// ------------------------------------------------------------------
(function() {
    js_set_anchor_margin();
})();
function js_set_anchor_margin() {
    let height = $('#nav_general').outerHeight() + $('#topbar').outerHeight() + 10;
    $('a[href^="#"]','.sticky-top').on('click',function(e) {
        e.preventDefault();
        let anchor = $(this).attr('href');
        anchor = anchor.split('#')[1];
        let anchor_top = $('#'+anchor).position().top;
        let anchor_offset = $('#'+anchor).offset().top;
        $(document).scrollTop(anchor_offset - height);
    });
};

</script>

