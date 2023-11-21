
<div class="fixed-bottom bg-black p-2 text-body-secondary text-center border-top-theme" id="myCookieConsent">
    <a class="btn btn-success btn-sm float-end" id="cookieButton">
        <i class="<?php echo icon('ok'); ?>"></i> <?php echo lang('Cookies.understood'); ?>
    </a>
    <div class="pt-1">
        <?php echo lang('Cookies.usecookies'); ?>
        <a class="link text-primary" role="button" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalLive">
            <?php echo lang('Cookies.moredetails'); ?>
        </a>
    </div>
</div>

<div class="modal fade" id="exampleModalLive" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-top-theme">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo lang('Cookies.termstitle'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo lang('Cookies.termscontent'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?php echo lang('Buttons.close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
/* COOKIES CONSENT */

<?php if (COOKIES_CONSENT): ?>
    $(document).ready(function(){
        testFirstCookie();
    });

    $(document).on('click', '#cookieButton', function() {
        console.log('Understood');
        var expire = new Date();
        expire = new Date(expire.getTime() + 7776000000);
        document.cookie = "cookieCompliancyCookie=here; expires=" + expire;
        $("#myCookieConsent").hide(400);
    });
<?php endif; ?>


function GetCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg) return "here";
        i = document.cookie.indexOf(" ",i) + 1;
        if (i == 0) break;
    }
    return null;
}

function testFirstCookie() {
	var offset = new Date().getTimezoneOffset();
	// $("#timezoneOffset").text(offset);
    /* DETECT EUROPEAN TIME ZONES */
	if ((offset >= -180) && (offset <= 0)) {
		var visit = GetCookie("cookieCompliancyCookie");
		if (visit == null) {$("#myCookieConsent").fadeIn(400);}
        else {} // Already accepted		
	}
}
</script>
