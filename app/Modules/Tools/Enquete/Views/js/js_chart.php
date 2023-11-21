<!-- -----------------------------------------------------
JS_CHART
----------------------------------------------------- -->

<!-- Chart JS -->
<script src="<?php echo base_url("node_modules/chart.js/dist/chart.umd.js"); ?>"></script>
<script src="<?php echo base_url("node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js"); ?>"></script>
<script src="<?php echo base_url("node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js");?>"></script>

<script type="text/javascript">

let canvas_zoom;
let charts_param = [];

$(document).ready(function() {
    $('.canvas').each(function() {
        const canvas = $(this);
        const container = $(this).closest('.card-body');
        const id_question = $(this).attr('id_question');
        const button = $(container).find('button');
        waiting_start(button);
        $.get(window.location.origin + '/enquete/chart/' + id_question + '/param/', function(result) {
            result = JSON.parse(result);
            charts_param[id_question] = result;
            result.ctx = document.getElementById('chart-' + id_question).getContext('2d');
            result.event = 'load';
            set_chart_by_type(id_question, result);
            waiting_end(button);
            $(canvas).fadeIn();
        });
    });
});

function set_chart_zoom_order(id_question)
{
    set_chart_order_param(charts_param[id_question], function(result) {
        result.ctx = document.getElementById('canvas-zoom').getContext('2d');
        result.event = 'zoom';
        set_chart_by_type(id_question, result);
    });
}

function set_chart_order_param(param, callback)
{
    let result = {...param };
    let labels = result.labels;
    let datas = result.datas;
    let colors = result.colors;
    result.labels = [];
    result.datas = [];
    result.colors = [];
    array = labels.map(function(d, i) {
        return {
            label: d,
            data: datas[i] || 0,
            color: colors[i] || 0,
        };
    });

    arraySorted = array.sort(function(a, b) {
        return b.data - a.data;
    });

    arraySorted.forEach(function(d){
        result.labels.push(d.label);
        result.datas.push(d.data);
        result.colors.push(d.color);
    });

    callback(result);
}

function set_chart_by_type(id_question, result)
{
    if(result.labels && result.datas && result.colors) {
        const datas0 = result.datas.filter((v) => (v == 0 || v == null)).length;
        if(datas0 < result.datas.length) {
            if(result.chartType == 'bar') { set_chart_bar(id_question, result); }
            else if(result.chartType == 'pie') { set_chart_pie(id_question, result); }
        }
        else {
            const html = '<div class="card card-body mb-3 h-100"> <small>Pas de données pour <br> <strong> ' + result.title + ' </strong> </small></div>'
            $('#chart-container-' + id_question).html(html);
        }
    }
    else {
        const html = '<div class="card card-body mb-3 h-100"> <small>Pas de données pour <br> <strong> ' + result.title + ' </strong> </small></div>'
        $('#chart-container-' + id_question).html(html);
    }
}

function set_average_bar(labels, datas, callback)
{
    let total = 0
    let j = 0;
    for(let i = 0; i < datas.length; i++) 
    {
        total += datas[i];
        if(datas[i]!=0) j++;
    }
    let average = total/j;
    if(!Number.isInteger(average)) average = average.toFixed(2);

    callback(average);
}

function set_average_notes(labels, datas, callback)
{
    let total = 0;
    let j = 0;
    for(let i = 0; i < datas.length; i++)
    {
        total += labels[i]*datas[i];
        j += datas[i];
    }
    const average = parseFloat((total/j).toFixed(2));

    callback(average);
}

function add_average_line(id_question)
{
    let result = charts_param[id_question];
    let notes = true;
    for(label of result.labels) {
        if(isNaN(label) || !Number.isInteger(parseFloat(label))) notes = false;
    }
    
    if(notes==false)
    {
        set_average_bar(result.labels, result.datas, function(average) {
            annotation = {
                line1: {
                    type: 'line',
                    yMin: average,
                    yMax: average,
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2,
                    label: {
                        content: (ctx) => 'Moyenne : ' + average,
                        enabled: true
                    },
                },
            }; 
            canvas_zoom.options.plugins.annotation.annotations = annotation;
            canvas_zoom.update();
        });
    } else {
        set_average_notes(result.labels, result.datas, function(average) {
            annotation = {
                line1: {
                    type: 'line',
                    xMin: average,
                    xMax: average,
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2,
                    label: {
                        content: (ctx) => 'Moyenne : ' + average,
                        enabled: true
                    },
                },
            }; 
            canvas_zoom.options.plugins.annotation.annotations = annotation;
            canvas_zoom.update();
        });        
    }
}

function set_chart_bar(id_question, result)
{
    if(canvas_zoom) { canvas_zoom.destroy(); }

    let annotation = {};

    const myChart = new Chart(result.ctx, {
        type: 'bar',
        responsive: true,
        maintainAspectRatio: false,
        data: {
            labels: result.labels,
            datasets: [{
                data: result.datas,
                backgroundColor: result.colors,
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: result.title,
                },
                legend: {
                    display: false,
                },
            },
            animation: {
                onComplete: function() {
                    if(result.event=='zoom') { canvas_zoom = this; }
                    else if(result.event=='load') { canvas[id_question] = this; }
                }
            },
            onClick: (e) => {
                const points = myChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

                if (points.length) {
                    const firstPoint = points[0];
                    let data = {
                        id_question: id_question,
                        id_answer: result.ids_label[firstPoint.index],
                    };
                    $.post(window.location.origin + '/enquete/filter/set_where_chart', data, function() {
                        window.location.reload();
                    });
                }
            },
        },
    });
}

function set_chart_pie(id_question, result)
{
    const myChart = new Chart(result.ctx, {
        type: 'pie',
        responsive:true,
        maintainAspectRatio: false,
        data: {
            labels: result.labels,
            datasets: [{
                data: result.datas,
                backgroundColor: result.colors,
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: result.title,
                },
            },
            animation: {
                onComplete: function() {
                    if(result.event=='zoom') { canvas_zoom = this; }
                    else if(result.event=='load') { canvas[id_question] = this; }
                }
            },
            onClick: (e) => {
                const points = myChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

                if (points.length) {
                    const firstPoint = points[0];
                    let data = {
                        id_question: id_question,
                        id_answer: result.ids_label[firstPoint.index],
                    };
                    $.post(window.location.origin + '/enquete/filter/set_where_chart', data, function() {
                        window.location.reload();
                    });
                }
            },
        },
    });
    // if(result.event=='load') { canvas[id_question] = myChart; }
}

</script>

