<script type="text/javascript">

// -----------------------------------
// JS_ENQUETE
// -----------------------------------

// toggle column
$(document).ready(function() {
    $('#filterColumns').find('input[type="checkbox"]').each(function() {
        $(this).attr('checked', true);
    });
});
$('#filterColumns').find('input[type="checkbox"]').on('click', function () {
    const table = $('#answers-list').find('table');
    const column = $(this).attr('data-column');
    if($(this).is(':checked')) {
        $('td[data-column="' + column + '"], th[ban_order="' + column + '"]', table).removeClass('collapse');
    } else {
        $('td[data-column="' + column + '"], th[ban_order="' + column + '"]', table).addClass('collapse');
    }
});

function js_show_canvas_modal(elem, canvas_type)
{
    waiting_start(elem);
    const id_question = $(elem).attr('id_question');
    let url = window.location.origin + '/enquete/graph/modal/' + canvas_type + '/' + id_question;
    $.get(url, function(view) {
        $('.modal-dialog','#modal').addClass('modal-xl');
        $('.modal-title','#modal').html('Graphique de résultats');
        $('.modal-body','#modal').html(view);
        $('.modal-close','#modal').html('Fermer');             
        $('.modal-footer', '#modal').prepend('<button onclick="download_canvas_zoom(this, \'' + id_question + '\');" class="btn" title="Télécharger le graphique"> <?php echo fontawesome('download');?></a>');
        $('#modal').modal('show');
        set_canvas_zoom(canvas_type, id_question);
        waiting_end(elem);
    });
}

function set_canvas_zoom(canvas_type, id_question)
{
    let param;
    if(canvas_type=='chart') { param = charts_param[id_question]; }
    else if(canvas_type=='trend') { param = trends_param[id_question]; }
    param.ctx = document.getElementById('canvas-zoom').getContext('2d');
    param.event = 'zoom';
    if(canvas_type=='chart') { set_chart_by_type(id_question, param); }
    else if(canvas_type=='trend') { set_trend_bar(id_question, param); }
}

function generate_pdf_summary(canvas_type, images)
{
    let title, name, w, h;
    if(canvas_type=='chart') {
        title = 'Statistiques d\'enquêtes';
        name = 'statistiques_d_enquetes';
        w = 80;
        h = 40;
    } else if(canvas_type=='trend') {
        title = "Courbe de tendances";
        name = 'courbe_de_tendances';
        w = 160;
        h = 70;
    }

    let doc = new jspdf.jsPDF();
        
    doc.text(20, 20, title);
    doc.setFont("'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
    doc.setFontSize(10);
    const height = doc.internal.pageSize.height;
    get_session_filter_text(function(filter_text) 
    {
        console.log(filter_text);
        let p=1, x0 = 20, y0 = 20;
        let x = x0;
        let y = y0;
        let k = 'odd';
        for(let image of images) {
            if(k == 'odd') {
                if(y == y0) {
                    if(p == 1) { y = y + 10; }
                    doc.text(20, y, filter_text);
                    y = y + 10;
                }
                x = x0;
                doc.addImage(image, 'PNG', x, y, w, h);
                if(canvas_type=='chart') { x = x + w + 10; }
                else if(canvas_type=='trend') { y = y + h + 10}
                k = 'even';
            } 
            else if (k == 'even') {
                doc.addImage(image, 'PNG', x, y, w, h);
                y = y + h + 10;
                x = x0;
                k = 'odd';
            }
            if(y + h + 10 > height) {
                doc.addPage();
                p = p+1;
                y = y0;                
            }
        }
        doc.save( name + '.pdf');
    });
}

function js_download_canvas_summary(canvas_type)
{
    let ids_question = new Array();
    $('canvas:visible').each(function() {
        const id_question = $(this).attr('id_question');
        ids_question.push(id_question);
    });
    let images = new Array();
    for(let id_question of ids_question) {
        if(canvas[id_question]) {
            let canva = canvas[id_question];
            let image = canva.toBase64Image();
            images.push(image);
        }
    }
    generate_pdf_summary(canvas_type, images);
}
    
function download_canvas_zoom(elem, id_question)
{
    waiting_start(elem);
    var a = document.createElement('a');
    a.href = canvas_zoom.toBase64Image();
    a.download = 'graphe_' + id_question + '.png';
    a.click();
    $(window).blur(function() { waiting_end(elem); });
}

function get_session_filter_text(callback)
{
    $.get(window.location.origin + '/enquete/filter/get', function(filter) {
        let text = '';
        if(filter) {
            filter = JSON.parse(filter);
            if(Object.keys(filter).length > 0) {
                if(Object.keys(filter).length == 1 ) { text += 'FILTRE ACTIF : '; }
                else { text += 'FILTRES ACTIFS : \n'}
                for(let key in filter) {
                    if(Object.keys(filter).length > 1 ) { text += '   - '; }
                    text += key + ' => ' + filter[key] + ' \n';
                }
            } else {
                text += 'Aucun filtre';
            }
        } else {
            text += 'Aucun filtre';
        }
        callback(text);       
    });
}

function filter_modal(elem)
{
    waiting_start(elem);
    const target = $(elem).attr('filter-target');
    let url = window.location.origin + '/enquete/filter/modal/' + target;
    if($(elem).attr('reference')) url += '/' + $(elem).attr('reference');   

    $.get(url, function(data) {
        data = JSON.parse(data);
        $('.modal-dialog','#modal').addClass('modal-lg');
        $('.modal-title','#modal').html(data.title);
        $('.modal-body','#modal').html(data.body);
        $('.modal-footer','#modal').prepend(data.footer);
        $('#modal').modal('show');
        waiting_end(elem);        
    });
}

function filter_month_collapse(elem)
{
    if($(elem).val()) { 
        $('#monthCollapse').collapse('show'); 
    } else { 
        $('#monthCollapse').collapse('hide'); 
        $('[name="period[month]"]').val(''); 
    }
}
function period_collapse_toggle(elem)
{
    const target = $(elem).attr('data-target');
    const html = $(elem).html();
    $(target).collapse('toggle');
    $('select option:first', target).prop('selected', true);
    $(':input', target).val('');
    if($(elem).is(':contains("+")')) {
        $(elem).html(html.replace('+', '-'));
    } else {
        $(elem).html(html.replace('-', '+'));
    }
}
// write over event writen in fhdao
$(document).on('click', 'div.pagination a[data-ci-pagination-page]', function(e) {
    e.preventDefault();
    window.location.replace($(this).attr('href'));
});

$(document).on('hidden.bs.modal', '#myiframe', function() {
    $("iframe", this).attr("src","");    
});
// function js_modal_iframe_demande(id_demande)
// {
//     $('#loadingMessage').show();
//     $("#myiframe").modal();
//     const url = window.location.origin + "/fh/fhc_dao/page_view_sans_nav/" + id_demande + "/fhd_liste_demande";
//     $("iframe", "#myiframe").attr("src", url);
//     $('#loadingMessage').hide();
// };

// function js_modal_iframe_person(id_person)
// {
//     $('#loadingMessage').show();
//     $("#myiframe").modal();
//     const url = window.location.origin + "/fh/fhc_dao/page_view_sans_nav/" + id_person + "/fhd_liste_personne";
//     $("iframe", "#myiframe").attr("src", url); 
//     $('#loadingMessage').hide();
// };

function reset_filter(elem)
{
    const form = $(elem).closest('form');
    const action = $(form).attr('action');
    $(form).attr('action', action + '/1');
    $(form).submit();
}

function js_answer_details(elem, id_answer)
{
    waiting_start(elem);
    $.get(window.location.origin + '/enquete/answer/' + id_answer + '/modal', function(result){
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-xl');
        $('.modal-title','#modal').html(result.title);
        $('.modal-body','#modal').html(result.body);
        $('.modal-footer','#modal').find('button').text('Fermer');
        $('#modal').modal('show');
        waiting_end(elem);
    });
}

function enquete_modal(elem, id_enquete)
{
    waiting_start(elem);
    $.get(window.location.origin + '/enquete/enquete/' + id_enquete + '/modal', function(result){
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-xl');
        $('.modal-title','#modal').html(result.title);
        $('.modal-body','#modal').html(result.body);
        $('.modal-footer','#modal').prepend(result.submit);
        $('.modal-footer','#modal').find('button.modal-close').text('Fermer');
        $('#modal').modal('show');
        waiting_end(elem);                
    });
}

function question_modal(elem, id_question)
{
    waiting_start(elem);
    $.get(window.location.origin + '/enquete/question/' + id_question + '/modal', function(result) {
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-xl');
        $('.modal-title','#modal').html(result.title);
        $('.modal-body','#modal').html(result.body);
        $('.modal-footer','#modal').prepend(result.submit);
        $('#modal').modal('show');
        waiting_end(elem);               
    });    
}

function question_delete_modal(id_question, num_question, question_fr)
{
    $('.modal-title','#modal').html('Supprimer une question');
    $('.modal-body','#modal').html(''
        + '<p> Vous êtes sur le point de supprimer la question n°' + num_question + ' : </p>'
        + '<p class="text-center font-weight-bold">' + question_fr + '</p>'
        + '<p> Cette action est irréversible. Veuillez la confirmer. </p>'
    );
    $('.modal-footer','#modal').prepend('<button type="button" class="btn btn-sm btn-danger" onclick="question_delete(' + id_question + ');"> Supprimer </button>');
    $('#modal').modal('show');
}

function question_delete(id_question)
{
    $.get(window.location.origin + '/enquete/question/' + id_question + '/delete', () => {
        window.location.reload();
    });
}

function question_new_modal(elem)
{
    waiting_start(elem);
    $.get(window.location.origin + '/enquete/question/new/modal', function(result) {
        result = JSON.parse(result);
        $('.modal-dialog','#modal').addClass('modal-xl');
        $('.modal-title','#modal').html(result.title);
        $('.modal-body','#modal').html(result.body);
        $('.modal-footer','#modal').prepend(result.submit);
        $('#modal').modal('show');
        waiting_end(elem);         
    });    
}

$(document).on('show.bs.modal', '#modal', function () {
    $('.slider-group').each(function(){
        let namelang = $(this).attr('id');
        js_question_note_slider(namelang);
    });
});
function js_question_note_slider(namelang)
{
    const handle = $("#handle_" + namelang);
    const slider = $("#slider_" + namelang);
    const group = $(slider).closest('.slider-group');
    $( "#slider_" + namelang, group).slider({
        value: 5,
        min: 0,
        max: 10,
        step: 1,
        create: function( event, ui ) {
            $(handle, group).css({
                'width' : '3em',
                'height' : '1.6em',
                'top' : '50%',
                'margin-top' : '-.8em',
                'text-align' : 'center',
                'line-height' : '1.6em',
            });
            $(slider, group).css('max-width', '500px');
        },
        slide: function( event, ui ) {
            handle.text( ui.value );
            $("#input_" + namelang, group).val(ui.value).trigger("change");
        }
    });
}

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
    $.post("<?php echo base_url('enquete/filter/set');?>", {'filter' : graphs, 'type' : type });  
}

</script>

