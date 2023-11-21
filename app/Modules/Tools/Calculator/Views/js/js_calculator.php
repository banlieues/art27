<script>

if (typeof devis_work_submit_button != 'function') {
    function devis_work_submit_button(form_id)
    {
        if(
            $('input[name="label"][form="' + form_id + '"]').val().trim().length>0 && 
            $('input[type="checkbox"][name="ids_group[]"][form="' + form_id + '"]:checked').length>0
        ) {
            $('button[form="' + form_id + '"]').removeClass('disabled').attr('disabled', false);
        } else {
            $('button[form="' + form_id + '"]').addClass('disabled').attr('disabled', true);
        }
    }
}

if (typeof them_process != 'function') {
    function them_process()
    {
        $('[id_road_0]').not('[id_road_1]').each(function() {
            let nb_works_by_them_0 = 0;
            $('[id_road_1]', this).each(function() {
                const nb_works_by_them_1 = $(this).find('[id_work]').length;
                nb_works_by_them_0 += nb_works_by_them_1;
                if(nb_works_by_them_1==0) {
                    $(this).fadeOut();
                }
            });
            if(nb_works_by_them_0==0) {
                $(this).fadeOut();
            }
        });
    }
}

if (typeof work_delete != 'function') {
    function work_delete(elem, id_work)
    {
        $('[id_work="' + id_work + '"]').fadeOut(function() { 
            $(this).outerHtml('');
            them_process();
        });
    }
}

if (typeof work_edit != 'function') {
    function work_edit(elem)
    {
        const form = $('#' + $(elem).attr('form'))[0];
        const formdata = new FormData(form);
        const url = '<?php echo base_url();?>/calculator/devis/work';

        $.ajax({url: url, data: formdata, processData: false, contentType: false, type: 'POST', success: function(result) {
            result = JSON.parse(result);
            const i = $('[id_work]').length;

            // nav refresh
            if($('#DevisNavNew').is(':hidden')) $('#DevisNavNew').show();
            result.work.i = i;
            $.post("<?php echo base_url("calculator/devis/nav/new");?>", result.work, function(view) {
                view = view.replace(/Work\#\#(\d+)\#\#Anchor/g, 'WorkNN$1NNAnchor');
                view = view.replace(/Work\#\#(\d+)\#\#Group(\d+)Anchor/g, 'WorkNN$1NNGroup$2Anchor');
                $('#DevisNavNewWorksCollapse').append(view);
            });

            // board refresh
            if($('#DevisNavNew').is(':hidden')) $('#DevisNavNew').show();
            const road_1 = $('[id_road_1="' + result.work.id_them + '"]');
            const road_0 = $('[id_road_0="' + $(road_1).attr('id_road_0') + '"]');
            if($('[id_work="' + result.work.id_work + '"]').length>0) {
                $('[id_work="' + result.work.id_work + '"]').fadeOut(function() {
                    $('[id_work="' + result.work.id_work + '"]').outerHtml(result.html).fadeIn();
                });                
            } else {
                view = result.html.replaceAll('##i##', '##' + i + '##');
                view = view.replace(/Work\#\#(\d+)\#\#Anchor/g, 'WorkNN$1NNAnchor');
                view = view.replace(/Work\#\#(\d+)\#\#Group(\d+)Anchor/g, 'WorkNN$1NNGroup$2Anchor');
                $(road_1).append(view);
            }
            if($(road_0).css('display')=='none') $(road_0).not('[id_road_1]').fadeIn();
            if($(road_1).css('display')=='none') $(road_1).fadeIn();
        }});
    }
}

$(document).on('change', 'select[name-disabled="id_them"]', function() {
    groups_show_by_them(this, $(this).val());
});
$(document).on('click', ':radio[name="id_them"]', function() {
    groups_show_by_them($('select[name-disabled="' + $(this).attr('name') + '"]'), $(this).val());
});
function groups_show_by_them(select, id_them=null)
{
    $('button[form="' + $(select).attr('form') + '"]').addClass('disabled').attr('disabled', true);
    $('#DevisGroups').html('<label class="col-4 col-form-label pt-0"> Groupe de travaux </label><div class="col-8"><?php echo fontawesome('spinner');?></div>').show();
    if(!id_them) {
        $('#DevisGroups').hide();
    } else {
        const url = '<?php echo base_url();?>/calculator/devis/groups';
        const data = {
            form_id: $(select).attr('form'),
            id_them: id_them,
        };
        $.post(url, data, function(view) {
            $('#DevisGroups').html(view);
        });
    }
}

if (typeof work_edit_modal != 'function') {
    function work_edit_modal(elem, id_work=null)
    {
        waiting_start(elem);
        const url = window.location.origin + '/calculator/devis/work/modal';
        let formdata;
        if(id_work) {
            formdata = new FormData($('#DevisForm')[0]);
            formdata.append('id_work', id_work);          
        }
        $.ajax({url: url, data: formdata, processData: false, contentType: false, type: 'POST', success: function(result) {
            result = JSON.parse(result);
            $('.modal-dialog','#modal').addClass('modal-lg');
            $('.modal-title','#modal').text('Ajouter un nouvel ouvrage');
            $('.modal-body', '#modal').html(result.body);
            $('.modal-footer', '#modal').prepend(result.footer);
            $('#modal').modal('show');
            waiting_end(elem);
        }});
    }
}

if (typeof devis_group_remove != 'function') {
    function devis_group_remove(id_group)
    {
        console.log($('input[type="checkbox"][name="ids_group[]"][value="' + id_group + '"]', '#GroupTagContainer'));
        $('input[type="checkbox"][value="' + id_group + '"]', '#GroupTagContainer').click();
    }
}

$(document).on('click', '#GroupTagContainer input[type="checkbox"]', function() {
    devis_group_select();
});
if (typeof devis_group_select != 'function') {
    function devis_group_select()
    {
        $('input[type="checkbox"][name="ids_group[]"]', '#GroupTagContainer').each(function() {
            const id_group = $(this).val();
            const div = $('[id_road_0][id_road_1][id_group="' + id_group + '"]');
            const id_road_0 = $(div).attr('id_road_0');
            const road_0 = $('[id_road_0="' + id_road_0 + '"]');
            const id_road_1 = $(div).attr('id_road_1');
            const road_1 = $('[id_road_1="' + id_road_1 + '"]');
            if($(this).is(':checked')) {
                if($(road_0).is(':hidden')) { $(road_0).not('[id_road_1]').not('[id_group]').fadeIn(); }
                if($(road_1).is(':hidden')) { $(road_1).not('[id_group]').fadeIn(); }
                if($(div).is(':hidden')) {
                    const plusminus_add = $(div).find('.plusminus-add');
                    $(plusminus_add).click();
                    $(div).fadeIn();
                }
            } else {
                if($(div).is(':visible')) {
                    const plusminus_remove = $(div).find('.plusminus-row').find('.plusminus-remove');
                    $(div).fadeOut(function() {
                        $(plusminus_remove).click();
                        if($(road_1).is(':visible') && $('[id_group]:visible', road_1).length==0) { $(road_1).not('[id_group]').fadeOut(); }
                        if($(road_0).is(':visible') && $('[id_group]:visible', road_0).length==0) { $(road_0).not('[id_road_1]').not('[id_group]').fadeOut(); }
                    });
                }
            }
        });
    }
}

if (typeof devis_group_remove != 'function') {
    function devis_group_remove(target, id_group)
    {
        // const select = $('select', target)[0];
        // $('option[value="' + id_group + '"]', select).attr('selected', false);
        // $(select).bsMultiSelect("Update");

        // const collapse = $('.collapse', target);
        // $('input[type="checkbox"][value="' + id_group + '"]', collapse).prop('checked', false);

        const div = '#DevisGroup' + id_group + 'Anchor';
        $(div).fadeOut(function() { $(this).find('.plusminus-row').outerHtml(''); });
    }
}

if (typeof group_update_active_modal != 'function') {
    function group_update_active_modal(elem, id_group, isActive)
    {
        waiting_start(elem);
        const url = window.location.origin + '/calculator/group/' + id_group + '/get/path';
        $.get(url, function(path) {
            let title, body, footer;
            if(isActive==1) {
                title = "Masquer le chemin vers l'élément de liste";
                body = 'Vous êtes sur le point de masquer le chemin suivant : <br><br> <strong> ' + path + ' </strong> <br> <br> Veuillez confirmer votre action.';
                footer = '<a role="button" class="road-display btn btn-sm btn-dark" href="' + window.location.origin + '/calculator/group/' + id_group + '/update/active/0" onclick="waiting_start(this);"> Masquer </button>';
            } else {
                title = "Afficher le chemin vers l'élément de liste";
                body = 'Vous êtes sur le point d\'afficher le chemin suivant : <br><br> <strong> ' + path + ' </strong> <br> <br> Veuillez confirmer votre action.';
                footer = '<a role="button" class="road-display btn btn-sm btn-info" href="' + window.location.origin + '/calculator/group/' + id_group + '/update/active/1" onclick="waiting_start(this);"> Afficher </button>';
            }
            $('.modal-dialog','#modal').addClass('modal-lg');
            $('.modal-title','#modal').text(title);
            $('.modal-body', '#modal').html(body);
            $('.modal-footer', '#modal').prepend(footer);
            $('#modal').modal('show');
            waiting_end(elem);
        })
    }
}

$(document).ready(function() {
    $('.group-sortable').sortable({
        items: ".group-sortable-row",
        start: function(event, ui) {
            const parent = $(ui.item).find('.road-move').first().parent();
            if($(parent).find('.div-alert').length==0) {
                $(parent).append('<div class="div-alert d-inline"></div>');
            } else {
                $('.div-alert', parent).first().html('');
            }
        },
        update: function(event, ui) {
            const parent = $(ui.item).find('.road-move').first().parent();
            $('.div-alert', parent).first().html('<div class="btn btn-sm"><?php echo fontawesome('spinner');?></div>');

            let datas = [];
            $(this).children('.group-sortable-row').each(function(index) {
                let data = {};
                data.id_group = $(this).attr('id_group');
                data.rank = index;
                datas.push(data);
                // $(this).attr('rank', index);
            });

            $.post(window.location.origin + '/calculator/group/update/rank', { 'ranks' : datas }, function(result) {
                let alert;
                if(result.trim()=='success') {
                    alert = '<div class="btn btn-sm btn-link link-success" title="Ordre de la liste modifiée"><?php echo fontawesome('check');?></div>';
                    // alert = '<small class="text-success bg-success bg-opacity-10 border border-success border-opacity-10 rounded p-1"> Ordre du groupe modifié </small>';
                } else if(result.trim()=='error') {
                    alert = '<div class="btn btn-sm btn-link link-danger" title="Erreur dans le tri de la liste"><?php echo fontawesome('triangle-exclamation');?></div>';
                    // alert = '<small class="text-danger bg-danger bg-opacity-10 border border-danger border-opacity-10 rounded p-1"> Erreur... </small>';
                }
                
                $('.div-alert', parent).first().html(alert);
            });
        }
    }); 
});

function road_list_collapse(elem, container)
{
    const status = $(elem).attr('status');
    if(!status || status=='collapse') {
        $('.collapse', '#' + container).collapse('show');
        $(elem).html('<?php echo fontawesome('down-left-and-up-right-to-center');?> Compresser la liste');
        $(elem).attr('status', 'show');
    } else {
        $('.collapse', '#' + container).collapse('hide');
        $(elem).html('<?php echo fontawesome('up-right-and-down-left-from-center');?> Etirer la liste');
        $(elem).attr('status', 'collapse');
    }
    
}

function display_control_notes(elem, i)
{
    if($(elem).is(':checked')) {
        $('textarea[name=\"pricenew[' + i + '][comment]\"]').fadeIn();
    } else {
        $('textarea[name=\"pricenew[' + i + '][comment]\"]').fadeOut();
    }
}

// $(".road-active").on( "sortstop", function( event, ui ) {
//     const id_road = $(this).attr('id_road');
//     $.get("<?php //echo base_url('calculator/estimation/update/rank');?>/" + id_road);
// });

// $(document).on('sortstop', '.road-active', function( event, ui) {
//     const elem = $('.road-move', ui.item[0]);
//     const road = $(elem).closest('.collapse[id_road]');
//     const id_road_parent = $(road).closest('.collapse[id_road]').attr('id_road') || 0;
//     $.post("<?php //echo base_url('tesorus/road/update/rank/calculator');?>/" + id_road_parent);
// });

$(document).on('click', '.road-display', function(e) {
    e.preventDefault();
    const elem = e.target;
    waiting_start(elem);
    const id_road = $(elem).attr('id_road');
    const is_active = $(elem).attr('is_active');
    $.get("<?php echo base_url('calculator/estimation/update/isactive');?>/" + id_road + '/' + is_active, function() {
        window.location = $(elem).attr('href');
    });
});

function group_tag_view_modal(elem)
{
    waiting_start(elem);
    const title = $(elem).text();
    $.get("<?php echo base_url('calculator/group/tag/modal');?>", function(view) {
        $('.modal-title','#modal').text(title);
        $('.modal-close','#modal').text('Fermer');
        $('.modal-dialog','#modal').addClass('modal-xl');
        $('.modal-body', '#modal').css('min-height', '50vh').html(view);
        $('#modal').modal('show');
        waiting_end(elem);
    });
};

function js_activate_add_roads_to_group(elem)
{
    const submit = $('[type="submit"][form="NewRoadsForm"]');
    if($('input[form="NewRoadsForm"]:checked').length>0) 
        $(submit).attr('disabled', false).removeClass('disabled'); 
    else $(submit).attr('disabled', true).addClass('disabled');
}

function js_activate_unlink_roads_to_group(elem)
{
    const submit = $('[type="submit"][form="UnlinkRoadsForm"]');
    if($('input[form="UnlinkRoadsForm"]:checked').length>0) 
        $(submit).attr('disabled', false).removeClass('disabled'); 
    else $(submit).attr('disabled', true).addClass('disabled');
}

function group_new_modal(elem, id_road_parent)
{
    waiting_start(elem);
    let data = {
        'id_road_parent' : id_road_parent,
    };
    let url = window.location.origin + '/calculator/group/new/modal';
    $.post(url, data, function(result) {
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').text(result.header);
        $('.modal-body', '#modal').html(result.body);
        $('.modal-footer', '#modal').prepend(result.submit);
        $('#modal').modal('show');
        waiting_end(elem);
    });
}

$(document).ready(function() {

    let height = window.innerHeight;
    if($('header').length) height -= $('header').outerHeight();

    $('#DevisNav').css('max-height', height);
});

</script>