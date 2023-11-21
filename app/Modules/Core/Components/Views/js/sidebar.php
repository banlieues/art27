<script>
    /* ------------------------------------------------------ */
    /* Storages of the differents sidebar menu states */
    /* ------------------------------------------------------ */
    document.addEventListener("DOMContentLoaded", function() {
        $('.sidebar-header').each(function() {
            const ref = $(this).attr('ref');
            let ls = localStorage.getItem('sidebar-' + ref);
            /* Load storages when page load / reload */
            if(ls) {
                $('#' + ref).addClass('show');
            }
        });

        $("#wrapper").toggleClass(localStorage.menutoggle);
        //$("#wrapper").css("margin-left","-250px");
    
        if($("#wrapper").hasClass("toggled"))
        {
            $(".navbar-brand").toggleClass("d-block d-none");
        }
    });
    $('.sidebar-header').click(function(e) {
        if (typeof(Storage) !== "undefined") 
        {
            const ref = $(this).attr('ref');
            let ls = localStorage.getItem('sidebar-' + ref);
            if (ls) {
                localStorage.removeItem('sidebar-' + ref);
            } else {
                localStorage.setItem('sidebar-' + ref, 'show');
            }
        }
        else 
        {
            /*Sorry! No Web Storage support ...*/
        }
    });

    /* ------------------------------------------------------ */
    /* Storage menu toggle state */
    /* ------------------------------------------------------ */
    $(document).ready(function() {
        /* Load storages when page load / reload */
        let ls = localStorage.getItem('menu-toggle');
        if(ls=='hidden') {
            $("#wrapper").toggleClass('toggled');
        }
    });
    $('#menutoggle').click(function(e) {
        if (typeof(Storage) !== "undefined") 
        {
            let ls = localStorage.getItem('menu-toggle');
            if (ls) {
                localStorage.removeItem('menu-toggle');
            } else {
                localStorage.setItem('menu-toggle', 'hidden');
            }
        }
        else 
        {
            /*Sorry! No Web Storage support ...*/
        }
    });

    /* TO SCROLL NAVBAR */
    $(document).ready(function() {
        //  $(document).on('scroll', function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 80) {
                $('#navbar-main').css('padding', '0px 9px');
                $('#logo-main').height(50);          
            }

            else {
                $('#navbar-main').css('padding', '5px 19px');
                $('#logo-main').height(60); 
            }
        });
    });

    /* TO TOGGLE SIDEBAR */
    $(document).ready(function() {
        $("#menutoggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            $(".navbar-brand").toggleClass("d-block d-none");
            // $("i", this).toggleClass("fa-bars fa-times");
            // .fadeToggle(1000)
        });
    });

    /* TO ANIMATE PROGRESS BAR */
    $(document).ready(function() {
        function progress_bar() {
            $('.progress-bar').each(function(index) {
                var random = Math.floor(Math.random() * 100) + 1;
                // $(this).animate({width: random + '%'}, 1000);
                $(this).attr('style', 'width: ' + random + '%');
                $(this).attr('aria-valuenow', random);
                $(this).text(random + '%');
            });
        }

        setInterval(function() {
            progress_bar();
        }, 2000);

        progress_bar();
    });
</script>