<!-- -----------------------------------------------------
JS_TREND
----------------------------------------------------- -->

<!-- Chart JS -->
<script src="<?php echo base_url("node_modules/chart.js/dist/chart.umd.js"); ?>"></script>
<script src="<?php echo base_url("node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js"); ?>"></script>
<script src="<?php echo base_url("node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js");?>"></script>

<script type="text/javascript">

let canvas_zoom;
let charts_param = [];
let trends_param = [];

function set_timerange(elem)
{
    $('canvas').fadeOut();
    $('.trend-container').find('.waiting').fadeIn();
    for(let reference in canvas) canvas[reference].destroy();
    const timerange = $(elem).val();
    set_trend(timerange);
}

$(document).ready(function() {
    set_trend();
});

function set_trend(timerange=null)
{
    $('.canvas').each(function() {
        const canvas = $(this);
        const container = $(this).closest('.trend-container');
        const reference = $(this).attr('reference');
        let url = window.location.origin + '/enquete/trend/param/get/' + reference;

        if(timerange) {url += '/' + timerange; }
        $.get(url, function(result) {
            $(".alert_patience").hide();
            result = JSON.parse(result);
            $('[name="timerange"]').find('option[value="' + result.timerange + '"]').attr('selected', true);
            trends_param[reference] = result;
            result.ctx = document.getElementById('trend-' + reference).getContext('2d');
            result.event = 'load';
            set_trend_bar(reference, result);
            $('.waiting', container).fadeOut();
            $(canvas).fadeIn();
        });
    });
}

let myTrend;

function set_trend_bar(reference, result)
{
    let scales;
    if(reference=='relation') {
        scales = {  y: { beginAtZero: true, max: 100, }};
    } else if($.inArray(reference, ['number', 'origin'])==-1) {
        scales = {  y: { beginAtZero: true, max: 10, }};
    }
    let config;

    if($.inArray(reference, ['number', 'origin'])>-1) { config = config_stacked(result); }
    else { config = config_scores(result, scales); }

    let annotation = {};

    myTrend = new Chart(result.ctx, config);

    if(result.event=='load') { canvas[reference] = myTrend; }
}

function config_scores(result, scales)
{
    if(canvas_zoom) { canvas_zoom.destroy(); }

    const config = {
        type: 'bar',
        responsive: true,
        maintainAspectRatio: false,
        data: result.data,
        options: {
            scales: scales,
            plugins: {
                title: {
                    display: true,
                    text: result.title,
                },
                // legend: {
                //     display: false,
                // },
            },
            animation: {
                onComplete: function() {
                    if(result.event=='zoom') { canvas_zoom = myTrend; }
                }
            },
        },
    };

    return config;
}

function config_stacked(result)
{
    let config = {
        type: 'bar',
        maintainAspectRatio: false,
        data: result.data,
        plugins: [ChartDataLabels],
        options: {
            plugins: {
                title: {
                    display: true,
                    text: result.title,
                },
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            let sum = 0;
                            Object.entries(ctx.parsed._stacks.y).forEach(([key, value]) => {
                                if(!isNaN(key) && Number.isInteger(parseFloat(key))) sum += value;
                            });
                            const percent = (100*ctx.parsed.y/sum).toFixed(2);
                            
                            return ctx.dataset.label + ' : ' + ctx.parsed.y + ' (' + percent + '%)';
                        }
                    },
                },
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    font: {
                        weight: 'bold',
                    },
                    formatter: (value, ctx) => {
                        let datasets = ctx.chart._metasets;
                        
                        if (ctx.datasetIndex === datasets.length - 1) {
                            let sum = 0;
                            
                            datasets.map(dataset => {
                                sum += dataset._parsed[ctx.dataIndex].y;
                            });
                            // console.log(sum);
                            if(sum>0) return sum; else return null;
                        }
                        else {
                            return null;
                        }
                    },
                }
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            },
        }
    };

    return config;
}
    
function download_chart_zoom(id_question)
{
    var a = document.createElement('a');
    a.href = canvas_zoom.toBase64Image();
    a.download = 'graphe_' + id_question + '.png';
    a.click();
}

</script>

