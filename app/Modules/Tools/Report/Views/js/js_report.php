<script type="text/javascript">

// ----------------------------------
// JS_REPORT
// ----------------------------------

function block_modal_choice(elem, id_report, id_block)
{
    const url = window.location.origin + '/report/block_modal_choice/' + id_report + '/' + id_block;
    modal_show(elem, url);
}

$(document).ready(function() {
    blocks_rank('#reportBlocks');
    blocks_sort('#reportBlocks');
});
function blocks_rank(elem)
{
    $('.block-row', elem).each(function(index) {
        $('[name^="blocks"]', this).each(function() {
            const extract = $(this).attr('name').split(']')[1];
            $(this).attr('name', 'blocks[' + index + ']' + extract + ']');
        });
        $('[name$="[rank]"]', this).val(index);
        $('.block-rank', this).text(eval(index+1) + '.');
    });
}
function blocks_sort(elem)
{
    $(elem).sortable({
        items: '.block-row',
        cursor: 'move',
        update: function(event, ui) {
            blocks_rank(elem);
        }
    }); 
}

function tag_modal_new()
{
    const url = window.location.origin + '/report/tag_modal_new';
    ajax_modal(url);   
}

function block_tag_modal_new(id_block=null)
{
    if(!id_block) {id_block = '';}
    const url = window.location.origin + '/report/block_tag_modal_new/' + id_block;
    ajax_modal(url);   
}

function block_tag_new(elem, id_block=null)
{
    const form_id = $(elem).attr('form');
    const form = $('#' + form_id)[0];
    let formdata = new FormData(form);
    if(!id_block) {id_block = '';}
    const url = window.location.origin + '/report/block_tag_new/' + id_block;
    ajax_html(url, formdata, function(result) {
        $('#modal').modal('hide');
        result = JSON.parse(result);
        const select = $('select[name="ids_tag[]"]');
        $(select).append('<option value="' + result.id_tag + '" selected> ' + result.label + ' </option>');
        $(select).bsMultiSelect('UpdateData');
    })
}

function tag_new(elem)
{
    const form_id = $(elem).attr('form');
    const form = $('#' + form_id)[0];
    let formdata = new FormData(form);
    const url = window.location.origin + '/report/tag_new';
    ajax_html(url, formdata, function(id_tag) {
        window.location.reload();
    })
}

function report_duplicate_modal(elem, id_report)
{
    const url = window.location.origin + '/report/duplicate/modal/' + id_report;
    ajax_modal(url);
}

function parent_thems_get(elem)
{
    const form = $(elem).closest('form');
    const id_parent = $(elem).val();
    const url = window.location.origin + '/report/parent/thems/get/' + id_parent;
    ajax_json(url, null, function(ids_them) {
        const select = $('select#ids_road_themSelect', form);
        for(let id_them of ids_them) {
            $('option[value="' + id_them + '"]', select).attr('selected', true);
        }
        $(select).bsMultiSelect('UpdateData');
    });   
}

function parent_blocks_get(elem)
{
    const id_parent = $(elem).val();
    const url = window.location.origin + '/report/parent/blocks/get/' + id_parent;
    ajax_html(url, null, function(view) {
        const div = $('#reportBlocksImport');
        $(div).html(view);
        $(div).fadeIn();
    });
}

function report_block_search_result(elem, id_report)
{
    const form_id = $(elem).attr('form');
    const form_search = $('#' + form_id)[0];
    const formdata = new FormData(form_search);
    const div = '#reportBlocks';
    const url = window.location.origin + '/report/block/search/result';
    
    const nb_blocks = $('.block-row', div).length;

    formdata.append('id_report', id_report);    
    formdata.append('nb_blocks', nb_blocks);    
    ajax_html(url, formdata, function(view) {
        $(div).append(view);
        $(div).addClass('mb-2');
        $(div).fadeIn();
        $('#modal').modal('hide');
    })
}

$(document).on('click', 'input[type="checkbox"]', '#blockResultsForm', function() {
    const form_id = 'blockResultsForm';
    if($('input[type="checkbox"]:checked', '#' + form_id).length>0) {
        $('button[form="' + form_id + '"]').removeClass('d-none');
    } else {
        $('button[form="' + form_id + '"]').addClass('d-none');
    }
});

function report_block_search(elem, id_report)
{
    const form_id = $(elem).attr('form');
    const formdata = new FormData($('#' + form_id)[0]);
    formdata.append('id_report', id_report);

    $('[name$="[id_block]"]', '#reportBlocks').each(function() {
        formdata.append('ids_block[]', $(this).val());
    });

    const url = window.location.origin + '/report/block/search';
    ajax_html(url, formdata, function(view) {
        const div = '#blockResults';
        $(div).html(view);
        $(div).fadeIn();
        tags_control();
        blocks_rank(div);
    });
}

function report_block_modal_search(elem, id_report=null)
{
    const url = window.location.origin + '/report/block/search/modal/' + id_report;
    modal_show(elem, url);
}

// $('#blockNewForm').ready(function() {
//     $.get(window.location.origin + '/report/block/new', function(view) {
//         $('#blockNewCollapse').html(view);
//         tags_control();
//     });
// });


function report_modal_block_remove(elem)
{
    const row = $(elem).closest('.row');
    $(row).fadeOut(function() { 
        $(row).remove();
        blocks_rank('#reportBlocks');
    });
}

function block_info_collapse(elem, id_report, id_block)
{
    const url = window.location.origin + '/report/block/info/collapse/' + id_report + '/' + id_block;
    $.get(url, function(view) {
        const target = $(elem).attr('data-bs-target');
        $(target).html(view);
    }); 
}

// function block_modal_info(elem, id_block)
// {
//     const url = window.location.origin + '/report/block_modal_info/' + id_block;
//     modal_show(elem, url); 
// }

function file_modal_info(elem)
{
    const id_file = $(elem).attr('id_file');
    const url = window.location.origin + '/report/file_modal_info/' + id_file;
    modal_show(elem, url); 
}

function block_modal_delete(elem, id_block)
{
    const url = window.location.origin + '/report/block_modal_delete/' + id_block;
    modal_show(elem, url); 
}

function report_delete_modal(elem, id_report)
{
    const url = window.location.origin + '/report/delete/modal/' + id_report;
    modal_show(elem, url); 
}

function tag_modal_delete(elem, id_tag)
{
    const url = window.location.origin + '/report/tag_modal_delete/' + id_tag;
    modal_show(elem, url); 
}

</script>