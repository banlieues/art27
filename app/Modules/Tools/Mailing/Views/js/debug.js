//function resizeIframe(iframe) {
//    iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";
//}

//$(document).on('shown.bs.collapse hidden.bs.collapse', '.collapse-cycle', function () {
//    if(parent.document.getElementById('iframe')) {
//        let iframe = parent.document.getElementById('iframe');
//        setIframeHeight(iframe);
//    }
//});

// event listener on iframe page
let get_message_frameHeight = function(event) {
    // Need to check for safety as we are going to process only our messages
    // So Check whether event with data(which contains any object) contains our message here its "FrameHeight"
   if (event.data === "FrameHeight") {

        //event.source contains parent page window object 
        //which we are going to use to send message back to main page here "abc.com/page"

        //parentSourceWindow = event.source;

        //Calculate the maximum height of the page
        var body = document.body, html = document.documentElement;
        var height = Math.max(body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight);

       // Send height back to parent page "abc.com/page"
        event.source.postMessage({ "FrameHeight": height }, "*"); 
        console.log('height from iframe_page : ' + height);
    }    
};
window.addEventListener('message', get_message_frameHeight);

//function onElementHeightChange(elm, callback) {
//    var lastHeight = elm.clientHeight, newHeight;
//
//    (function run() {
//        newHeight = elm.clientHeight;
//        if (lastHeight !== newHeight) callback(newHeight);
//        lastHeight = newHeight;
//
//        if (elm.onElementHeightChangeTimer) clearTimeout(elm.onElementHeightChangeTimer);
//
//        elm.onElementHeightChangeTimer = setTimeout(run, 500);
//    })();
//}
//
//onElementHeightChange(document.body, function(h) {
//    console.log('Body height changed:', h);
//    window.addEventListener('message', get_message_frameHeight);
//});

function js_inscr_public_new(elem) {
    let form = $(elem).closest('form');
    let url = $(form).attr('src');
    waiting_start(elem);
    $.ajax({
        type: "POST",
        url: url,
        data: $(form).serialize(),
        dataType: 'text',
        error: function(xhr, status, error){
            let errorMessage = xhr.status + ': ' + xhr.statusText;
            alert('Error - ' + errorMessage);
        },
        success: function(target) {
            window.location.replace(target);
        }
    });
}