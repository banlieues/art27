<script>
       /*
        *   IMMPORTANT: Ce système fonctionne avec un form qui va soumettre en get
        *       les paramètres. Le bandata doit donc être placé sur un container
        *       qui contient et le formulaire de search et le tableau
        *       Nécessite d'ajouter un ban_order avec le champ qui va servir d'order
        *       banData_order_select_down (attribut à ajouter pour par defaut le champ actif et s'il est en up ou en down)
        *       Il a besoin d'un btn_search pour soumettre ensuite le formulaire
        *       Le form doit avoir avoir la class form_with_order
        */

    var sort_down='<i class="fas fa-sort-down"></i>';
    var sort_up='<i class="fas fa-sort-up"></i>';
    var sort_no_select='<i class="fas fa-sort"></i>';

    function initialize_banData(el)
    {
        var banData=el;
        var form=el.find(".form_with_order");
    
        if(form.length==0) form = $('form#searchOrderForm');
        //on ajoute deux inputs dans l'hidden
        //Input pour le nom du champ
        //input pour les sens de l'order
        form.append(' <input type="hidden" name="orderBy" class="ban_order_by" value="">');
        form.append(' <input type="hidden" name="orderDirection" class="ban_order_direction" value="">');

        $("th",banData).each(function()
        {
            var th=$(this);
        
            if(!$(this)[0].hasAttribute("ban_order"))
            {
                //si pas de ban_order definit sur le th, on ne fait rien
            }
            else if($(this)[0].hasAttribute("banData_order_select_ASC"))
            {
                if($(".fa-sort-down",th).length)
                {
                }
                else
                {
                    th.prepend(sort_down);
                }

                th.addClass("ban_th_over");
                var ban_order=$(this).attr("ban_order");
                $(".ban_order_by",form).val(ban_order);
                $(".ban_order_direction",form).val("ASC");
                th.addClass("text-nowrap");
                th.css("cursor","pointer");
                th.addClass("table-dark");;
            }
            else if($(this)[0].hasAttribute("banData_order_select_DESC"))
            {
                if($(".fa-sort-up",th).length)
                {
                }
                else
                {
                    th.prepend(sort_up);
                }
                //th.prepend(sort_up);
                th.addClass("ban_th_over");
                var ban_order=$(this).attr("ban_order");
                $(".ban_order_by",form).val(ban_order);
                $(".ban_order_direction",form).val("DESC");
                th.addClass("text-nowrap");
                th.css("cursor","pointer");
                th.addClass("table-dark");
            }
            else
            {
                if($(".fa-sort",th).length)
                {
                }
                else
                {
                    th.prepend('<span style="opacity:0.2">'+sort_no_select+'</span>&nbsp;');
                }
                
                th.addClass("ban_th_over");
                th.addClass("text-nowrap");
                th.css("cursor","pointer");
            }
        });
    }

    jQuery(document).ready(function()
    {
        //initialization des tables
        $(".banData").each(function(){
            initialize_banData($(this));
        })

        $(document).on("mouseover", ".ban_th_over", function()
        {
            $(this).css("opacity","0.2");
        });

        $(document).on("mouseout", ".ban_th_over", function()
        {
            $(this).css("opacity","1");
        });

        $(document).on("click", ".ban_th_over", function()
        {
            var banData=$(this).closest(".banData");

            var ban_order=$(this).attr("ban_order");
            let ban_order_by = $(".ban_order_by", banData);
            if(ban_order_by.length==0) ban_order_by = $(".ban_order_by", '#searchOrderForm');
            $(ban_order_by).val(ban_order);

            let ban_order_direction = $(".ban_order_direction", banData);
            if(ban_order_direction.length==0) ban_order_direction = $(".ban_order_direction", '#searchOrderForm');
            $(ban_order_direction).val("ASC");
            if($(this)[0].hasAttribute("banData_order_select_ASC"))
            {
                $(ban_order_direction).val("DESC");
            }
            else if($(this)[0].hasAttribute("banData_order_select_DESC"))
            {
                $(ban_order_direction).val("ASC");
            }

            let btn_search = $(".btn_search", banData);
            if(btn_search.length==0) btn_search = $('.btn_search[form="searchOrderForm"]');
            console.log(btn_search);
            $(btn_search).trigger("click");
        });

        $(document).on("change", ".ban_len", function()
        {
            var banData=$(this).closest(".banData");

            let btn_search = $(".btn_search", banData);
            if(btn_search.length==0) btn_search = $('.btn_search[form="searchOrderForm"]');
            //console.log(btn_search);
            $(btn_search).trigger("click");
        });
    })

</script>