<script>
// -------------------------------
// JS_FEATURE
// -------------------------------

function road_delete_modal(elem, road_name, id_road)
{
    let url = window.location.origin + '/tesorus/road/delete/modal/' + road_name + '/' + id_road;
    $.get(url, function(result) {
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').text(result.header);
        $('.modal-body', '#modal').html(result.body);
        $('.modal-footer', '#modal').prepend(result.submit);
        $('#modal').modal('show');
    });
}

function road_delete(road_name, id_road)
{
    const url = window.location.origin + '/tesorus/road/delete/' + road_name + '/' + id_road;
    $.get(url, function(id_road_parent) {
        localStorage.setItem('id_road_active', id_road_parent);
        window.location.reload();
    });
}

function roadDesactivate(road)
{
    $(road).removeClass('road-active');

    // const id_road_parent = $(collapse_active).closest('.collapse').attr('id_road');
    // const road_collapse = $('[id_road="' + id_road + '"]').not('.road-edit-group');
    // console.log(road_collapse);
    $('.sort-container', road).removeAttr('sort-container');
    $('.road-edit-buttons', road).addClass('invisible');
    // $('.road-move, .road-edit, .road-new, .road-sub, .road-display, .road-text', road).attr('hidden', true);
    $('.road-edit-group', road).removeClass('ui-state-default bg-white text-dark border-bottom').addClass('border-0 bg-light');
    $('.road-new-group', road).attr('hidden', true);
    $('.collapse', road).each(function() {
        if($(this).sortable( "instance" )) $(this).sortable( "destroy" );
    });
    
    $('button[data-bs-toggle="collapse"]', road).addClass('d-none');
}

function roadActivate(id_road)
{
    localStorage.setItem('road_name', $('#roadEdit').attr('road_name'));
    localStorage.setItem('id_road_active', id_road);

    const road_to_desactivate = $('.road-active');
    roadDesactivate(road_to_desactivate);

    $('button#road' + id_road + 'Button').removeClass('d-none');

    const road = $('.collapse[id_road="' + id_road + '"], .collapsing[id_road="' + id_road + '"]');
    $(road).addClass('road-active');
    $('button[data-bs-toggle="collapse"]', road).removeClass('d-none');
    $(road).addClass('sort-container');
    $('.road-edit-buttons', road).removeClass('invisible');
    // $('.road-move, .road-new, .road-sub, .road-edit, .road-display, .road-text', road).attr('hidden', false);
    $('.road-edit-group', road).addClass('ui-state-default bg-white text-dark border-bottom').removeClass('border-0 bg-light');
    $('.road-new-group', road).attr('hidden', false);

    road_sort(road);       
}

$(document).ready(function() 
{
    const road_name = $('#roadEdit').attr('road_name');
    const id_road_active = localStorage.getItem('id_road_active');
    if(road_name==localStorage.getItem('road_name') && id_road_active && id_road_active!=0) {
        $.get('<?php echo base_url();?>/tesorus/' + road_name + '/road/' + id_road_active + '/get/ids_road', function(ids_road) {
            ids_road = JSON.parse(ids_road);
            for(let id of ids_road) {
                const target_id = $('.collapse[id_road="' + id + '"]').attr('id');
                const button = $('[data-bs-toggle="collapse"][data-bs-target="#' + target_id + '"]');
                $(button).click();
            }
            $('#roadEditWait').fadeOut(function() {
                $('#roadEdit').fadeIn();
            });
        });
        
    } else {
        localStorage.removeItem('id_road_active');
        roadActivate(0);
        $('#roadEditWait').fadeOut(function() {
            $('#roadEdit').fadeIn();
        });
    }
});

$('[data-bs-toggle="collapse"]', '#road0Collapse').on('click', function() 
{
    // collapse_caret_by_button(this);
    const target = $(this).attr('data-bs-target');
    const id_road = $(target).attr('id_road');
    if(!$(this).hasClass('collapsed')) {
        // console.log('open ' + id_road);
        roadActivate(id_road);
    } else {
        const id_road_parent = $(target).closest('.collapse').attr('id_road');
        // console.log('close ' + id_road + ' open ' + id_road_parent);
        roadActivate(id_road_parent);
    }
});

// function collapse_caret_by_button(button)
// {
//     if(
//         $(button).find('svg').attr('data-icon')=="caret-right" || 
//         $(button).find('i').hasClass('fa-caret-right')
//     ) {
//         $(button).html('<?php //echo fontawesome('caret-down');?>');
//     } else if(
//         $(button).find('svg').attr('data-icon')=="caret-down" || 
//         $(button).find('i').hasClass('fa-caret-down')
//     ) {
//         $(button).html('<?php //echo fontawesome('caret-right');?>');
//     }
// }

function road_checkbox_dropdown_behaviour(elem)
{
    // const target_id = $(elem).attr('data-bs-target');
    // console.log(target_id);
    // // dropdown element
    // $(target_id).on('show.bs.collapse', function() {
    //     if(!$(this).hasClass('show')) { $(elem).html('<?php //echo fontawesome('caret-down');?>'); }         
    // });
    // // dropdown up
    // $(target_id).on('hidden.bs.collapse', function() {
    //     if(!$(this).hasClass('show')) { $(elem).html('<?php //echo fontawesome('caret-right');?>'); }
    // });        

    const children = $('input[type=\"checkbox\"]:checked', target_id);
    console.log(children);
    $(children).each(function() { $(this).click(); });
}

function road_checkbox_input_behaviour(elem)
{
    const collapse = $(elem).closest('.collapse');
    const button = $('button[data-bs-target=\"#' + $(collapse).attr('id') + '\"]');

    if($('input:checked', collapse).length>0) {
        $(button).addClass('disabled').attr('disabled', true);
    } else {
        $(button).removeClass('disabled').attr('disabled', false);
    }
}

function cell_new_modal()
{
    $.get(window.location.origin + '/tesorus/cell/new/modal', function(result) {
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').text(result.header);
        $('.modal-body', '#modal').html(result.body);
        $('.modal-footer', '#modal').prepend(result.submit);
        $('#modal').modal('show');            
    });
}

function cell_update_modal(id_cell)
{
    $.get(window.location.origin + '/tesorus/cell/update/modal/' + id_cell, function(result) {
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').text(result.header);
        $('.modal-body', '#modal').html(result.body);
        $('.modal-footer', '#modal').prepend(result.submit);
        $('#modal').modal('show');            
    });
}

function road_update_modal(elem, road_name, id_road)
{
    waiting_start(elem);
    $.get(window.location.origin + '/tesorus/road/update/modal/' + road_name + '/' + id_road, function(result) {
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').text(result.header);
        $('.modal-body', '#modal').html(result.body);
        $('.modal-footer', '#modal').prepend(result.submit);
        $('#modal').modal('show'); 
        waiting_end(elem);           
    });
}

function road_update_active_modal(elem, road_name, id_road, isActive)
{
    waiting_start(elem);
    const url = window.location.origin + '/tesorus/path/by/road/get/' + road_name + '/' + id_road;
    $.get(url, function(path) {
        let title, body, footer;
        if(isActive==1) {
            title = "Masquer le chemin vers l'élément de liste";
            body = 'Vous êtes sur le point de masquer le chemin suivant : <br><br> <strong> ' + path + ' </strong> <br> <br> Veuillez confirmer votre action.';
            footer = '<a role="button" id_road="' + id_road + '" is_active="0" class="road-display btn btn-sm btn-dark" href="' + window.location.origin + '/tesorus/road/update/isActive/' + road_name + '/' + id_road + '/0" onclick="waiting_start(this);"> Masquer </button>';
        } else {
            title = "Afficher le chemin vers l'élément de liste";
            body = 'Vous êtes sur le point d\'afficher le chemin suivant : <br><br> <strong> ' + path + ' </strong> <br> <br> Veuillez confirmer votre action.';
            footer = '<a role="button" id_road="' + id_road + '" is_active="1" class="road-display btn btn-sm btn-info" href="' + window.location.origin + '/tesorus/road/update/isActive/' + road_name + '/' + id_road + '/1" onclick="waiting_start(this);"> Afficher </button>';
        }
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').text(title);
        $('.modal-body', '#modal').html(body);
        $('.modal-footer', '#modal').prepend(footer);
        $('#modal').modal('show');
        waiting_end(elem);
    });
}

function road_update_hasText_modal(road_name, id_road, has_text)
{
    const url = window.location.origin + '/tesorus/path/by/road/get/' + road_name + '/' + id_road;
    $.get(url, function(path) {
        let title, body, footer;
        if(has_text==1) {
            title = 'Supprimer le champ texte';
            body = 'Vous êtes sur le point de supprimer le champ texte pour le chemin suivant : <br><br> <strong> ' + path + ' </strong> <br> <br> Veuillez confirmer votre action.';
            footer = '<a role="button" class="btn btn-sm btn-dark" href="' + window.location.origin + '/tesorus/road/update/hastext/' + road_name + '/' + id_road + '"> Supprimer </button>';
        } else {
            title = 'Créer un champ texte';
            body = 'Vous êtes sur le point de créer un champ texte pour le chemin suivant : <br><br> <strong> ' + path + ' </strong> <br> <br> Veuillez confirmer votre action.';
            footer = '<a role="button" class="btn btn-sm btn-info" href="' + window.location.origin + '/tesorus/road/update/hastext/' + road_name + '/' + id_road + '"> Créer </button>';
        }
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').text(title);
        $('.modal-body', '#modal').html(body);
        $('.modal-footer', '#modal').prepend(footer);
        $('#modal').modal('show');
    });  
}

function road_sort(elem)
{
    const road_name = $('#roadEdit').attr('road_name');

    $(elem).sortable({
        items: ".road-edit-group",
        start: function(event, ui) {
            const parent = $(ui.item).find('.road-move').first().parent();
            if($(parent).find('.div-alert').length==0) {
                $(parent).append('<div class="div-alert d-inline"></div>');
            } else {
                $('.div-alert', parent).html('');
            }
        },
        update: function(event, ui) {
            const parent = $(ui.item).find('.road-move').first().parent();
            $('.div-alert', parent).first().html('<div class="btn btn-sm"><?php echo fontawesome('spinner');?></div>');

            let datas = [];
            $(this).children('.road-edit-group').each(function(index) {
                let data = {};
                data.id_road = $(this).attr('id_road');
                data.rank = index;
                datas.push(data);
                // $(this).attr('rank', index);
            });

            $.post(window.location.origin + '/tesorus/road/update/rank/' + road_name, { 'ranks' : datas }, function(result) {
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

                // $('#roadRankSave').attr('hidden', false);            }
    }); 
}

function road_view_modal(elem, road_name, type, size)
{
    waiting_start(elem);
    const title = $(elem).text();
    $.get(window.location.origin + '/tesorus/road/view/modal/' + road_name + '/' + type, function(view) {
        $('.modal-title','#modal').text(title);
        $('.modal-close','#modal').text('Fermer');
        $('.modal-dialog','#modal').addClass('modal-' + size);
        $('.modal-body', '#modal').css('min-height', '50vh').html(view);
        $('#modal').modal('show');
        waiting_end(elem);
    });
};

// $('#roadListCollapse').each(function() { 
//     caretDropdownControl(this); 
// });
// function caretDropdownControl(elem)
// {
//     // dropdown element
//     $('.collapse', elem).on('show.bs.collapse', function() {
//         if(!$(this).hasClass('show')) {
//             const target = $(this).attr('id');
//             const button = $('button[data-bs-target="#' + target + '"]');
//             $(button).html('<?php //echo fontawesome('caret-down');?>');   
//         }         
//     });
//     // dropdown up
//     $('.collapse', elem).on('hidden.bs.collapse', function() {
//         if(!$(this).hasClass('show')) {
//             const target = $(this).attr('id');
//             const button = $('button[data-bs-target="#' + target + '"]');
//             $(button).html('<?php //echo fontawesome('caret-right');?>');
//             $('.collapse', this).collapse('hide');
//         }
//     });        
// }

function plusDropdownControl(elem)
{
    // dropdown element
    $('.collapse', elem).on('show.bs.collapse', function() {
        if(!$(this).hasClass('show')) {
            const target = $(this).attr('id');
            const input = $('input[data-bs-target="#' + target + '"]');
            const label_fr = $('label[for="' + $(input).attr('id') + '"]');
            const new_label = $(label_fr).html().replace('<?php echo fontawesome('plus');?>', '<?php echo fontawesome('minus');?>');
            $(label_fr).html(new_label); 
        }         
    });
    // dropdown up
    $('.collapse', elem).on('hidden.bs.collapse', function() {
        if(!$(this).hasClass('show')) {
            const target = $(this).attr('id');
            const input = $('input[data-bs-target="#' + target + '"]');
            const label_fr = $('label[for="' + $(input).attr('id') + '"]');
            const new_label = $(label_fr).html().replace('<?php echo fontawesome('minus');?>', '<?php echo fontawesome('plus');?>');
            $(label_fr).html(new_label); 
        }
    });        
}

$('#modal').on('shown.bs.modal', function() {
    // $('#roadListCollapse').each(function() {
    //     caretDropdownControl(this); 
    // });
    $('form#roadCheckboxForm').each(function() {
        plusDropdownControl(this); 
    });
    // if($('form#thematicCheckboxForm').length) roadCollapseControl($('form#thematicCheckboxForm'), 'checkbox');
    // if($('form#thematicRadioForm').length) roadCollapseControl($('form#thematicRadioForm'), 'radio');
});

function road_new_modal(elem, road_name, id_road_parent, is_terminus=null)
{
    waiting_start(elem);
    let data = {
        'id_road_parent' : id_road_parent,
        'is_terminus' : is_terminus,
    };
    let url = window.location.origin + '/tesorus/road/new/modal/' + road_name;
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

</script>