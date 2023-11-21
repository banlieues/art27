<script type="text/javascript">


function set_worker(type, id_deposit)
{
    $.get(window.location.origin + '/company/set/worker/' + type + '/' + id_deposit);

    // $.get(window.location.origin + '/company/set/worker/off/' + id_deposit, function(is_set_off) {
    //     const table = $('#deposits-list');
    //     if(is_set_off==true) {
    //         $('[user_on_work][id_deposit="' + id_deposit + '"]', table).remove();
    //     }
    // });
}

function company_delete_modal(elem, id_company)
{
    const url = window.location.origin + '/company/company/delete/modal/' + id_company;
    modal_show(elem, url);
}

function deposit_to_company_update_modal(elem, id_deposit)
{
    const form = $('#depositDublonForm')[0];
    let formdata = new FormData(form);
    formdata.append('id_deposit', id_deposit);

    const url = window.location.origin + '/company/deposit/to/company/update/modal';
    modal_show(elem, url, formdata);
}

function deposit_delete_modal(elem, id_deposit)
{
    const url = window.location.origin + '/company/deposit/delete/modal/' + id_deposit;
    modal_show(elem, url);
}

function deposit_info_modal(elem, id_deposit)
{
    const url = window.location.origin + '/company/deposit/info/modal/' + id_deposit;
    modal_show(elem, url, null, 'Je recherche des éventuels doublons.');
}

function deposit_to_company_modal(elem, id_deposit)
{
    const url = window.location.origin + '/company/deposit/to/company/modal/' + id_deposit;
    modal_show(elem, url);
}

function select_dublon_submit_button(elem)
{
    $('[button-type="update-modal"]').addClass('d-none');
    $('[button-type="create-modal"]').addClass('d-none');
    const form = $(elem).closest('form');
    const id_company = $('[header]:checked', form).val();
    if(id_company!='on') {
        $(form).attr('method', window.location.origin + '/company/deposit/to/company/update/' + id_company);
        $('[button-type="update-modal"]').removeClass('d-none');
    } else {
        $(form).attr('method', window.location.origin + '/company/deposit/to/company');
        $('[button-type="create-modal"]').removeClass('d-none');
    }
}

function select_dublon_bis(elem)
{
    $('input[type="radio"]', '#modal').not('[header]').addClass('invisible');
    const id_company = $(elem).val();
    console.log(id_company);
    const id_deposit = $('input[name="id_deposit"]').val();
    if(id_company) {
        $('input[id_company="' + id_company + '"]').not('[header]').each(function() {
            console.log(this);
            const name = $(this).attr('name');
            if($(this).val() != $('input[id_deposit][name="' + name + '"]').val()) {
                $(this).removeClass('invisible');
                $('input[id_deposit][name="' + name + '"]').removeClass('invisible');

                if($(this).val().length>0) $(this).prop('checked', true);
                else $('input[id_deposit][name="' + name + '"]').prop('checked', true);
            }
        });
    }

    select_dublon_submit_button(elem);
}

</script>