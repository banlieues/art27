<script type="text/javascript">

function deposit_delete_modal(elem, id_deposit)
{
    const url = window.location.origin + '/demande/web/deposit/delete/modal/' + id_deposit;
    modal_show(elem, url);
}

function deposit_info_modal(elem, id_deposit)
{
    const url = window.location.origin + '/demande/web/deposit/info/modal/' + id_deposit;
    modal_show(elem, url, null, 'Je recherche des éventuels doublons.');
}

function set_worker(type, id_deposit)
{
    $.get(window.location.origin + '/demande/web/set/worker/' + type + '/' + id_deposit);

    // $.get(window.location.origin + '/demande/web/set/worker/off/' + id_deposit, function(is_set_off) {
    //     const table = $('#deposits-list');
    //     if(is_set_off==true) {
    //         $('[user_on_work][id_deposit="' + id_deposit + '"]', table).remove();
    //     }
    // });
}

function select_dublon_demande(elem)
{
    const group = $(elem).attr('group');
    const button = $('button[form="depositForm"]');
    const input_new = $('input[name="id_building"]').not('[value]');
    if(group==0) {
        $(button).attr('title', "Créer une nouvelle demande").html('<?php echo fontawesome('plus');?> Nouvelle demande');
        if($(input_new).is(':checked') && $('.building_existing').is(':hidden')) {
            $('.building_new_brugis').hide();
            $('.building_existing').show();
        }
    }
    else {
        $(button).attr('title', "Ajouter le message à la demande existante").html('<?php echo fontawesome('plus');?> Message à la demande existante');
        
        if($(input_new).is(':checked') && $('.building_new_brugis').is(':hidden')) {
            $('.building_new_brugis').show();
            $('.building_existing').hide();
            $(input_new).parent().append('<a id="anchor_building" href="#depositBuildingTable" style="display: none;"> Click </a>');
            $('#anchor_building').click().outerHtml('');
        }
        // $.get(window.location.origin + '/demande/web/profil/building/by/demande/get/' + $(elem).val(), function(demande) {
        //     demande = JSON.parse(demande);
        //     for(let id_contact of demande.ids_contact) {
        //         const input = $('input[name="id_contact"][value="' + id_contact + '"]');
        //         if(input.length>0) $(input).prop('checked', true);
        //     }
        //     for(let id_contact_profil of demande.ids_contact_profil) {
        //         const input = $('input[name="id_contact_profil"][value="' + id_contact_profil + '"]');
        //         if(input.length>0) $(input).prop('checked', true);
        //     }
        //     if(demande.ids_building) {
        //         for(let id_building of demande.ids_building) {
        //             const input = $('input[name="id_building"][value="' + id_building + '"]');
        //             if(input.length>0) $(input).prop('checked', true);
        //         }
        //     }
        // });
    }
}

function select_dublon_building(elem)
{
    let id_contact = parseInt($('input[name="id_contact"]:checked').val());
    if(!id_contact) id_contact = 0;
    let id_contact_profil = parseInt($('input[name="id_contact_profil"]:checked').val());
    if(!id_contact_profil) id_contact_profil = 0;

    let checked = {};
    for(let ref of ['contact_name', 'contact_lastname', 'contact_email', 'contact_email2', 'contact_phone', 'contact_phone2']) {
        checked[ref] = {
            group : $('input[name="' + ref + '"]:checked').attr('group'),
            id_contact : $('input[name="' + ref + '"]:checked').attr('id_contact'),
            id_contact_profil : $('input[name="' + ref + '"]:checked').attr('id_contact_profil'),
        };
    }

    const id_deposit = $('input[type="hidden"][name="id_deposit"]').val();

    let id_building = parseInt($('input[name="id_building"]:checked').val());
    if(!id_building) id_building = 0;

    if(id_building>0) {
        $('#depositPerson').find('tbody').fadeTo('slow', 0);
        $.get(window.location.origin + '/demande/web/dublons/profil/get/' + id_deposit + '/' + id_building, function(view) {
            $('#depositPerson').html(view);
            $('#depositPerson').find('tbody').fadeTo('slow', 1);
            
            if(id_contact>0) {
                $('input[name="id_contact"][value=' + id_contact + ']').prop('checked', true);
                select_dublon_profil_display('id_contact', $('input[name="id_contact"][value=' + id_contact + ']'));
                for(let ref in checked) {
                    if(checked[ref].group==0) 
                        $('input[name="' + ref + '"][group=0]').prop('checked', true);
                    else if($('input[name="' + ref + '"][id_contact="' + checked[ref].id_contact + '"]').length) 
                        $('input[name="' + ref + '"][id_contact="' + checked[ref].id_contact + '"]').prop('checked', true);
                    else $('input[name="' + ref + '"][id_contact=' + id_contact + ']').prop('checked', true);
                }
            } else if(id_contact_profil>0) {
                $('input[name="id_contact_profil"][value=' + id_contact_profil + ']').prop('checked', true);
                select_dublon_profil_display('id_contact_profil', $('input[name="id_contact_profil"][value=' + id_contact_profil + ']'));
                for(let ref in checked) {
                    if(checked[ref].group==0) 
                        $('input[name="' + ref + '"][group=0]').prop('checked', true);
                    else if($('input[name="' + ref + '"][id_contact_profil="' + checked[ref].id_contact_profil + '"]').length) 
                        $('input[name="' + ref + '"][id_contact_profil="' + checked[ref].id_contact_profil + '"]').prop('checked', true);
                    else $('input[name="' + ref + '"][id_contact_profil=' + id_contact_profil + ']').prop('checked', true);
                }
            }
        });
    } else { id_building = 0; }

    dublon_demande_get(id_deposit, id_contact, id_contact_profil, id_building);
}

function dublon_demande_get(id_deposit, id_contact=0, id_contact_profil=0, id_building=0)
{
    $('#depositDemande').find('tbody').fadeTo('slow', 0);
    const id_demande = $('input[name="id_demande"]:checked').val();
    const url = window.location.origin + '/demande/web/dublons/demande/get/' + id_deposit + '/' + id_contact + '/' + id_contact_profil + '/' + id_building;
    $.get(url, function(view) {
        $('#depositDemande').html(view);
        $('#depositDemande').find('tbody').fadeTo('slow', 1);
        const button = $('button[form="depositForm"]');
        if(id_demande && id_demande>0) $('input[name="id_demande"][value=' + id_demande + ']').prop('checked', true);
        if($('input[name="id_demande"]:checked').val()>0) {
            $(button).html('<?php echo fontawesome('plus');?> Message à la demande existante');
        } else {
            $(button).html('<?php echo fontawesome('plus');?> Nouvelle demande');
        }
    });
}

function select_dublon_profil_display(name_type, elem)
{
    const table = $(elem).closest('table');
    const group = $(elem).attr('group');

    $('tbody input[type="radio"]', table).not('[name="' + name_type + '"]').addClass('d-none').prop('checked', false).prop('disabled', true);
    // $('tbody input[group=0][name="contact_email2"]', table).val('');
    // $('tbody input[group=0][name="contact_phone2"]', table).val('');
    // $('tbody input[group=0][name="contact_email2"]', table).siblings('label').text('');
    // $('tbody input[group=0][name="contact_phone2"]', table).val('').siblings('label').text('');

    if(group>0) {
        // $('tbody input[group=0]', table).not('[name="' + name_type + '"]').removeClass('d-none');
        $('tbody input[group=' + group + ']', table).not('[name="' + name_type + '"]').prop('disabled', false);
        // $('tbody input[group=0]', table).not('[name="' + name_type + '"]').removeClass('d-none');
        $('tbody input[group=' + group + ']', table).not('[name="' + name_type + '"]').each(function() {
            const input_new = $('tbody input[group=0][name="' + $(this).attr('name') + '"]', table);
            let value_new = '';
            let value_dublon = '';
            if($(input_new).val()) value_new = $(input_new).val().trim();
            if($(this).val()) value_dublon = $(this).val().trim();
            if(value_new == value_dublon || (value_new.length==0 && value_dublon.length==0) || (value_new.length==0)) {
                $(this).addClass('d-none').prop('checked', true).prop('disabled', false);
            } else {
                $(input_new).removeClass('d-none').prop('disabled', false);
                $(this).removeClass('d-none').prop('checked', true).prop('disabled', false);
            }
        });
    } else {
        $('tbody input[group=0]', table).not('[name="' + name_type + '"]').addClass('d-none').prop('checked', true).prop('disabled', false);
    }
}

function select_dublon_profil(elem, name_type)
{
    select_dublon_profil_display(name_type, elem);

    const id_deposit = $('input[type="hidden"][name="id_deposit"]').val();

    let id_contact = parseInt($('input[name="id_contact"]:checked').val());
    if(!id_contact) id_contact = 0;
    let id_contact_profil = parseInt($('input[name="id_contact_profil"]:checked').val());
    if(!id_contact_profil) id_contact_profil = 0;
    let id_building = parseInt($('input[name="id_building"]:checked').val());
    if(!id_building) id_building = 0;

    // let id_building = $('input[name="id_building"]:checked').val();
    // if(id_building=="on") id_building = 0;

    $('#depositBuilding').find('tbody').fadeTo('slow', 0);
    $.get(window.location.origin + '/demande/web/dublons/building/get/' + id_deposit + '/' + id_contact + '/' + id_contact_profil, function(view) {
        $('#depositBuilding').html(view);
        $('#depositBuilding').find('tbody').fadeTo('slow', 1);
        $('input[name="id_building"][value=' + id_building + ']').prop('checked', true);
    });
    
    dublon_demande_get(id_deposit, id_contact, id_contact_profil, id_building);
}

</script>