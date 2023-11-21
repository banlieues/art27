<script>

// ------------------------------------------------------------------
// Resize iframe to fit content
// ------------------------------------------------------------------
$(document).on('shown.bs.tab', '#nav-prevfr-button, #nav-prevnl-button', function() {
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
    const height = content.scrollHeight + 30;
    elem.style.height = height + 'px';
}

// if(typeof get_iframe != 'function') {
//     function get_iframe(elem, url)
//     {
//         $.get(url, function(view) {
//             $(elem).html('<iframe width="100%" frameBorder="0"></iframe>');
//             const iframe = $('iframe', elem)[0];
//             const content = iframe.contentWindow.document;
//             content.open();
//             content.write(view);
//             content.close();
//             iframe_resize(iframe);
//         }); 
//     }    
// }

</script>